<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\ServicePackage;
use App\Models\ServiceCategory;
use App\Mail\BookingInvoiceMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutService
{
    /**
     * Process checkout and create bookings.
     *
     * @param array $validated
     * @return array
     * @throws \Exception
     */
    public function process(array $validated): array
    {
        $fullCart = session('cart', []);
        $checkoutKeys = session('checkout_keys');

        // Determine which items to process
        if ($checkoutKeys) {
            $cart = collect($fullCart)->filter(fn ($i) => in_array($i['key'], $checkoutKeys))->values()->all();
            $remainingCart = collect($fullCart)->filter(fn ($i) => ! in_array($i['key'], $checkoutKeys))->values()->all();
        } else {
            $cart = $fullCart;
            $remainingCart = [];
        }

        if (empty($cart)) {
            throw new \Exception('Keranjang masih kosong.');
        }

        // Update customer profile details
        Auth::user()->update([
            'phone' => $validated['phone'],
            'instagram' => $validated['instagram'],
            'address' => $validated['address'] ?? Auth::user()->address,
        ]);

        $createdBookings = [];

        DB::transaction(function () use ($cart, $validated, &$createdBookings) {
            foreach ($cart as $item) {
                $package = ServicePackage::with('category')->find($item['package_id']);
                $scheduleId = null;

                if ($package && ! empty($item['slot_waktu'])) {
                    $catId = $package->category_id;
                    $categorySlug = $package->category->slug;

                    if ($categorySlug === 'wedding' || $categorySlug === 'prewedding') {
                        $weddingCategory = ServiceCategory::where('slug', 'wedding')->first();
                        $preweddingCategory = ServiceCategory::where('slug', 'prewedding')->first();
                        $categoryIds = array_filter([$weddingCategory?->id, $preweddingCategory?->id]);

                        $maxBookings = ($item['slot_waktu'] === 'pagi') ? 2 : 1;

                        // Lock and check if blocked
                        $isBlocked = Schedule::whereIn('category_id', $categoryIds)
                            ->whereDate('tanggal', $item['booking_date'])
                            ->where('jenis_jadwal', $item['slot_waktu'])
                            ->where('status', 'diblokir')
                            ->lockForUpdate()
                            ->exists();

                        if ($isBlocked) {
                            throw new \Exception("Slot waktu " . ucfirst($item['slot_waktu']) . " pada tanggal " . $item['booking_date'] . " diblokir oleh MUA.");
                        }

                        // Lock and check active bookings count
                        $activeCount = Booking::whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
                            ->where(function ($q) use ($item) {
                                $targetDate = $item['booking_date'];
                                $targetSlot = $item['slot_waktu'];
                                $q->where(function ($sq) use ($targetDate, $targetSlot) {
                                    $sq->whereDate('tanggal_acara', $targetDate)
                                       ->where('slot_waktu', $targetSlot);
                                })
                                ->orWhere(function ($sq) use ($targetDate, $targetSlot) {
                                    $sq->where('notes', 'LIKE', '%' . $targetDate . '%')
                                       ->where('notes', 'LIKE', '%Slot Hari 2: ' . $targetSlot . '%');
                                })
                                ->orWhere(function ($sq) use ($targetDate, $targetSlot) {
                                    $sq->where('notes', 'LIKE', '%' . $targetDate . '%')
                                       ->where('notes', 'LIKE', '%Slot Hari 3: ' . $targetSlot . '%');
                                });
                            })
                            ->lockForUpdate()
                            ->count();

                        // Count how many times this specific date and slot is requested in the current item
                        $requestedInCurrent = 1;
                        if (!empty($item['booking_date_2']) && $item['booking_date_2'] === $item['booking_date'] && $item['slot_waktu_2'] === $item['slot_waktu']) {
                            $requestedInCurrent++;
                        }
                        if (!empty($item['booking_date_3']) && $item['booking_date_3'] === $item['booking_date'] && $item['slot_waktu_3'] === $item['slot_waktu']) {
                            $requestedInCurrent++;
                        }

                        if (($activeCount + $requestedInCurrent) > $maxBookings) {
                            throw new \Exception("Slot waktu " . ucfirst($item['slot_waktu']) . " pada tanggal " . $item['booking_date'] . " sudah dibooking pelanggan lain.");
                        }

                        // Find or create schedule slot
                        $schedule = Schedule::where('category_id', $catId)
                            ->whereDate('tanggal', $item['booking_date'])
                            ->where('jenis_jadwal', $item['slot_waktu'])
                            ->lockForUpdate()
                            ->first();

                        if (! $schedule) {
                            $schedule = Schedule::create([
                                'category_id' => $catId,
                                'tanggal' => $item['booking_date'],
                                'jenis_jadwal' => $item['slot_waktu'],
                                'jam_mulai' => $item['slot_waktu'] == 'pagi' ? '06:00' : '12:00',
                                'jam_selesai' => $item['slot_waktu'] == 'pagi' ? '11:00' : '16:00',
                                'kuota' => $maxBookings,
                                'terpakai' => 0,
                                'status' => 'tersedia',
                                'created_by' => Auth::id(),
                            ]);
                        }
                        $scheduleId = $schedule->id;

                        // Process secondary date if exists
                        if (! empty($item['booking_date_2'])) {
                            $isBlocked2 = Schedule::whereIn('category_id', $categoryIds)
                                ->whereDate('tanggal', $item['booking_date_2'])
                                ->where('jenis_jadwal', $item['slot_waktu_2'])
                                ->where('status', 'diblokir')
                                ->lockForUpdate()
                                ->exists();

                            if ($isBlocked2) {
                                throw new \Exception("Slot waktu " . ucfirst($item['slot_waktu_2']) . " pada tanggal " . $item['booking_date_2'] . " (Tanggal 2) diblokir oleh MUA.");
                            }

                            $activeCount2 = Booking::whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
                                ->where(function ($q) use ($item) {
                                    $targetDate = $item['booking_date_2'];
                                    $targetSlot = $item['slot_waktu_2'];
                                    $q->where(function ($sq) use ($targetDate, $targetSlot) {
                                        $sq->whereDate('tanggal_acara', $targetDate)
                                           ->where('slot_waktu', $targetSlot);
                                    })
                                    ->orWhere(function ($sq) use ($targetDate, $targetSlot) {
                                        $sq->where('notes', 'LIKE', '%' . $targetDate . '%')
                                           ->where('notes', 'LIKE', '%Slot Hari 2: ' . $targetSlot . '%');
                                    })
                                    ->orWhere(function ($sq) use ($targetDate, $targetSlot) {
                                        $sq->where('notes', 'LIKE', '%' . $targetDate . '%')
                                           ->where('notes', 'LIKE', '%Slot Hari 3: ' . $targetSlot . '%');
                                    });
                                })
                                ->lockForUpdate()
                                ->count();

                            // Count how many times this specific date and slot is requested in the current item
                            $requestedInCurrent2 = 1;
                            if ($item['booking_date'] === $item['booking_date_2'] && $item['slot_waktu'] === $item['slot_waktu_2']) {
                                $requestedInCurrent2++;
                            }
                            if (!empty($item['booking_date_3']) && $item['booking_date_3'] === $item['booking_date_2'] && $item['slot_waktu_3'] === $item['slot_waktu_2']) {
                                $requestedInCurrent2++;
                            }

                            $maxBookings2 = ($item['slot_waktu_2'] === 'pagi') ? 2 : 1;

                            if (($activeCount2 + $requestedInCurrent2) > $maxBookings2) {
                                throw new \Exception("Slot waktu " . ucfirst($item['slot_waktu_2']) . " pada tanggal " . $item['booking_date_2'] . " (Tanggal 2) sudah dibooking pelanggan lain.");
                            }

                            $schedule2 = Schedule::where('category_id', $catId)
                                ->whereDate('tanggal', $item['booking_date_2'])
                                ->where('jenis_jadwal', $item['slot_waktu_2'])
                                ->lockForUpdate()
                                ->first();

                            if (! $schedule2) {
                                Schedule::create([
                                    'category_id' => $catId,
                                    'tanggal' => $item['booking_date_2'],
                                    'jenis_jadwal' => $item['slot_waktu_2'],
                                    'jam_mulai' => $item['slot_waktu_2'] == 'pagi' ? '06:00' : '12:00',
                                    'jam_selesai' => $item['slot_waktu_2'] == 'pagi' ? '11:00' : '16:00',
                                    'kuota' => $maxBookings2,
                                    'terpakai' => 0,
                                    'status' => 'tersedia',
                                    'created_by' => Auth::id(),
                                ]);
                            }
                        }

                        // Process tertiary date if exists
                        if (! empty($item['booking_date_3'])) {
                            $isBlocked3 = Schedule::whereIn('category_id', $categoryIds)
                                ->whereDate('tanggal', $item['booking_date_3'])
                                ->where('jenis_jadwal', $item['slot_waktu_3'])
                                ->where('status', 'diblokir')
                                ->lockForUpdate()
                                ->exists();

                            if ($isBlocked3) {
                                throw new \Exception("Slot waktu " . ucfirst($item['slot_waktu_3']) . " pada tanggal " . $item['booking_date_3'] . " (Tanggal 3) diblokir oleh MUA.");
                            }

                            $activeCount3 = Booking::whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
                                ->where(function ($q) use ($item) {
                                    $targetDate = $item['booking_date_3'];
                                    $targetSlot = $item['slot_waktu_3'];
                                    $q->where(function ($sq) use ($targetDate, $targetSlot) {
                                        $sq->whereDate('tanggal_acara', $targetDate)
                                           ->where('slot_waktu', $targetSlot);
                                    })
                                    ->orWhere(function ($sq) use ($targetDate, $targetSlot) {
                                        $sq->where('notes', 'LIKE', '%' . $targetDate . '%')
                                           ->where('notes', 'LIKE', '%Slot Hari 2: ' . $targetSlot . '%');
                                    })
                                    ->orWhere(function ($sq) use ($targetDate, $targetSlot) {
                                        $sq->where('notes', 'LIKE', '%' . $targetDate . '%')
                                           ->where('notes', 'LIKE', '%Slot Hari 3: ' . $targetSlot . '%');
                                    });
                                })
                                ->lockForUpdate()
                                ->count();

                            // Count how many times this specific date and slot is requested in the current item
                            $requestedInCurrent3 = 1;
                            if ($item['booking_date'] === $item['booking_date_3'] && $item['slot_waktu'] === $item['slot_waktu_3']) {
                                $requestedInCurrent3++;
                            }
                            if ($item['booking_date_2'] === $item['booking_date_3'] && $item['slot_waktu_2'] === $item['slot_waktu_3']) {
                                $requestedInCurrent3++;
                            }

                            $maxBookings3 = ($item['slot_waktu_3'] === 'pagi') ? 2 : 1;

                            if (($activeCount3 + $requestedInCurrent3) > $maxBookings3) {
                                throw new \Exception("Slot waktu " . ucfirst($item['slot_waktu_3']) . " pada tanggal " . $item['booking_date_3'] . " (Tanggal 3) sudah dibooking pelanggan lain.");
                            }

                            $schedule3 = Schedule::where('category_id', $catId)
                                ->whereDate('tanggal', $item['booking_date_3'])
                                ->where('jenis_jadwal', $item['slot_waktu_3'])
                                ->lockForUpdate()
                                ->first();

                            if (! $schedule3) {
                                Schedule::create([
                                    'category_id' => $catId,
                                    'tanggal' => $item['booking_date_3'],
                                    'jenis_jadwal' => $item['slot_waktu_3'],
                                    'jam_mulai' => $item['slot_waktu_3'] == 'pagi' ? '06:00' : '12:00',
                                    'jam_selesai' => $item['slot_waktu_3'] == 'pagi' ? '11:00' : '16:00',
                                    'kuota' => $maxBookings3,
                                    'terpakai' => 0,
                                    'status' => 'tersedia',
                                    'created_by' => Auth::id(),
                                ]);
                            }
                        }

                    } elseif ($categorySlug === 'regular') {
                        $schedule = Schedule::where('category_id', $catId)
                            ->whereDate('tanggal', $item['booking_date'])
                            ->where('jenis_jadwal', $item['slot_waktu'])
                            ->lockForUpdate()
                            ->first();

                        if (! $schedule) {
                            throw new \Exception("Jadwal untuk slot waktu " . ucfirst($item['slot_waktu']) . " pada tanggal " . $item['booking_date'] . " belum dibuka oleh MUA.");
                        }

                        if ($schedule->status !== 'tersedia') {
                            throw new \Exception("Slot waktu " . ucfirst($item['slot_waktu']) . " pada tanggal " . $item['booking_date'] . " diblokir oleh MUA.");
                        }

                        // Lock and check active bookings count (maximum 3 bookings for Regular category)
                        $activeCount = Booking::whereDate('tanggal_acara', $item['booking_date'])
                            ->where('slot_waktu', $item['slot_waktu'])
                            ->whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima', 'selesai'])
                            ->lockForUpdate()
                            ->count();

                        if ($activeCount >= $schedule->kuota) {
                            throw new \Exception("Kuota MUA sudah penuh untuk slot waktu " . ucfirst($item['slot_waktu']) . " pada tanggal " . $item['booking_date'] . ".");
                        }

                        $scheduleId = $schedule->id;
                    }
                }

                $notes = $validated['notes'] ?? null;
                if (! empty($item['booking_date_2'])) {
                    $notes = ($notes ? $notes . "\n" : "") . "Tanggal Acara Kedua: " . $item['booking_date_2'];
                    if (! empty($item['slot_waktu_2'])) {
                        $notes .= "\nSlot Hari 2: " . $item['slot_waktu_2'];
                    }
                }
                if (! empty($item['booking_date_3'])) {
                    $notes = ($notes ? $notes . "\n" : "") . "Tanggal Acara Ketiga: " . $item['booking_date_3'];
                    if (! empty($item['slot_waktu_3'])) {
                        $notes .= "\nSlot Hari 3: " . $item['slot_waktu_3'];
                    }
                }

                $booking = Booking::create([
                    'booking_code' => 'LYB-'.strtoupper(Str::random(8)),
                    'user_id' => Auth::id(),
                    'package_id' => $item['package_id'],
                    'schedule_id' => $scheduleId,
                    'booking_date' => $item['booking_date'],
                    'tanggal_acara' => $item['booking_date'],
                    'slot_waktu' => $item['slot_waktu'] ?? null,
                    'tanggal_fitting' => $item['tanggal_fitting'] ?? null,
                    'softlens' => $item['softlens'] ?? false,
                    'subtotal' => $item['subtotal'] ?? $item['total_price'],
                    'addon_total' => $item['addon_total'] ?? 0,
                    'total_price' => $item['total_price'],
                    'dp_amount' => $item['dp_amount'],
                    'remaining_payment' => $item['remaining_payment'],
                    'sisa_pelunasan' => $item['remaining_payment'],
                    'status' => 'pending',
                    'payment_status' => 'belum_bayar',
                    'notes' => $notes,
                ]);

                // Save addons to booking_addons pivot table
                if (! empty($item['addons'])) {
                    foreach ($item['addons'] as $addonItem) {
                        $addonModel = Addon::find($addonItem['id']);
                        if ($addonModel) {
                            $booking->addons()->attach($addonModel->id, [
                                'price' => $addonModel->harga_default ?? $addonItem['price'],
                                'nama_addon' => $addonModel->name,
                                'qty' => 1,
                                'subtotal' => $addonItem['price'],
                                'is_pihak_lain' => $addonModel->is_pihak_lain ?? false,
                                'biaya_pihak_lain' => $addonModel->biaya_pihak_lain ?? 0,
                            ]);
                        }
                    }
                }

                // Save pending payment record with selected method
                $methodMap = [
                    'va' => 'Transfer Bank',
                    'qris' => 'QRIS',
                    'wallet' => 'E-Wallet',
                ];
                $selectedMethod = $methodMap[$validated['payment_method']] ?? $validated['payment_method'];

                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->dp_amount,
                    'proof_image' => null,
                    'status' => 'pending',
                    'metode_pembayaran' => $selectedMethod,
                ]);

                $createdBookings[] = $booking;
            }
        });

        // Update cart: remove only processed items, keep the rest
        if (empty($remainingCart)) {
            session()->forget('cart');
        } else {
            session(['cart' => $remainingCart]);
        }
        session()->forget('checkout_keys');

        // Send invoice email to customer for each created booking
        foreach ($createdBookings as $booking) {
            try {
                $booking->load(['user', 'package', 'addons']);
                if ($booking->user && $booking->user->email) {
                    Mail::to($booking->user->email)
                        ->send(new BookingInvoiceMail($booking, 'created'));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send booking invoice email for booking ' . $booking->booking_code . ': ' . $e->getMessage());
            }
        }

        return $createdBookings;
    }
}