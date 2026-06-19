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

        $bookings = Booking::where('user_id', $userId)
            ->with(['package', 'addons', 'payments', 'latestCancellationRequest'])
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
