<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Support\PreviewData;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        return view('customer.cart', compact('cart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => ['required', 'integer'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'softlens' => ['nullable', 'boolean'],
            'addons' => ['nullable', 'array'],
            'addons.*' => ['integer'],
            'action' => ['nullable', 'in:cart,checkout'],
        ]);

        $package = PreviewData::packageById((int) $validated['package_id']);
        abort_if(! $package, 404);

        $addons = PreviewData::addons()->whereIn('id', collect($validated['addons'] ?? [])->map(fn ($id) => (int) $id))->values();
        $addonTotal = $addons->sum('price');
        $subtotal = $package->price;
        $total = $subtotal + $addonTotal;

        $cart = session('cart', []);
        $cart[] = [
            'key' => uniqid('cart_', true),
            'package_id' => $package->id,
            'package_code' => $package->code,
            'package_name' => $package->name,
            'category_name' => $package->category->name,
            'booking_date' => $validated['booking_date'],
            'softlens' => (bool) ($validated['softlens'] ?? false),
            'addons' => $addons->map(fn ($addon) => ['id' => $addon->id, 'name' => $addon->name, 'price' => $addon->price])->values()->all(),
            'subtotal' => $subtotal,
            'addon_total' => $addonTotal,
            'total_price' => $total,
            'dp_amount' => $package->dp_amount,
            'remaining_payment' => $total - $package->dp_amount,
        ];
        session(['cart' => $cart]);

        if (($validated['action'] ?? 'cart') === 'checkout') {
            return redirect()->route('customer.checkout.index')->with('success', 'Paket berhasil ditambahkan. Silakan lanjut checkout.');
        }

        return redirect()->route('customer.cart.index')->with('success', 'Paket berhasil ditambahkan ke keranjang.');
    }

    public function destroy(string $key)
    {
        $cart = collect(session('cart', []))->reject(fn ($item) => $item['key'] === $key)->values()->all();
        session(['cart' => $cart]);

        return back()->with('success', 'Item keranjang dihapus.');
    }
}
