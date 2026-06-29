<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\ServicePackage;
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
        // Try fetching package from database first, fallback to PreviewData
        $package = ServicePackage::with('category')->find($request->package_id);
        if (! $package) {
            $package = PreviewData::packageById((int) $request->package_id);
        }
        abort_if(! $package, 404);

        $categorySlug = $package->category->slug ?? '';

        $is2xMakeup = $package && in_array($package->code, ['PKG-MU-2X', 'PKG-WED-SILVER', 'PKG-WED-GOLD', 'PKG-WED-GOLD-L']);
        $is3xMakeup = $package && in_array($package->code, ['PKG-MU-3X', 'PKG-WED-DIAMOND-P', 'PKG-WED-DIAMOND-L']);

        $rules = [
            'package_id' => ['required', 'integer'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'softlens' => ['required', 'boolean'],
            'addons' => ['nullable', 'array'],
            'addons.*' => ['integer'],
            'action' => ['nullable', 'in:cart,checkout'],
            'edit_key' => ['nullable', 'string'],
        ];

        if ($is2xMakeup || $is3xMakeup) {
            $rules['booking_date_2'] = ['required', 'date', 'after_or_equal:today'];
        } else {
            $rules['booking_date_2'] = ['nullable', 'date'];
        }

        if ($is3xMakeup) {
            $rules['booking_date_3'] = ['required', 'date', 'after_or_equal:today'];
        } else {
            $rules['booking_date_3'] = ['nullable', 'date'];
        }

        if ($categorySlug === 'wedding' || $categorySlug === 'prewedding' || $categorySlug === 'regular') {
            $rules['slot_waktu'] = ['required', 'string', 'in:pagi,siang'];
            if ($is2xMakeup || $is3xMakeup) {
                $rules['slot_waktu_2'] = ['required', 'string', 'in:pagi,siang'];
            } else {
                $rules['slot_waktu_2'] = ['nullable', 'string'];
            }
            if ($is3xMakeup) {
                $rules['slot_waktu_3'] = ['required', 'string', 'in:pagi,siang'];
            } else {
                $rules['slot_waktu_3'] = ['nullable', 'string'];
            }
        } else {
            $rules['slot_waktu'] = ['nullable', 'string'];
            $rules['slot_waktu_2'] = ['nullable', 'string'];
            $rules['slot_waktu_3'] = ['nullable', 'string'];
        }

        if ($categorySlug === 'baju') {
            $rules['tanggal_fitting'] = ['required', 'date', 'before:booking_date'];
        } else {
            $rules['tanggal_fitting'] = ['nullable', 'date'];
        }

        $validated = $request->validate($rules, [
            'booking_date.required' => 'Tanggal booking wajib dipilih.',
            'booking_date_2.required' => 'Tanggal booking kedua wajib dipilih untuk paket ini.',
            'booking_date_3.required' => 'Tanggal booking ketiga wajib dipilih untuk paket ini.',
            'softlens.required' => 'Status penggunaan softlens wajib dipilih.',
            'slot_waktu.required' => 'Slot waktu MUA pertama wajib dipilih.',
            'slot_waktu_2.required' => 'Slot waktu MUA kedua wajib dipilih.',
            'slot_waktu_3.required' => 'Slot waktu MUA ketiga wajib dipilih.',
            'tanggal_fitting.required' => 'Tanggal fitting wajib dipilih.',
            'tanggal_fitting.before' => 'Tanggal fitting harus sebelum tanggal booking.',
        ]);

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
