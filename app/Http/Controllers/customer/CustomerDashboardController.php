<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Support\PreviewData;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $bookings = PreviewData::sessionBookings()->take(5);
        $activeBookingCount = $bookings->whereIn('status', ['pending', 'menunggu_konfirmasi', 'diterima'])->count();

        return view('customer.dashboard', compact('bookings', 'activeBookingCount'));
    }
}
