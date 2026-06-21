<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function __construct(protected MidtransService $midtrans) {}

    public function getSnapToken(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        abort_if($booking->payment_status !== 'belum_bayar', 400, 'Booking ini sudah dibayar.');

        $token = $this->midtrans->getSnapToken($booking);

        return response()->json([
            'snap_token' => $token,
            'client_key' => config('midtrans.client_key'),
        ]);
    }

    public function notification(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $rawContent = json_decode($request->getContent(), true) ?? [];
        Log::info('Midtrans notification request received', [
            'app_debug' => config('app.debug'),
            'is_production' => config('midtrans.is_production'),
            'raw_content' => $rawContent,
            'input_order_id' => $request->input('order_id') ?? $rawContent['order_id'] ?? null,
        ]);

        $orderId = $request->input('order_id') ?? $rawContent['order_id'] ?? null;
        $transactionStatus = $request->input('transaction_status') ?? $rawContent['transaction_status'] ?? null;
        $fraudStatus = $request->input('fraud_status') ?? $rawContent['fraud_status'] ?? null;

        // Bypass validation on local/debug simulation to avoid Transaction::status() calling Midtrans API with a dummy transaction ID
        if ((config('app.debug') || !config('midtrans.is_production')) && !empty($orderId) && !empty($transactionStatus)) {
            // Log bypass
            Log::info('Bypassing Midtrans API validation for simulation');
        } else {
            try {
                $notification = new Notification;
                $orderId = $notification->order_id;
                $transactionStatus = $notification->transaction_status;
                $fraudStatus = $notification->fraud_status ?? null;
            } catch (\Exception $e) {
                Log::error('Midtrans notification error: '.$e->getMessage());

                return response()->json(['message' => 'invalid notification'], 400);
            }
        }

        $bookings = collect([]);
        if (str_starts_with($orderId, 'LYB-GP-')) {
            $parts = explode('-', $orderId);
            // parts: [0 => "LYB", 1 => "GP", 2 => id1, 3 => id2, ..., last => timestamp]
            $bookingIds = array_slice($parts, 2, count($parts) - 3);
            $bookings = Booking::whereIn('id', $bookingIds)->get();
        } else {
            $bookingCode = preg_replace('/-\d+$/', '', $orderId);
            $booking = Booking::where('booking_code', $bookingCode)->first();
            if ($booking) {
                $bookings->push($booking);
            }
        }

        if ($bookings->isEmpty()) {
            Log::warning('Midtrans notification: bookings not found', ['order_id' => $orderId]);

            return response()->json(['message' => 'bookings not found'], 404);
        }

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                foreach ($bookings as $booking) {
                    $booking->update([
                        'payment_status' => 'dp_diterima',
                        'status' => 'diterima',
                    ]);

                    $payment = $booking->payments()->where('status', 'pending')->first();
                    if ($payment) {
                        $payment->update([
                            'status' => 'diterima',
                            'paid_at' => now(),
                        ]);
                    } else {
                        $booking->payments()->create([
                            'amount' => $booking->dp_amount,
                            'proof_image' => null,
                            'status' => 'diterima',
                            'paid_at' => now(),
                        ]);
                    }
                }
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $newStatus = ($transactionStatus === 'expire') ? 'expired' : 'dibatalkan';
            foreach ($bookings as $booking) {
                $booking->update([
                    'payment_status' => 'belum_bayar',
                    'status' => $newStatus,
                ]);

                $payment = $booking->payments()->where('status', 'pending')->first();
                if ($payment) {
                    $payment->update([
                        'status' => 'ditolak',
                    ]);
                }
            }
        }

        return response()->json(['message' => 'ok']);
    }
}
