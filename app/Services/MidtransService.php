<?php

namespace App\Services;

use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Prevent curl hanging by setting connect and total timeouts, and fix SDK key 10023 bug
        Config::$curlOptions = [
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        ];

        // Hanya bypass SSL di local development
        if (app()->environment('local')) {
            Config::$curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
            Config::$curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;
        }
    }

    /**
     * Check if the system is running in sandbox/simulation mode.
     */
    public function isSandboxMode(): bool
    {
        return !config('midtrans.is_production');
    }

    public function getSnapToken(Booking $booking): string
    {
        $cacheKey = "midtrans_snap_token_booking_{$booking->id}";
        $cached = Cache::get($cacheKey);

        if ($cached && isset($cached['snap_token'])) {
            return $cached['snap_token'];
        }

        $orderId = $booking->booking_code.'-'.time();

        // Sandbox mode: return dummy token without calling Midtrans API
        if ($this->isSandboxMode()) {
            $dummyToken = 'sandbox-token-'.$booking->id.'-'.time();
            Cache::put($cacheKey, [
                'snap_token' => $dummyToken,
                'order_id' => $orderId,
            ], now()->addHours(24));
            Log::info('Sandbox mode: generated dummy snap token', ['booking_id' => $booking->id, 'token' => $dummyToken]);
            return $dummyToken;
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->dp_amount,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->user->phone,
            ],
            'item_details' => [
                [
                    'id' => 'DP-'.$booking->booking_code,
                    'price' => (int) $booking->dp_amount,
                    'quantity' => 1,
                    'name' => 'DP Booking '.$booking->booking_code,
                ],
            ],
            'custom_expiry' => [
                'expiry_duration' => 60,
                'unit' => 'minute',
            ],
            'callbacks' => [
                'finish' => route('customer.payment.success', $booking->booking_code),
                'unfinish' => route('customer.payment.instruction', $booking->booking_code),
                'error' => route('customer.payment.instruction', $booking->booking_code),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        Cache::put($cacheKey, [
            'snap_token' => $snapToken,
            'order_id' => $orderId,
        ], now()->addHours(24));

        return $snapToken;
    }

    public function getSnapTokenForBookings($bookings): string
    {
        if (empty($bookings)) {
            throw new \InvalidArgumentException('Bookings list cannot be empty.');
        }

        $bookingIds = collect($bookings)->pluck('id')->all();
        $totalDp = collect($bookings)->sum('dp_amount');

        sort($bookingIds);
        $cacheKey = "midtrans_snap_token_group_" . implode('_', $bookingIds);

        $cached = Cache::get($cacheKey);
        if ($cached && isset($cached['snap_token'])) {
            return $cached['snap_token'];
        }

        // Order ID format: LYB-GP-{id1}-{id2}-...-{timestamp}
        $orderId = 'LYB-GP-'.implode('-', $bookingIds).'-'.time();

        // Sandbox mode: return dummy token without calling Midtrans API
        if ($this->isSandboxMode()) {
            $dummyToken = 'sandbox-group-token-'.implode('-', $bookingIds).'-'.time();
            $groupData = [
                'snap_token' => $dummyToken,
                'order_id' => $orderId,
                'booking_ids' => $bookingIds,
            ];
            Cache::put($cacheKey, $groupData, now()->addHours(24));
            foreach ($bookings as $b) {
                Cache::put("midtrans_snap_token_booking_{$b->id}", [
                    'snap_token' => $dummyToken,
                    'order_id' => $orderId,
                    'group_booking_ids' => $bookingIds,
                ], now()->addHours(24));
            }
            Log::info('Sandbox mode: generated dummy group snap token', ['booking_ids' => $bookingIds, 'token' => $dummyToken]);
            return $dummyToken;
        }

        $firstBooking = $bookings[0];
        $user = $firstBooking->user;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalDp,
            ],
            'customer_details' => [
                'first_name' => $user->name ?? 'Customer',
                'email' => $user->email ?? '',
                'phone' => $user->phone ?? '',
            ],
            'item_details' => collect($bookings)->map(fn ($b) => [
                'id' => 'DP-'.$b->booking_code,
                'price' => (int) $b->dp_amount,
                'quantity' => 1,
                'name' => 'DP '.($b->package->name ?? 'Booking'),
            ])->all(),
            'custom_expiry' => [
                'expiry_duration' => 60,
                'unit' => 'minute',
            ],
            'callbacks' => [
                'finish' => route('customer.payment.success', $firstBooking->booking_code),
                'unfinish' => route('customer.payment.instruction', $firstBooking->booking_code),
                'error' => route('customer.payment.instruction', $firstBooking->booking_code),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $groupData = [
            'snap_token' => $snapToken,
            'order_id' => $orderId,
            'booking_ids' => $bookingIds,
        ];

        Cache::put($cacheKey, $groupData, now()->addHours(24));

        // Also save maps for each individual booking so they look up the same order_id
        foreach ($bookings as $b) {
            Cache::put("midtrans_snap_token_booking_{$b->id}", [
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'group_booking_ids' => $bookingIds,
            ], now()->addHours(24));
        }

        return $snapToken;
    }

    /**
     * Check transaction status directly from Midtrans and update DB if successful.
     * Returns true if status was updated/already paid, false otherwise.
     */
    public function checkAndConfirmPayment(Booking $booking): bool
    {
        if (in_array($booking->payment_status, ['dp_diterima', 'lunas'])) {
            return true;
        }

        // Sandbox mode: skip Midtrans API status check entirely
        if ($this->isSandboxMode()) {
            Log::info('Sandbox mode: skipping Midtrans API status check', ['booking_id' => $booking->id]);
            return false;
        }

        $cacheKey = "midtrans_snap_token_booking_{$booking->id}";
        $cached = Cache::get($cacheKey);

        if (!$cached || !isset($cached['order_id'])) {
            return false;
        }

        $orderId = $cached['order_id'];

        try {
            // Setup Config
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');
            Config::$curlOptions = [
                CURLOPT_CONNECTTIMEOUT => 2,
                CURLOPT_TIMEOUT => 3,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json'], // Fix for Midtrans SDK key 10023 bug
            ];

            // Hanya bypass SSL di local development
            if (app()->environment('local')) {
                Config::$curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
                Config::$curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;
            }

            $status = Transaction::status($orderId);

            // Convert status object to array if needed
            $status = (array) $status;

            $transactionStatus = $status['transaction_status'] ?? null;
            $fraudStatus = $status['fraud_status'] ?? null;

            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                if ($fraudStatus === 'accept' || $fraudStatus === null) {

                    // Identify which bookings to update
                    $bookingsToConfirm = collect([]);
                    if (!empty($cached['group_booking_ids'])) {
                        // Group checkout
                        $bookingsToConfirm = Booking::whereIn('id', $cached['group_booking_ids'])
                            ->where('payment_status', 'belum_bayar')
                            ->get();
                    } else {
                        // Single checkout
                        if ($booking->payment_status === 'belum_bayar') {
                            $bookingsToConfirm->push($booking);
                        }
                    }

                    if ($bookingsToConfirm->isNotEmpty()) {
                        foreach ($bookingsToConfirm as $b) {
                            $b->update([
                                'payment_status' => 'dp_diterima',
                                'status' => 'diterima',
                            ]);

                            $payment = $b->payments()->where('status', 'pending')->first();
                            if ($payment) {
                                $payment->update([
                                    'status' => 'diterima',
                                    'paid_at' => now(),
                                ]);
                            } else {
                                $b->payments()->create([
                                    'amount' => $b->dp_amount,
                                    'proof_image' => null,
                                    'status' => 'diterima',
                                    'metode_pembayaran' => $status['payment_type'] ?? 'Midtrans',
                                    'paid_at' => now(),
                                ]);
                            }
                        }
                    }

                    return true;
                }
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $newStatus = ($transactionStatus === 'expire') ? 'expired' : 'dibatalkan';
                
                $bookingsToUpdate = collect([]);
                if (!empty($cached['group_booking_ids'])) {
                    $bookingsToUpdate = Booking::whereIn('id', $cached['group_booking_ids'])->get();
                } else {
                    $bookingsToUpdate->push($booking);
                }

                foreach ($bookingsToUpdate as $b) {
                    $b->update([
                        'payment_status' => 'belum_bayar',
                        'status' => $newStatus,
                    ]);

                    $payment = $b->payments()->where('status', 'pending')->first();
                    if ($payment) {
                        $payment->update([
                            'status' => 'ditolak',
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning("Failed to auto-check Midtrans status for order {$orderId}: " . $e->getMessage());
        }

        return false;
    }
}

