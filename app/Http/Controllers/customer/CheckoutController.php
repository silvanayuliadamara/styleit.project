<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            'notes' => ['nullable', 'string', 'max:1000'],
            'proof_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $bookings = session('preview_bookings', []);
        foreach ($cart as $item) {
            $bookings[] = array_merge($item, [
                'booking_code' => 'LYB-PREV-' . now()->format('His') . '-' . strtoupper(Str::random(3)),
                'notes' => $validated['notes'] ?? null,
                'proof_uploaded' => $request->hasFile('proof_image'),
            ]);
        }

        session(['preview_bookings' => $bookings]);
        session()->forget('cart');

        return redirect()->route('customer.bookings.index')->with('success', 'Preview booking berhasil dibuat. Ini belum masuk database.');
    }
}
