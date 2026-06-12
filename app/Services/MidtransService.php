<?php

namespace App\Services;

use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function getSnapToken(Booking $booking): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_code . '-' . time(),
                'gross_amount' => (int) $booking->dp_amount,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->user->phone,
            ],
            'item_details' => [
                [
                    'id' => 'DP-' . $booking->booking_code,
                    'price' => (int) $booking->dp_amount,
                    'quantity' => 1,
                    'name' => 'DP Booking ' . $booking->booking_code,
                ],
            ],
        ];

        return Snap::getSnapToken($params);
    }
}
