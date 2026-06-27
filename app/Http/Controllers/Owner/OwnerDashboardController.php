<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        // Fetch all bookings for calculation
        $allBookings = Booking::with(['package.items', 'addons', 'payments'])->get();

        // 1. Total Booking
        $totalBookings = $allBookings->count();

        // 2. Menunggu DP (pending or awaiting confirmation)
        $bookingWaiting = $allBookings->whereIn('status', ['pending', 'menunggu_konfirmasi'])->count();

        // 3. DP Dibayar (status diterima, or payment_status dp_diterima)
        $bookingDiterima = $allBookings->where('payment_status', 'dp_diterima')->count();

        // 4. Lunas
        $bookingSelesai = $allBookings->where('payment_status', 'lunas')->count();

        // 5. Total Harga Booking (Gross booked value)
        $totalOmset = $allBookings->whereNotIn('status', ['ditolak', 'dibatalkan'])->sum('total_price');

        // 6. Pembayaran Diterima (Actual cash in hand before expenses)
        $totalPendapatan = $allBookings->whereNotIn('status', ['ditolak', 'dibatalkan'])->sum('total_dibayar');

        // 7. Biaya Pihak Lain
        $totalBiayaPihakLain = 0;
        $totalBiayaHenna = 0;
        $totalBiayaMelati = 0;
        $totalBiayaLainnya = 0;
        $totalGatewayFee = 0;

        foreach ($allBookings as $booking) {
            if (! in_array($booking->status, ['ditolak', 'dibatalkan'])) {
                $breakdown = $booking->pihak_lain_breakdown;
                $totalBiayaPihakLain += $breakdown['total'];
                $totalBiayaMelati += $breakdown['melati'];
                $totalBiayaHenna += $breakdown['henna'];
                $totalBiayaLainnya += $breakdown['lainnya'];
                
                $totalGatewayFee += $booking->gateway_fee;
            }
        }

        // 8. Estimasi Bersih Owner
        $estimasiBersihOwner = max(0, $totalPendapatan - $totalBiayaPihakLain - $totalGatewayFee);

        // Recent booking list
        $latestBookings = Booking::with(['user', 'package'])
            ->latest()
            ->take(5)
            ->get();

        // Fetch pending cancellation requests
        $pendingCancellations = Booking::whereHas('latestCancellationRequest', function ($q) {
            $q->where('status_persetujuan', 'diajukan');
        })->with(['user', 'package', 'latestCancellationRequest'])->get();

        // Chart Data: monthly booking counts & actual revenues for the last 6 months
        $monthlyRevenue = [];
        $monthlyBookings = [];
        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabels[] = $date->translatedFormat('M Y');
            
            // Filter bookings created in this month
            $monthBookings = $allBookings->filter(function ($b) use ($date) {
                return $b->created_at && $b->created_at->format('Y-m') === $date->format('Y-m');
            });
            
            $monthlyBookings[] = $monthBookings->count();
            $monthlyRevenue[] = (int) $monthBookings->whereNotIn('status', ['ditolak', 'dibatalkan'])->sum('total_dibayar');
        }

        return view('owner.dashboard', compact(
            'totalBookings',
            'bookingWaiting',
            'bookingDiterima',
            'bookingSelesai',
            'totalOmset',
            'totalPendapatan',
            'totalBiayaPihakLain',
            'totalBiayaHenna',
            'totalBiayaMelati',
            'totalBiayaLainnya',
            'estimasiBersihOwner',
            'latestBookings',
            'pendingCancellations',
            'monthLabels',
            'monthlyRevenue',
            'monthlyBookings'
        ));
    }
}
