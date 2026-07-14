<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'package', 'schedule']);

        // Filters
        if ($request->filled('status')) {
            if ($request->status === 'dibatalkan') {
                $query->whereIn('status', Booking::CANCELLED_STATUSES);
            } else {
                $query->where('status', $request->status);
            }
        } elseif ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
            if ($request->payment_status !== 'dp_dikembalikan') {
                $query->whereNotIn('status', Booking::CANCELLED_STATUSES);
            }
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

        return view('owner.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'package', 'schedule', 'addons', 'payments', 'latestCancellationRequest']);

        return view('owner.bookings.show', compact('booking'));
    }

    public function invoice(Booking $booking)
    {
        $booking->load(['user', 'package', 'schedule', 'addons', 'payments', 'latestCancellationRequest']);

        return view('shared.invoice', compact('booking'));
    }

    /**
     * Setujui DP yang diupload customer.
     */
    public function confirmDp(Request $request, Booking $booking)
    {
        $bookingService = app(\App\Services\BookingService::class);
        $bookingService->confirmDpPayment($booking);

        return redirect()->route('owner.bookings.show', $booking->id)
            ->with('success', 'Pembayaran DP berhasil dikonfirmasi dan jadwal telah diperbarui.');
    }

    /**
     * Konfirmasi pelunasan booking.
     */
    public function confirmLunas(Booking $booking)
    {
        $bookingService = app(\App\Services\BookingService::class);
        $bookingService->confirmLunas($booking);

        return redirect()->route('owner.bookings.show', $booking->id)
            ->with('success', 'Pelunasan booking berhasil dikonfirmasi.');
    }

    /**
     * Update status booking secara umum (batal, selesai, dll.)
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:diterima,ditolak,selesai,dibatalkan',
            'status_layanan' => 'nullable|in:pending,terjadwal,selesai,dibatalkan',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $validated['status'];

        DB::transaction(function () use ($booking, $oldStatus, $newStatus, $validated) {
            $updateData = ['status' => $newStatus];
            if (isset($validated['status_layanan'])) {
                $updateData['status_layanan'] = $validated['status_layanan'];
            } elseif ($newStatus === 'selesai') {
                $updateData['status_layanan'] = 'selesai';
            } elseif (in_array($newStatus, ['ditolak', 'dibatalkan'])) {
                $updateData['status_layanan'] = 'dibatalkan';
            }

            $booking->update($updateData);

            // Jika status berubah dari aktif (diterima) menjadi batal/tolak, decrement terpakai
            if ($oldStatus === 'diterima' && in_array($newStatus, ['ditolak', 'dibatalkan'])) {
                if ($booking->schedule_id) {
                    $schedule = Schedule::find($booking->schedule_id);
                    if ($schedule) {
                        $schedule->decrementTerpakai();
                    }
                }
                if ($booking->tanggal_acara_2) {
                    $schedule2 = Schedule::where('category_id', $booking->package->category_id)
                        ->whereDate('tanggal', $booking->tanggal_acara_2)
                        ->where('jenis_jadwal', $booking->slot_waktu_2)
                        ->first();
                    if ($schedule2) {
                        $schedule2->decrementTerpakai();
                    }
                }
                if ($booking->tanggal_acara_3) {
                    $schedule3 = Schedule::where('category_id', $booking->package->category_id)
                        ->whereDate('tanggal', $booking->tanggal_acara_3)
                        ->where('jenis_jadwal', $booking->slot_waktu_3)
                        ->first();
                    if ($schedule3) {
                        $schedule3->decrementTerpakai();
                    }
                }
            }
            // Sebaliknya, jika status berubah dari pending/batal ke diterima, increment terpakai
            elseif ($oldStatus !== 'diterima' && $newStatus === 'diterima') {
                if ($booking->schedule_id) {
                    $schedule = Schedule::find($booking->schedule_id);
                    if ($schedule) {
                        $schedule->incrementTerpakai();
                    }
                }
                if ($booking->tanggal_acara_2) {
                    $schedule2 = Schedule::where('category_id', $booking->package->category_id)
                        ->whereDate('tanggal', $booking->tanggal_acara_2)
                        ->where('jenis_jadwal', $booking->slot_waktu_2)
                        ->first();
                    if ($schedule2) {
                        $schedule2->incrementTerpakai();
                    }
                }
                if ($booking->tanggal_acara_3) {
                    $schedule3 = Schedule::where('category_id', $booking->package->category_id)
                        ->whereDate('tanggal', $booking->tanggal_acara_3)
                        ->where('jenis_jadwal', $booking->slot_waktu_3)
                        ->first();
                    if ($schedule3) {
                        $schedule3->incrementTerpakai();
                    }
                }
            }
        });

        return redirect()->route('owner.bookings.show', $booking->id)
            ->with('success', 'Status booking berhasil diperbarui.');
    }

    public function confirmCancel(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $cancelReq = $booking->latestCancellationRequest;
        if (! $cancelReq || $cancelReq->status_persetujuan !== 'diajukan') {
            return redirect()->back()->with('error', 'Tidak ada pengajuan pembatalan aktif.');
        }

        DB::transaction(function () use ($booking, $cancelReq, $validated) {
            if ($validated['action'] === 'approve') {
                // Check if customer has already paid DP
                $hasPaidDp = $booking->total_dibayar > 0;
                $dpAmount = $booking->total_dibayar;

                $cancelReq->update([
                    'status_persetujuan' => 'disetujui',
                    'approved_by' => auth()->id(),
                    'customer_dibaca' => false,
                    'dp_dikembalikan' => $hasPaidDp,
                    'jumlah_dp_dikembalikan' => $hasPaidDp ? $dpAmount : null,
                ]);

                $bookingService = app(\App\Services\BookingService::class);
                $bookingService->cancelBooking($booking);

                // Update payment_status to reflect DP refund
                if ($hasPaidDp) {
                    $booking->update(['payment_status' => 'dp_dikembalikan']);
                }
            } else {
                $cancelReq->update([
                    'status_persetujuan' => 'ditolak',
                    'approved_by' => auth()->id(),
                    'customer_dibaca' => false,
                ]);
            }
        });

        $msg = $validated['action'] === 'approve'
            ? 'Pengajuan pembatalan disetujui. Booking dibatalkan.'
            : 'Pengajuan pembatalan ditolak. Status booking tetap.';

        return redirect()->back()->with('success', $msg);
    }

    public function laporan(Request $request)
    {
        $filterBulan = $request->input('bulan');
        $filterTahun = $request->input('tahun', date('Y'));

        $query = Booking::with(['user', 'package.items', 'addons', 'payments'])
            ->whereNotIn('status', Booking::CANCELLED_STATUSES);

        if ($filterBulan) {
            $query->whereMonth('tanggal_acara', $filterBulan);
        }
        if ($filterTahun) {
            $query->whereYear('tanggal_acara', $filterTahun);
        }

        // Clone query for summary calculation (before pagination)
        $summaryBookings = (clone $query)->get();

        $totalHargaBooking = 0;
        $totalDiterima = 0;
        $totalBiayaPihakLain = 0;
        $totalBiayaMelati = 0;
        $totalBiayaHenna = 0;
        $totalBiayaLainnya = 0;
        $totalGatewayFee = 0;
        $totalBersihOwner = 0;

        foreach ($summaryBookings as $booking) {
            $breakdown = $booking->pihak_lain_breakdown;
            $totalHargaBooking += $booking->total_price;
            $totalDiterima += $booking->total_dibayar;
            $totalBiayaPihakLain += $breakdown['total'];
            $totalBiayaMelati += $breakdown['melati'];
            $totalBiayaHenna += $breakdown['henna'];
            $totalBiayaLainnya += $breakdown['lainnya'];
            $totalGatewayFee += $booking->gateway_fee;

            $bersih = $booking->total_dibayar > 0
                ? max(0, $booking->total_dibayar - $breakdown['total'] - $booking->gateway_fee)
                : max(0, $booking->dp_amount - $breakdown['total']);
            $totalBersihOwner += $bersih;
        }

        // Paginated bookings for table display
        $bookings = $query->latest('tanggal_acara')->paginate(10)->withQueryString();

        // Attach computed fields for each paginated booking
        foreach ($bookings as $booking) {
            $breakdown = $booking->pihak_lain_breakdown;
            $booking->biaya_pihak_lain = $breakdown['total'];
            $booking->biaya_melati = $breakdown['melati'];
            $booking->biaya_henna = $breakdown['henna'];
            $booking->biaya_lainnya = $breakdown['lainnya'];
            $booking->bersih_owner = $booking->total_dibayar > 0
                ? max(0, $booking->total_dibayar - $breakdown['total'] - $booking->gateway_fee)
                : max(0, $booking->dp_amount - $breakdown['total']);
        }

        // Available years for filter dropdown
        $availableYears = Booking::selectRaw('YEAR(tanggal_acara) as tahun')
            ->whereNotNull('tanggal_acara')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->filter()
            ->values();
        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }

        return view('owner.laporan', compact(
            'bookings',
            'totalHargaBooking',
            'totalDiterima',
            'totalBiayaPihakLain',
            'totalBiayaMelati',
            'totalBiayaHenna',
            'totalBiayaLainnya',
            'totalGatewayFee',
            'totalBersihOwner',
            'filterBulan',
            'filterTahun',
            'availableYears'
        ));
    }

    /**
     * Build the base query for laporan export (shared by CSV and PDF).
     */
    private function buildLaporanQuery(Request $request)
    {
        $query = Booking::with(['user', 'package.items', 'addons', 'payments'])
            ->whereNotIn('status', Booking::CANCELLED_STATUSES);

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_acara', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_acara', $request->tahun);
        }

        return $query;
    }

    public function exportLaporanCsv(Request $request)
    {
        $bookings = $this->buildLaporanQuery($request)->get();

        $periodLabel = '';
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $periodLabel = '_' . $request->tahun . '-' . str_pad($request->bulan, 2, '0', STR_PAD_LEFT);
        } elseif ($request->filled('tahun')) {
            $periodLabel = '_' . $request->tahun;
        }
        $fileName = 'Laporan_Keuangan' . $periodLabel . '_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            'Kode Booking', 'Customer', 'Paket', 'Tanggal Acara',
            'Total Harga', 'DP Wajib', 'Total Dibayar', 'Sisa Pelunasan',
            'Biaya Pihak Lain (Total)', 'Pihak Lain: Melati', 'Pihak Lain: Henna', 'Pihak Lain: Lainnya',
            'Gateway Fee', 'Bersih Owner', 'Status',
        ];

        $callback = function () use ($bookings, $columns) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, $columns, ';');

            foreach ($bookings as $booking) {
                $breakdown = $booking->pihak_lain_breakdown;
                $biayaP = $breakdown['total'];

                $bersih = $booking->total_dibayar > 0
                    ? max(0, $booking->total_dibayar - $biayaP - $booking->gateway_fee)
                    : max(0, $booking->dp_amount - $biayaP);

                fputcsv($file, [
                    $booking->booking_code,
                    $booking->user->name ?? '-',
                    $booking->package->name ?? '-',
                    $booking->tanggal_acara ? $booking->tanggal_acara->format('Y-m-d') : ($booking->booking_date ? $booking->booking_date->format('Y-m-d') : '-'),
                    $booking->total_price,
                    $booking->dp_amount,
                    $booking->total_dibayar,
                    $booking->sisa_pelunasan,
                    $biayaP,
                    $breakdown['melati'],
                    $breakdown['henna'],
                    $breakdown['lainnya'],
                    $booking->gateway_fee,
                    $bersih,
                    $booking->status,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportLaporanPdf(Request $request)
    {
        $bookings = $this->buildLaporanQuery($request)->latest('tanggal_acara')->get();

        $totalHargaBooking = 0;
        $totalDiterima = 0;
        $totalBiayaPihakLain = 0;
        $totalBiayaMelati = 0;
        $totalBiayaHenna = 0;
        $totalGatewayFee = 0;
        $totalBersihOwner = 0;

        foreach ($bookings as $booking) {
            $breakdown = $booking->pihak_lain_breakdown;
            $booking->biaya_pihak_lain = $breakdown['total'];
            $booking->biaya_melati = $breakdown['melati'];
            $booking->biaya_henna = $breakdown['henna'];
            $booking->biaya_lainnya = $breakdown['lainnya'];
            $booking->bersih_owner = $booking->total_dibayar > 0
                ? max(0, $booking->total_dibayar - $breakdown['total'] - $booking->gateway_fee)
                : max(0, $booking->dp_amount - $breakdown['total']);

            $totalHargaBooking += $booking->total_price;
            $totalDiterima += $booking->total_dibayar;
            $totalBiayaPihakLain += $breakdown['total'];
            $totalBiayaMelati += $breakdown['melati'];
            $totalBiayaHenna += $breakdown['henna'];
            $totalGatewayFee += $booking->gateway_fee;
            $totalBersihOwner += $booking->bersih_owner;
        }

        // Build period label
        $periodLabel = 'Semua Periode';
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $bulanNames = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
            $periodLabel = ($bulanNames[(int)$request->bulan] ?? '') . ' ' . $request->tahun;
        } elseif ($request->filled('tahun')) {
            $periodLabel = 'Tahun ' . $request->tahun;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('owner.laporan-pdf', compact(
            'bookings',
            'totalHargaBooking',
            'totalDiterima',
            'totalBiayaPihakLain',
            'totalBiayaMelati',
            'totalBiayaHenna',
            'totalGatewayFee',
            'totalBersihOwner',
            'periodLabel'
        ))->setPaper('a4', 'landscape');

        $fileLabel = '';
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $fileLabel = '_' . $request->tahun . '-' . str_pad($request->bulan, 2, '0', STR_PAD_LEFT);
        } elseif ($request->filled('tahun')) {
            $fileLabel = '_' . $request->tahun;
        }

        return $pdf->download('Laporan_Keuangan' . $fileLabel . '_' . date('Y-m-d') . '.pdf');
    }
}
