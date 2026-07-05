<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
    /** Statuses considered "active" (not cancelled/rejected). */
    const ACTIVE_STATUSES = ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'];

    /** Statuses considered "cancelled/rejected". */
    const CANCELLED_STATUSES = ['ditolak', 'dibatalkan', 'expired'];

    protected $fillable = [
        'booking_code',
        'user_id',
        'checkout_id',
        'schedule_id',
        'package_id',
        'booking_date',
        'tanggal_acara',
        'softlens',
        'subtotal',
        'addon_total',
        'total_price',
        'dp_amount',
        'total_dibayar',
        'remaining_payment',
        'sisa_pelunasan',
        'status',
        'status_layanan',
        'payment_status',
        'notes',
        'slot_waktu',
        'tanggal_fitting',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'tanggal_acara' => 'date',
        'softlens' => 'boolean',
        'subtotal' => 'integer',
        'addon_total' => 'integer',
        'total_price' => 'integer',
        'dp_amount' => 'integer',
        'total_dibayar' => 'integer',
        'remaining_payment' => 'integer',
        'sisa_pelunasan' => 'integer',
        'tanggal_fitting' => 'date',
    ];

    /* ---- Relationships ---- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(ServicePackage::class, 'package_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /* ---- Scopes ---- */

    public function scopeActive($query)
    {
        return $query->whereIn('status', self::ACTIVE_STATUSES);
    }

    public function scopeCancelled($query)
    {
        return $query->whereIn('status', self::CANCELLED_STATUSES);
    }

    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class, 'booking_addons')
            ->withPivot(['price', 'addon_option_id', 'nama_addon', 'nama_option', 'qty', 'subtotal', 'is_pihak_lain', 'biaya_pihak_lain'])
            ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function cancellationRequests(): HasMany
    {
        return $this->hasMany(CancellationRequest::class);
    }

    public function latestCancellationRequest(): HasOne
    {
        return $this->hasOne(CancellationRequest::class)->latestOfMany();
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /* ---- Dynamic Accessors ---- */

    public function getGatewayFeeAttribute(): int
    {
        $hasOnlinePayment = $this->payments
            ->whereNull('proof_image')
            ->where('status', 'diterima')
            ->isNotEmpty();

        return $hasOnlinePayment ? (int) config('services.midtrans.gateway_fee', 4400) : 0;
    }

    public function getFittingPriorityAttribute(): ?int
    {
        if (! $this->tanggal_fitting) {
            return null;
        }

        $bookingIds = self::whereDate('tanggal_acara', $this->tanggal_acara)
            ->whereNotNull('tanggal_fitting')
            ->whereNotIn('status', ['ditolak', 'dibatalkan'])
            ->orderBy('tanggal_fitting', 'asc')
            ->orderBy('created_at', 'asc')
            ->pluck('id')
            ->toArray();

        $index = array_search($this->id, $bookingIds);

        return $index !== false ? $index + 1 : null;
    }

    public static function cancelExpiredBookings()
    {
        $expiredIds = self::where('status', 'pending')
            ->where('payment_status', 'belum_bayar')
            ->where('created_at', '<=', now()->subHour())
            ->pluck('id');

        if ($expiredIds->isNotEmpty()) {
            self::whereIn('id', $expiredIds)->update([
                'status' => 'dibatalkan',
                'status_layanan' => 'dibatalkan',
            ]);

            Payment::whereIn('booking_id', $expiredIds)
                ->where('status', 'pending')
                ->update([
                    'status' => 'ditolak',
                ]);
        }
    }

    public static function autoCancelPendingCancellations()
    {
        $pendingCancels = CancellationRequest::where('status_persetujuan', 'diajukan')
            ->where('created_at', '<=', now()->subHours(24))
            ->get();

        foreach ($pendingCancels as $cancelReq) {
            DB::transaction(function () use ($cancelReq) {
                $booking = $cancelReq->booking;
                if ($booking) {
                    $cancelReq->update([
                        'status_persetujuan' => 'disetujui',
                        'approved_by' => null,
                        'customer_dibaca' => false,
                    ]);

                    if ($booking->status === 'diterima') {
                        if ($booking->schedule_id) {
                            $schedule = $booking->schedule;
                            if ($schedule) {
                                $schedule->decrementTerpakai();
                            }
                        }
                        if ($booking->tanggal_acara_2) {
                            $schedule2 = Schedule::where('category_id', $booking->package->category_id)
                                ->whereDate('tanggal', $booking->tanggal_acara_2)
                                ->where('jenis_jadwal', $booking->slot_waktu_2)
                                ->first();
                            if ($schedule2) {
                                $schedule2->decrementTerpakai();
                            }
                        }
                        if ($booking->tanggal_acara_3) {
                            $schedule3 = Schedule::where('category_id', $booking->package->category_id)
                                ->whereDate('tanggal', $booking->tanggal_acara_3)
                                ->where('jenis_jadwal', $booking->slot_waktu_3)
                                ->first();
                            if ($schedule3) {
                                $schedule3->decrementTerpakai();
                            }
                        }
                    }

                    $booking->update([
                        'status' => 'dibatalkan',
                        'status_layanan' => 'dibatalkan',
                    ]);
                }
            });
        }
    }

    public function getPihakLainBreakdownAttribute(): array
    {
        $biayaMelati = 0;
        $biayaHenna = 0;
        $biayaLainnya = 0;

        if ($this->package && $this->package->items) {
            foreach ($this->package->items as $item) {
                $itemName = strtolower($item->name);
                $isMelati = (strpos($itemName, 'melati') !== false);
                $isHenna = (strpos($itemName, 'henna') !== false || strpos($itemName, 'kuku') !== false);

                if ($item->is_pihak_lain || $isMelati || $isHenna) {
                    $itemCost = $item->biaya_pihak_lain;
                    
                    if ($isMelati) {
                        $biayaMelati += $itemCost;
                    } elseif ($isHenna) {
                        $biayaHenna += $itemCost;
                    } else {
                        $biayaLainnya += $itemCost;
                    }
                }
            }
        }

        foreach ($this->addons as $addon) {
            $addonName = strtolower($addon->pivot->nama_addon);
            $isMelati = (strpos($addonName, 'melati') !== false);
            $isHenna = (strpos($addonName, 'henna') !== false || strpos($addonName, 'kuku') !== false);

            if ($addon->pivot->is_pihak_lain || $isMelati || $isHenna) {
                $addonCost = $addon->pivot->biaya_pihak_lain;
                if ($addonCost <= 0) {
                    // Fallback to addon's selling price (subtotal)
                    $addonCost = $addon->pivot->subtotal;
                } else {
                    $addonCost = $addonCost * ($addon->pivot->qty ?? 1);
                }

                if ($isMelati) {
                    $biayaMelati += $addonCost;
                } elseif ($isHenna) {
                    $biayaHenna += $addonCost;
                } else {
                    $biayaLainnya += $addonCost;
                }
            }
        }

        return [
            'melati' => $biayaMelati,
            'henna' => $biayaHenna,
            'lainnya' => $biayaLainnya,
            'total' => $biayaMelati + $biayaHenna + $biayaLainnya,
        ];
    }

    public function getTanggalAcara2Attribute(): ?string
    {
        if ($this->notes && preg_match('/Tanggal Acara Kedua: (\d{4}-\d{2}-\d{2})/', $this->notes, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function getTanggalAcara3Attribute(): ?string
    {
        if ($this->notes && preg_match('/Tanggal Acara Ketiga: (\d{4}-\d{2}-\d{2})/', $this->notes, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function getSlotWaktu2Attribute(): string
    {
        if ($this->notes && preg_match('/Slot Hari 2: (pagi|siang)/', $this->notes, $matches)) {
            return $matches[1];
        }
        return $this->slot_waktu;
    }

    public function getSlotWaktu3Attribute(): string
    {
        if ($this->notes && preg_match('/Slot Hari 3: (pagi|siang)/', $this->notes, $matches)) {
            return $matches[1];
        }
        return $this->slot_waktu;
    }
}

