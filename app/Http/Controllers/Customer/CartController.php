<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\ServicePackage;
use App\Support\PreviewData;
use App\Http\Requests\Cart\StoreCartRequest;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        return view('customer.cart', compact('cart'));
    }

    public function store(StoreCartRequest $request)
    {
        $validated = $request->validated();

        // Try fetching package from database first, fallback to PreviewData
        $package = ServicePackage::with('category')->find($validated['package_id']);
        if (! $package) {
            $package = PreviewData::packageById((int) $validated['package_id']);
        }
        abort_if(! $package, 404);

        $addons = collect([]);
        if (count($validated['addons'] ?? []) > 0) {
            $dbAddons = Addon::whereIn('id', $validated['addons'])->get();
            if ($dbAddons->isNotEmpty()) {
                $addons = $dbAddons->map(fn ($addon) => (object) [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'price' => $addon->harga_default ?: $addon->price,
                ]);
            } else {
                $addons = PreviewData::addons()->whereIn('id', collect($validated['addons'])->map(fn ($id) => (int) $id))->values();
            }
        }
        $addonTotal = $addons->sum('price');
        $subtotal = $package->price;
        $total = $subtotal + $addonTotal;

        $cart = session('cart', []);
        $editKey = $request->input('edit_key');

        $itemIndex = collect($cart)->search(fn ($item) => $item['key'] === $editKey);

        $cartData = [
            'key' => $editKey ?: uniqid('cart_', true),
            'package_id' => $package->id,
            'package_code' => $package->code,
            'package_name' => $package->name,
            'package_image' => $package->image ?? null,
            'category_name' => $package->category->name ?? '',
            'booking_date' => $validated['booking_date'],
            'booking_date_2' => $validated['booking_date_2'] ?? null,
            'booking_date_3' => $validated['booking_date_3'] ?? null,
            'softlens' => (bool) ($validated['softlens'] ?? false),
            'slot_waktu' => $validated['slot_waktu'] ?? null,
            'slot_waktu_2' => $validated['slot_waktu_2'] ?? null,
            'slot_waktu_3' => $validated['slot_waktu_3'] ?? null,
            'tanggal_fitting' => $validated['tanggal_fitting'] ?? null,
            'addons' => $addons->map(fn ($addon) => ['id' => $addon->id, 'name' => $addon->name, 'price' => $addon->price])->values()->all(),
            'subtotal' => $subtotal,
            'addon_total' => $addonTotal,
            'total_price' => $total,
            'dp_amount' => $package->dp_amount,
            'remaining_payment' => $total - $package->dp_amount,
        ];

        if ($itemIndex !== false) {
            $cart[$itemIndex] = $cartData;
            session(['cart' => $cart]);

            return redirect()->route('customer.cart.index')->with('success', 'Detail layanan berhasil diperbarui.');
        } else {
            $cart[] = $cartData;
            session(['cart' => $cart]);

            if (($validated['action'] ?? 'cart') === 'checkout') {
                session(['checkout_keys' => [$cartData['key']]]);

                return redirect()->route('customer.checkout.index')->with('success', 'Paket berhasil ditambahkan. Silakan lanjut checkout.');
            }

            return redirect()->route('customer.cart.index')->with('success', 'Paket berhasil ditambahkan ke keranjang.');
        }
    }

    public function select(Request $request)
    {
        $selectedKeys = $request->input('selected_keys', []);
        if (empty($selectedKeys)) {
            return redirect()->back()->with('warning', 'Pilih minimal satu layanan untuk checkout.');
        }
        session(['checkout_keys' => $selectedKeys]);

        return redirect()->route('customer.checkout.index');
    }

    public function destroy(string $key)
    {
        $cart = collect(session('cart', []))->reject(fn ($item) => $item['key'] === $key)->values()->all();
        session(['cart' => $cart]);

        // Clean up checkout keys if deleted item was selected
        $checkoutKeys = session('checkout_keys', []);
        if (in_array($key, $checkoutKeys)) {
            $checkoutKeys = array_values(array_diff($checkoutKeys, [$key]));
            session(['checkout_keys' => $checkoutKeys]);
        }

        return back()->with('success', 'Item keranjang dihapus.');
    }
}