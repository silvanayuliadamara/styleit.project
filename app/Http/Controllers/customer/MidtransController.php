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
    public function __construct(protected MidtransService $midtrans)
    {
    }

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

        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['message' => 'invalid notification'], 400);
        }

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? null;

        $bookingCode = preg_replace('/-\d+$/', '', $orderId);
        $booking = Booking::where('booking_code', $bookingCode)->first();

        if (! $booking) {
            Log::warning('Midtrans notification: booking not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'booking not found'], 404);
        }

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                $booking->update([
                    'payment_status' => 'dp_diterima',
                    'status' => 'diterima',
                ]);

                $booking->payments()->create([
                    'amount' => $booking->dp_amount,
                    'proof_image' => null,
                    'status' => 'diterima',
                    'paid_at' => now(),
                ]);
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $booking->update([
                'payment_status' => 'belum_bayar',
                'status' => 'dibatalkan',
            ]);
        }

        return response()->json(['message' => 'ok']);
    }
}
