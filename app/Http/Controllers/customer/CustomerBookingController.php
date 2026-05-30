<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Support\PreviewData;
use Illuminate\Http\Response;

class CustomerBookingController extends Controller
{
    public function index()
    {
        $bookings = PreviewData::sessionBookings();
        return view('customer.bookings.index', compact('bookings'));
    }

    public function show(string $booking)
    {
        $booking = PreviewData::sessionBookings()->firstWhere('booking_code', $booking);
        abort_if(! $booking, Response::HTTP_NOT_FOUND);

        return view('customer.bookings.show', compact('booking'));
    }
}
