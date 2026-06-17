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
        $fullCart = session('cart', []);
        if (empty($fullCart)) {
            return redirect()->route('layanan.index')->with('warning', 'Keranjang masih kosong. Pilih paket terlebih dahulu.');
        }

        // Filter by selected keys if coming from cart select
        $checkoutKeys = session('checkout_keys');
        $cart = $checkoutKeys
            ? collect($fullCart)->filter(fn ($i) => in_array($i['key'], $checkoutKeys))->values()->all()
            : $fullCart;

        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('warning', 'Item yang dipilih tidak ditemukan. Silakan pilih lagi.');
        }

        return view('customer.checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        $fullCart     = session('cart', []);
        $checkoutKeys = session('checkout_keys');

        // Determine which items to process
        if ($checkoutKeys) {
            $cart          = collect($fullCart)->filter(fn ($i) => in_array($i['key'], $checkoutKeys))->values()->all();
            $remainingCart = collect($fullCart)->filter(fn ($i) => !in_array($i['key'], $checkoutKeys))->values()->all();
        } else {
            $cart          = $fullCart;
            $remainingCart = [];
        }

        if (empty($cart)) {
            return redirect()->route('layanan.index')->with('warning', 'Keranjang masih kosong.');
        }

        $validated = $request->validate([
            'phone'       => ['required', 'string', 'max:20'],
            'instagram'   => ['nullable', 'string', 'max:50'],
            'address'     => ['nullable', 'string', 'max:500'],
            'notes'       => ['nullable', 'string', 'max:1000'],
            'proof_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Update customer profile details
        Auth::user()->update([
            'phone'     => $validated['phone'],
            'instagram' => $validated['instagram'],
            'address'   => $validated['address'],
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('payments', 'public');
        }

        foreach ($cart as $item) {
            $package = \App\Models\ServicePackage::with('category')->find($item['package_id']);
            $scheduleId = null;

            if ($package && !empty($item['slot_waktu'])) {
                $catId = $package->category_id;
                
                // Find or create schedule slot
                $schedule = \App\Models\Schedule::where('category_id', $catId)
                    ->whereDate('tanggal', $item['booking_date'])
                    ->where('jenis_jadwal', $item['slot_waktu'])
                    ->first();

                if (!$schedule) {
                    $schedule = \App\Models\Schedule::create([
                        'category_id' => $catId,
                        'tanggal' => $item['booking_date'],
                        'jenis_jadwal' => $item['slot_waktu'],
                        'jam_mulai' => $item['slot_waktu'] == 'pagi' ? '06:00' : ($item['slot_waktu'] == 'siang' ? '12:00' : '17:00'),
                        'jam_selesai' => $item['slot_waktu'] == 'pagi' ? '11:00' : ($item['slot_waktu'] == 'siang' ? '16:00' : '21:00'),
                        'kuota' => $package->category->slug === 'regular' ? 3 : 1,
                        'terpakai' => 0,
                        'status' => 'tersedia',
                        'created_by' => Auth::id(),
                    ]);
                }
                $scheduleId = $schedule->id;
            }

            $booking = Booking::create([
                'booking_code'      => 'LYB-' . strtoupper(Str::random(8)),
                'user_id'           => Auth::id(),
                'package_id'        => $item['package_id'],
                'schedule_id'       => $scheduleId,
                'booking_date'      => $item['booking_date'],
                'tanggal_acara'     => $item['booking_date'],
                'slot_waktu'        => $item['slot_waktu'] ?? null,
                'tanggal_fitting'   => $item['tanggal_fitting'] ?? null,
                'softlens'          => $item['softlens'] ?? false,
                'subtotal'          => $item['subtotal'] ?? $item['total_price'],
                'addon_total'       => $item['addon_total'] ?? 0,
                'total_price'       => $item['total_price'],
                'dp_amount'         => $item['dp_amount'],
                'remaining_payment' => $item['remaining_payment'],
                'sisa_pelunasan'    => $item['remaining_payment'],
                'status'            => 'pending',
                'payment_status'    => $proofPath ? 'dp_diupload' : 'belum_bayar',
                'notes'             => $validated['notes'] ?? null,
            ]);

            // Save addons to booking_addons pivot table
            if (!empty($item['addons'])) {
                foreach ($item['addons'] as $addonItem) {
                    $addonModel = \App\Models\Addon::find($addonItem['id']);
                    if ($addonModel) {
                        $booking->addons()->attach($addonModel->id, [
                            'price' => $addonModel->harga_default ?? $addonItem['price'],
                            'nama_addon' => $addonModel->name,
                            'qty' => 1,
                            'subtotal' => $addonItem['price'],
                            'is_pihak_lain' => $addonModel->is_pihak_lain ?? false,
                            'biaya_pihak_lain' => $addonModel->biaya_pihak_lain ?? 0,
                        ]);
                    }
                }
            }

            if ($proofPath) {
                Payment::create([
                    'booking_id'  => $booking->id,
                    'amount'      => $item['dp_amount'],
                    'proof_image' => $proofPath,
                    'status'      => 'pending',
                ]);
            }
        }

        // Update cart: remove only processed items, keep the rest
        if (empty($remainingCart)) {
            session()->forget('cart');
        } else {
            session(['cart' => $remainingCart]);
        }
        session()->forget('checkout_keys');

        return redirect()->route('customer.bookings.index')
            ->with('success', 'Booking berhasil dibuat! Kami akan segera mengkonfirmasi pesanan Anda.');
    }
}
