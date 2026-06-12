<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('layanan.index')->with('warning', 'Keranjang masih kosong. Pilih paket terlebih dahulu.');
        }

        return view('customer.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('layanan.index')->with('warning', 'Keranjang masih kosong.');
        }

        $validated = $request->validate([
            'notes'       => ['nullable', 'string', 'max:1000'],
            'proof_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('payments', 'public');
        }

        foreach ($cart as $item) {
            $booking = Booking::create([
                'booking_code'      => 'LYB-' . strtoupper(Str::random(8)),
                'user_id'           => Auth::id(),
                'package_id'        => $item['package_id'],
                'booking_date'      => $item['booking_date'],
                'softlens'          => $item['softlens'] ?? false,
                'subtotal'          => $item['subtotal'] ?? $item['total_price'],
                'addon_total'       => $item['addon_total'] ?? 0,
                'total_price'       => $item['total_price'],
                'dp_amount'         => $item['dp_amount'],
                'remaining_payment' => $item['remaining_payment'],
                'status'            => 'pending',
                'payment_status'    => $proofPath ? 'dp_diupload' : 'belum_bayar',
                'notes'             => $validated['notes'] ?? null,
            ]);

            if ($proofPath) {
                Payment::create([
                    'booking_id'  => $booking->id,
                    'amount'      => $item['dp_amount'],
                    'proof_image' => $proofPath,
                    'status'      => 'pending',
                ]);
            }
        }

        session()->forget('cart');

        return redirect()->route('customer.bookings.index')
            ->with('success', 'Booking berhasil dibuat! Kami akan segera mengkonfirmasi pesanan Anda.');
    }
}
