<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CancellationRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Check Midtrans status for any pending unpaid bookings first
        $unpaidBookings = Booking::where('user_id', $userId)
            ->where('payment_status', 'belum_bayar')
            ->where('status', 'pending')
            ->get();

        if ($unpaidBookings->isNotEmpty()) {
            $midtransService = app(\App\Services\MidtransService::class);
            foreach ($unpaidBookings as $booking) {
                $midtransService->checkAndConfirmPayment($booking);
            }
        }

        $allBookings = Booking::where('user_id', $userId)->get();
        $totalBookingCount = $allBookings->count();
        $activeBookingCount = $allBookings->whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima'])->count();
        $completedBookingCount = $allBookings->where('status', 'selesai')->count();

        $bookings = Booking::where('user_id', $userId)
            ->with(['package', 'addons', 'payments', 'latestCancellationRequest', 'review'])
            ->latest()
            ->paginate(5);

        // Fetch unread cancellation result notifications for this customer
        $unreadCancellationNotifs = $userId ? CancellationRequest::whereHas('booking', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->whereIn('status_persetujuan', ['disetujui', 'ditolak'])
            ->where('customer_dibaca', false)
            ->with('booking')
            ->get() : collect();

        return view('customer.dashboard', compact(
            'bookings',
            'totalBookingCount',
            'activeBookingCount',
            'completedBookingCount',
            'unreadCancellationNotifs'
        ));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($user->id)],
            'instagram' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->instagram = $request->instagram;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('customer.profile.edit')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
