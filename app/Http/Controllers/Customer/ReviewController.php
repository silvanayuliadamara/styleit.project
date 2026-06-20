<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, string $bookingCode)
    {
        $booking = Booking::where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->first();

        abort_if(! $booking, 404);

        // Guard: booking harus lunas dan selesai
        if ($booking->payment_status !== 'lunas' || $booking->status !== 'selesai') {
            return redirect()->back()->with('error', 'Review hanya bisa diberikan setelah layanan selesai dan pembayaran lunas.');
        }

        // Guard: belum pernah review
        if ($booking->review()->exists()) {
            return redirect()->back()->with('error', 'Anda sudah memberikan review untuk booking ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Silakan pilih rating bintang.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'komentar.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'package_id' => $booking->package_id,
            'rating' => $validated['rating'],
            'komentar' => $validated['komentar'],
            'status_review' => 'tampil',
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Ulasan Anda berhasil disimpan.');
    }
}
