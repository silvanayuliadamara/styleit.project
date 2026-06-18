<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServiceCategory;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Admin hanya untuk kategori "Khusus Baju"
        $bajuCat = ServiceCategory::where('slug', 'baju')->first();

        $bajuQuery = Booking::whereIn('package_id', function ($q) use ($bajuCat) {
            $q->select('id')->from('service_packages')->where('category_id', $bajuCat?->id);
        });

        $totalBooking = (clone $bajuQuery)->count();
        $bookingPending = (clone $bajuQuery)->whereIn('status', ['pending', 'menunggu_konfirmasi'])->count();
        $bookingAktif = (clone $bajuQuery)->where('status', 'diterima')->count();
        $bookingSelesai = (clone $bajuQuery)->where('status', 'selesai')->count();
        $bookingExpired = (clone $bajuQuery)->where('status', 'expired')->count();

        $latestBookings = (clone $bajuQuery)
            ->with(['user', 'package'])
            ->latest()
            ->take(5)
            ->get();

        // Fetch pending cancellations for baju bookings (admin view only)
        $pendingCancellations = (clone $bajuQuery)
            ->whereHas('latestCancellationRequest', function ($q) {
                $q->where('status_persetujuan', 'diajukan');
            })
            ->with(['user', 'package', 'latestCancellationRequest'])
            ->get();

        return view('admin.dashboard', compact(
            'totalBooking',
            'bookingPending',
            'bookingAktif',
            'bookingSelesai',
            'bookingExpired',
            'latestBookings',
            'pendingCancellations'
        ));
    }
}
