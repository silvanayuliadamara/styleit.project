<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBooking = Booking::count();

        $bookingPending = Booking::where('status', 'pending')->count();
        $bookingAktif = Booking::whereIn('status', ['confirmed', 'process', 'paid'])->count();
        $bookingSelesai = Booking::where('status', 'completed')->count();
        $bookingExpired = Booking::where('status', 'expired')->count();

        $latestBookings = Booking::with(['user', 'package'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBooking',
            'bookingPending',
            'bookingAktif',
            'bookingSelesai',
            'bookingExpired',
            'latestBookings'
        ));
    }
}
