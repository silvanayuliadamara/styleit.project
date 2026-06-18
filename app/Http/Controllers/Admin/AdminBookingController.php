<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    /**
     * Scope query ke kategori "Khusus Baju" saja.
     */
    private function bajuScope()
    {
        $bajuCat = ServiceCategory::where('slug', 'baju')->first();

        return Booking::whereIn('package_id', function ($q) use ($bajuCat) {
            $q->select('id')->from('service_packages')->where('category_id', $bajuCat?->id);
        });
    }

    public function index(Request $request)
    {
        $query = $this->bajuScope()->with(['user', 'package']);

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if (! $this->bajuScope()->where('id', $booking->id)->exists()) {
            abort(403, 'Unauthorized action.');
        }
        $booking->load(['user', 'package', 'schedule', 'addons', 'payments', 'latestCancellationRequest']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function invoice(Booking $booking)
    {
        if (! $this->bajuScope()->where('id', $booking->id)->exists()) {
            abort(403, 'Unauthorized action.');
        }
        $booking->load(['user', 'package', 'schedule', 'addons', 'payments']);

        return view('shared.invoice', compact('booking'));
    }
}
