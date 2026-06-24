<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\ServicePackage;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $needsAddress = false;
        foreach ($cart as $item) {
            $package = ServicePackage::with('category')->find($item['package_id']);
            if ($package) {
                $slug = $package->category->slug ?? '';
                if ($slug === 'wedding' || $slug === 'baju') {
                    $needsAddress = true;
                    break;
                }
            }
        }

        return view('customer.checkout', compact('cart', 'needsAddress'));
    }

    public function store(Request $request)
    {
        $fullCart = session('cart', []);
        $checkoutKeys = session('checkout_keys');

        // Determine which items to process
        if ($checkoutKeys) {
            $cart = collect($fullCart)->filter(fn ($i) => in_array($i['key'], $checkoutKeys))->values()->all();
            $remainingCart = collect($fullCart)->filter(fn ($i) => ! in_array($i['key'], $checkoutKeys))->values()->all();
        } else {
            $cart = $fullCart;
            $remainingCart = [];
        }

        if (empty($cart)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Keranjang masih kosong.'], 400);
            }

            return redirect()->route('layanan.index')->with('warning', 'Keranjang masih kosong.');
        }

        // Check if any package in cart needs address validation (Wedding or Baju categories)
        $needsAddress = false;
        foreach ($cart as $item) {
            $package = ServicePackage::with('category')->find($item['package_id']);
            if ($package) {
                $slug = $package->category->slug ?? '';
                if ($slug === 'wedding' || $slug === 'baju') {
                    $needsAddress = true;
                    break;
                }
            }
        }

        $rules = [
            'phone' => ['required', 'string', 'max:20'],
            'instagram' => ['required', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'string', 'in:va,qris,wallet'],
        ];

        if ($needsAddress) {
            $rules['address'] = ['required', 'string', 'max:500'];
        } else {
            $rules['address'] = ['nullable', 'string', 'max:500'];
        }

        $validated = $request->validate($rules, [
            'phone.required' => 'Nomor HP/WhatsApp wajib diisi.',
            'instagram.required' => 'Username Instagram wajib diisi.',
            'address.required' => 'Alamat lengkap wajib diisi untuk layanan Wedding / Khusus Baju.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
        ]);

        // Update customer profile details
        Auth::user()->update([
            'phone' => $validated['phone'],
            'instagram' => $validated['instagram'],
            'address' => $validated['address'] ?? Auth::user()->address,
        ]);

        $createdBookings = [];

        foreach ($cart as $item) {
            $package = ServicePackage::with('category')->find($item['package_id']);
            $scheduleId = null;

            if ($package && ! empty($item['slot_waktu'])) {
                $catId = $package->category_id;

                // Find or create schedule slot
                $schedule = Schedule::where('category_id', $catId)
                    ->whereDate('tanggal', $item['booking_date'])
                    ->where('jenis_jadwal', $item['slot_waktu'])
                    ->first();

                if (! $schedule) {
                    $schedule = Schedule::create([
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
                'booking_code' => 'LYB-'.strtoupper(Str::random(8)),
                'user_id' => Auth::id(),
                'package_id' => $item['package_id'],
                'schedule_id' => $scheduleId,
                'booking_date' => $item['booking_date'],
                'tanggal_acara' => $item['booking_date'],
                'slot_waktu' => $item['slot_waktu'] ?? null,
                'tanggal_fitting' => $item['tanggal_fitting'] ?? null,
                'softlens' => $item['softlens'] ?? false,
                'subtotal' => $item['subtotal'] ?? $item['total_price'],
                'addon_total' => $item['addon_total'] ?? 0,
                'total_price' => $item['total_price'],
                'dp_amount' => $item['dp_amount'],
                'remaining_payment' => $item['remaining_payment'],
                'sisa_pelunasan' => $item['remaining_payment'],
                'status' => 'pending',
                'payment_status' => 'belum_bayar',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Save addons to booking_addons pivot table
            if (! empty($item['addons'])) {
                foreach ($item['addons'] as $addonItem) {
                    $addonModel = Addon::find($addonItem['id']);
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

            // Save pending payment record with selected method
            $methodMap = [
                'va' => 'Virtual Account',
                'qris' => 'QRIS',
                'wallet' => 'E-Wallet',
            ];
            $selectedMethod = $methodMap[$validated['payment_method']] ?? $validated['payment_method'];

            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->dp_amount,
                'proof_image' => null,
                'status' => 'pending',
                'metode_pembayaran' => $selectedMethod,
            ]);

            $createdBookings[] = $booking;
        }

        // Update cart: remove only processed items, keep the rest
        if (empty($remainingCart)) {
            session()->forget('cart');
        } else {
            session(['cart' => $remainingCart]);
        }
        session()->forget('checkout_keys');

        if ($request->expectsJson()) {
            $midtransService = app(MidtransService::class);
            $snapToken = $midtransService->getSnapTokenForBookings($createdBookings);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'client_key' => config('midtrans.client_key'),
                'redirect_url' => route('customer.payment.instruction', $createdBookings[0]->booking_code),
                'booking_codes' => collect($createdBookings)->pluck('booking_code')->all(),
            ]);
        }

        return redirect()->route('customer.payment.instruction', $createdBookings[0]->booking_code)
            ->with('success', 'Booking berhasil dibuat! Silakan selesaikan pembayaran Anda.');
    }

    public function paymentInstruction($booking_code)
    {
        $booking = Booking::where('booking_code', $booking_code)->firstOrFail();

        // Ensure user is authorized
        abort_if($booking->user_id !== Auth::id(), 403);

        // Auto-check Midtrans status directly
        $midtransService = app(MidtransService::class);
        $midtransService->checkAndConfirmPayment($booking);

        $booking->refresh();

        if (in_array($booking->payment_status, ['dp_diterima', 'lunas'])) {
            return redirect()->route('customer.payment.success', $booking_code);
        }

        $payment = $booking->payments()->where('status', 'pending')->first();
        if (! $payment) {
            return redirect()->route('customer.dashboard')
                ->with('warning', 'Pembayaran untuk booking ini tidak ditemukan atau sudah diproses.');
        }

        $method = $payment->metode_pembayaran;

        // Generate virtual account number based on booking id
        $vaNumber = '88108'.str_pad($booking->id, 9, '0', STR_PAD_LEFT);

        // Midtrans token
        $midtransService = app(MidtransService::class);
        $snapToken = $midtransService->getSnapToken($booking);

        return view('customer.payment-instruction', compact('booking', 'payment', 'method', 'vaNumber', 'snapToken'));
    }

    public function paymentSuccess($booking_code)
    {
        $booking = Booking::where('booking_code', $booking_code)->firstOrFail();

        // Ensure user is authorized
        abort_if($booking->user_id !== Auth::id(), 403);

        $payment = $booking->payments()->latest()->first();

        if (! $payment) {
            return redirect()->route('customer.dashboard')
                ->with('warning', 'Data pembayaran tidak ditemukan.');
        }

        return view('customer.payment-success', compact('booking', 'payment'));
    }

    /**
     * Client-side fallback to confirm payment when Midtrans webhook
     * cannot reach localhost (development environment).
     *
     * Also handles group bookings: confirms all sibling bookings
     * created in the same checkout session.
     */
    public function confirmPayment(Request $request, $booking_code)
    {
        $booking = Booking::where('booking_code', $booking_code)->firstOrFail();
        abort_if($booking->user_id !== Auth::id(), 403);

        // Collect all bookings to confirm (single or group)
        $bookingCodes = $request->input('booking_codes', []);
        if (! empty($bookingCodes)) {
            // Group checkout: confirm all bookings by their codes
            $bookings = Booking::whereIn('booking_code', $bookingCodes)
                ->where('user_id', Auth::id())
                ->where('payment_status', 'belum_bayar')
                ->get();
        } else {
            // Single booking
            $bookings = collect([$booking])->filter(fn ($b) => $b->payment_status === 'belum_bayar');
        }

        if ($bookings->isEmpty()) {
            return response()->json(['message' => 'Pembayaran sudah dikonfirmasi sebelumnya.']);
        }

        foreach ($bookings as $b) {
            // Update booking status
            $b->update([
                'payment_status' => 'dp_diterima',
                'status' => 'diterima',
            ]);

            // Update payment record
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
                    'metode_pembayaran' => $request->input('payment_type', 'Midtrans'),
                    'paid_at' => now(),
                ]);
            }
        }

        return response()->json(['message' => 'Pembayaran berhasil dikonfirmasi.', 'success' => true]);
    }
}

