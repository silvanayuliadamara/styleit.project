<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Confirm DP payment for a booking.
     * Consolidates logic from CheckoutController, MidtransController, MidtransService, OwnerBookingController.
     *
     * @param  Booking  $booking
     * @param  string|null  $paymentMethod  e.g. 'Virtual Account', 'QRIS', 'Midtrans'
     * @param  int|null  $approvedBy  User ID of the person who approved (null for auto/Midtrans)
     */
    public function confirmDpPayment(Booking $booking, ?string $paymentMethod = null, ?int $approvedBy = null): void
    {
        if (in_array($booking->payment_status, ['dp_diterima', 'lunas'])) {
            return; // Already confirmed
        }

        DB::transaction(function () use ($booking, $paymentMethod, $approvedBy) {
            // 1. Update or create payment record
            $pendingPayment = $booking->payments()->where('status', 'pending')->first();
            if ($pendingPayment) {
                $pendingPayment->update([
                    'status' => 'diterima',
                    'paid_at' => now(),
                ]);
            } else {
                $booking->payments()->create([
                    'amount' => $booking->dp_amount,
                    'proof_image' => null,
                    'status' => 'diterima',
                    'metode_pembayaran' => $paymentMethod ?? 'Midtrans',
                    'paid_at' => now(),
                ]);
            }

            // 2. Update booking status
            $booking->update([
                'status' => 'diterima',
                'payment_status' => 'dp_diterima',
                'total_dibayar' => $booking->dp_amount,
                'sisa_pelunasan' => $booking->total_price - $booking->dp_amount,
                'status_layanan' => 'terjadwal',
            ]);

            // 3. Increment schedule usage (BUG-4 fix: this was missing in Midtrans/client-confirm paths)
            if ($booking->schedule_id) {
                $schedule = Schedule::find($booking->schedule_id);
                if ($schedule) {
                    $schedule->incrementTerpakai();
                }
            }
            if ($booking->tanggal_acara_2) {
                $schedule2 = Schedule::where('category_id', $booking->package->category_id)
                    ->whereDate('tanggal', $booking->tanggal_acara_2)
                    ->where('jenis_jadwal', $booking->slot_waktu)
                    ->first();
                if ($schedule2) {
                    $schedule2->incrementTerpakai();
                }
            }
        });
    }

    /**
     * Reject/cancel a DP payment (e.g., Midtrans expire/deny).
     */
    public function rejectPayment(Booking $booking, string $newStatus = 'dibatalkan'): void
    {
        DB::transaction(function () use ($booking, $newStatus) {
            $booking->update([
                'payment_status' => 'belum_bayar',
                'status' => $newStatus,
            ]);

            $payment = $booking->payments()->where('status', 'pending')->first();
            if ($payment) {
                $payment->update(['status' => 'ditolak']);
            }
        });
    }

    /**
     * Confirm pelunasan (full settlement) for a booking.
     */
    public function confirmLunas(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $sisa = $booking->total_price - $booking->total_dibayar;

            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $sisa,
                'status' => 'diterima',
                'paid_at' => now(),
                'tipe_pembayaran' => 'pelunasan',
            ]);

            $booking->update([
                'payment_status' => 'lunas',
                'total_dibayar' => $booking->total_price,
                'sisa_pelunasan' => 0,
            ]);
        });
    }

    /**
     * Cancel a booking and release its schedule slot.
     */
    public function cancelBooking(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            // Decrement schedule if was accepted
            if ($booking->status === 'diterima') {
                if ($booking->schedule_id) {
                    $schedule = Schedule::find($booking->schedule_id);
                    if ($schedule) {
                        $schedule->decrementTerpakai();
                    }
                }
                if ($booking->tanggal_acara_2) {
                    $schedule2 = Schedule::where('category_id', $booking->package->category_id)
                        ->whereDate('tanggal', $booking->tanggal_acara_2)
                        ->where('jenis_jadwal', $booking->slot_waktu)
                        ->first();
                    if ($schedule2) {
                        $schedule2->decrementTerpakai();
                    }
                }
            }

            $booking->update([
                'status' => 'dibatalkan',
                'status_layanan' => 'dibatalkan',
            ]);
        });
    }
}
