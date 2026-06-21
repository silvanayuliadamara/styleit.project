<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CancellationRequest;
use Illuminate\Support\Facades\Auth;

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

        $bookings = Booking::where('user_id', $userId)
            ->with(['package', 'addons', 'payments', 'latestCancellationRequest', 'review'])
            ->latest()
            ->get();

        $totalBookingCount = $bookings->count();
        $activeBookingCount = $bookings->whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima'])->count();
        $completedBookingCount = $bookings->where('status', 'selesai')->count();

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
}
