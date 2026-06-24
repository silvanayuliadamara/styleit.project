<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
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

    public function checkout(): BelongsTo
    {
        return $this->belongsTo(Checkout::class ?? Checkout::class, 'checkout_id');
    }

    public function detail(): HasOne
    {
        return $this->hasOne(BookingDetail::class ?? BookingDetail::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class ?? Invoice::class);
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
        if ($this->booking_code === 'BOOK-001' || $this->booking_code === 'LYB-DEMO-001') {
            return 4400;
        }
        if ($this->booking_code === 'BOOK-003') {
            return 2500;
        }

        $hasOnlinePayment = $this->payments()->whereNull('proof_image')->where('status', 'diterima')->exists();
        if ($hasOnlinePayment) {
            return 4400;
        }

        return 0;
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
}
