<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServicePackage;
use App\Services\MidtransService;
use App\Services\CheckoutService;
use App\Http\Requests\Checkout\StoreCheckoutRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

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

    public function store(StoreCheckoutRequest $request)
    {
        try {
            $createdBookings = $this->checkoutService->process($request->validated());
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        $isManualTransfer = $request->input('payment_method') === 'manual_transfer';

        if ($request->expectsJson()) {
            if ($isManualTransfer) {
                return response()->json([
                    'success' => true,
                    'manual_transfer' => true,
                    'redirect_url' => route('customer.payment.manual', $createdBookings[0]->booking_code),
                    'booking_codes' => collect($createdBookings)->pluck('booking_code')->all(),
                ]);
            }

            $midtransService = app(MidtransService::class);
            $snapToken = $midtransService->getSnapTokenForBookings($createdBookings);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'client_key' => config('midtrans.client_key'),
                'sandbox_mode' => !config('midtrans.is_production'),
                'sandbox_url' => !config('midtrans.is_production')
                    ? route('customer.payment.sandbox', $createdBookings[0]->booking_code)
                    : null,
                'redirect_url' => route('customer.payment.instruction', $createdBookings[0]->booking_code),
                'booking_codes' => collect($createdBookings)->pluck('booking_code')->all(),
            ]);
        }

        if ($isManualTransfer) {
            return redirect()->route('customer.payment.manual', $createdBookings[0]->booking_code)
                ->with('success', 'Booking berhasil dibuat! Silakan transfer dan upload bukti pembayaran.');
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
     * Show sandbox payment simulator page.
     * Only available when MIDTRANS_IS_PRODUCTION is false.
     */
    public function sandboxSimulator($booking_code)
    {
        // Block access in production mode
        abort_if(config('midtrans.is_production'), 404);

        $booking = Booking::where('booking_code', $booking_code)->firstOrFail();
        abort_if($booking->user_id !== Auth::id(), 403);

        // If already paid, redirect to success
        if (in_array($booking->payment_status, ['dp_diterima', 'lunas'])) {
            return redirect()->route('customer.payment.success', $booking_code);
        }

        // Collect all related booking codes (for group checkout)
        $bookingCodes = [$booking->booking_code];

        return view('customer.payment-sandbox-simulator', compact('booking', 'bookingCodes'));
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

        $bookingService = app(\App\Services\BookingService::class);
        foreach ($bookings as $b) {
            $bookingService->confirmDpPayment($b, $request->input('payment_type', 'Midtrans'));
        }

        return response()->json(['message' => 'Pembayaran berhasil dikonfirmasi.', 'success' => true]);
    }

    /**
     * Show the manual bank transfer instruction page.
     */
    public function manualTransfer($booking_code)
    {
        $booking = Booking::where('booking_code', $booking_code)->firstOrFail();
        abort_if($booking->user_id !== Auth::id(), 403);

        // If already paid, redirect to success
        if (in_array($booking->payment_status, ['dp_diterima', 'lunas'])) {
            return redirect()->route('customer.payment.success', $booking_code);
        }

        // If already uploaded proof, redirect to dashboard
        if ($booking->payment_status === 'dp_diupload') {
            return redirect()->route('customer.dashboard')
                ->with('success', 'Bukti transfer sudah diupload. Menunggu konfirmasi owner.');
        }

        $payment = $booking->payments()->where('status', 'pending')->first();

        return view('customer.payment-manual-transfer', compact('booking', 'payment'));
    }

    /**
     * Upload proof of manual bank transfer.
     */
    public function uploadBuktiTransfer(Request $request, $booking_code)
    {
        $booking = Booking::where('booking_code', $booking_code)->firstOrFail();
        abort_if($booking->user_id !== Auth::id(), 403);

        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ], [
            'proof_image.required' => 'Bukti transfer wajib diupload.',
            'proof_image.image' => 'File harus berupa gambar.',
            'proof_image.mimes' => 'Format gambar harus JPEG, PNG, atau JPG.',
            'proof_image.max' => 'Ukuran gambar maksimal 10MB.',
        ]);

        $path = $request->file('proof_image')->store('payment_proofs', 'public');

        // Update the pending payment with proof image
        $payment = $booking->payments()->where('status', 'pending')->first();
        if ($payment) {
            $payment->update([
                'proof_image' => $path,
            ]);
        } else {
            $booking->payments()->create([
                'amount' => $booking->dp_amount,
                'proof_image' => $path,
                'status' => 'pending',
                'metode_pembayaran' => 'Transfer Manual',
            ]);
        }

        // Update booking status to waiting for owner confirmation
        $booking->update([
            'status' => 'menunggu_konfirmasi',
            'payment_status' => 'dp_diupload',
        ]);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Bukti transfer berhasil diupload! Menunggu konfirmasi dari admin.');
    }
}