<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CancellationRequest;
use App\Support\PreviewData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CustomerBookingController extends Controller
{
    public function index()
    {
        return redirect()->route('customer.dashboard');
    }

    public function show(string $bookingCode)
    {
        $dbBooking = Booking::where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->with(['user', 'package', 'schedule', 'addons', 'payments', 'latestCancellationRequest'])
            ->first();

        if ($dbBooking) {
            $booking = $dbBooking;
        } else {
            $booking = PreviewData::sessionBookings()->firstWhere('booking_code', $bookingCode);
        }

        abort_if(! $booking, Response::HTTP_NOT_FOUND);

        return view('customer.bookings.show', compact('booking'));
    }

    public function invoice(string $bookingCode)
    {
        $dbBooking = Booking::where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->with(['user', 'package', 'schedule', 'addons', 'payments', 'latestCancellationRequest'])
            ->first();

        if ($dbBooking) {
            $booking = $dbBooking;
        } else {
            $booking = PreviewData::sessionBookings()->firstWhere('booking_code', $bookingCode);
        }

        abort_if(! $booking, Response::HTTP_NOT_FOUND);

        return view('shared.invoice', compact('booking'));
    }

    public function cancel(Request $request, string $bookingCode)
    {
        $booking = Booking::where('booking_code', $bookingCode)
            ->where('user_id', Auth::id())
            ->first();

        if ($booking) {
            // Check if there is already a pending cancellation request
            $existingRequest = \App\Models\CancellationRequest::where('booking_id', $booking->id)
                ->where('status_persetujuan', 'diajukan')
                ->exists();

            if ($existingRequest) {
                return redirect()->back()->with('error', 'Permohonan pembatalan untuk booking ini sudah diajukan sebelumnya.');
            }

            $validated = $request->validate([
                'alasan' => 'required|string|max:500',
                'bank_name' => 'required|string|max:100',
                'bank_account' => 'required|string|max:100',
                'account_holder' => 'required|string|max:100',
            ], [
                'alasan.required' => 'Alasan pembatalan wajib diisi.',
                'bank_name.required' => 'Nama bank wajib diisi.',
                'bank_account.required' => 'Nomor rekening wajib diisi.',
                'account_holder.required' => 'Nama pemilik rekening wajib diisi.',
            ]);

            if (in_array($booking->status, ['pending', 'menunggu_konfirmasi', 'diterima'])) {
                // Format alasan to include bank details
                $formattedAlasan = "Alasan: " . $validated['alasan'] . "\n"
                    . "Rekening Refund: " . $validated['bank_name'] . " - " . $validated['bank_account'] . " a.n. " . $validated['account_holder'];

                // Create cancellation request
                \App\Models\CancellationRequest::create([
                    'booking_id' => $booking->id,
                    'alasan' => $formattedAlasan,
                    'status_persetujuan' => 'diajukan',
                ]);

                return redirect()->back()->with('success', 'Permohonan pembatalan berhasil dikirim. Menunggu persetujuan Admin/Owner.');
            }
            return redirect()->back()->with('error', 'Booking tidak dapat dibatalkan pada status ini.');
        }

        // Fallback for session bookings
        return redirect()->back()->with('success', 'Permohonan pembatalan demo berhasil dikirim (simulasi).');
    }

    /**
     * Customer dismisses (marks as read) a cancellation notification.
     */
    public function dismissCancellationNotif(string $id)
    {
        $cancelReq = CancellationRequest::whereHas('booking', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        // Only mark as read if it has been resolved
        if (in_array($cancelReq->status_persetujuan, ['disetujui', 'ditolak'])) {
            $cancelReq->update(['customer_dibaca' => true]);
        }

        return redirect()->route('customer.dashboard');
    }
}
