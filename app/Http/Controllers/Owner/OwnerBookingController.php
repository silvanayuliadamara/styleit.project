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
            $query->where('status', $request->status);
        }
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

        return view('owner.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'package', 'schedule', 'addons', 'payments', 'latestCancellationRequest']);

        return view('owner.bookings.show', compact('booking'));
    }

    public function invoice(Booking $booking)
    {
        $booking->load(['user', 'package', 'schedule', 'addons', 'payments']);

        return view('shared.invoice', compact('booking'));
    }

    /**
     * Setujui DP yang diupload customer.
     */
    public function confirmDp(Request $request, Booking $booking)
    {
        DB::transaction(function () use ($booking) {
            // Update status pembayaran pending di tabel payments menjadi approved
            $pendingPayment = $booking->payments()->where('status', 'pending')->first();
            if ($pendingPayment) {
                $pendingPayment->update([
                    'status' => 'approved',
                    'paid_at' => now(),
                ]);
            }

            // Update booking
            $booking->update([
                'status' => 'diterima',
                'payment_status' => 'dp_diterima',
                'total_dibayar' => $booking->dp_amount,
                'sisa_pelunasan' => $booking->total_price - $booking->dp_amount,
                'status_layanan' => 'terjadwal',
            ]);

            // Jika ada schedule, increment jumlah terpakai
            if ($booking->schedule_id) {
                $schedule = Schedule::find($booking->schedule_id);
                if ($schedule) {
                    $schedule->incrementTerpakai();
                }
            }
        });

        return redirect()->route('owner.bookings.show', $booking->id)
            ->with('success', 'Pembayaran DP berhasil dikonfirmasi dan jadwal telah diperbarui.');
    }

    /**
     * Konfirmasi pelunasan booking.
     */
    public function confirmLunas(Booking $booking)
    {
        DB::transaction(function () use ($booking) {
            // Cari sisa pelunasan
            $sisa = $booking->total_price - $booking->total_dibayar;

            // Catat pembayaran pelunasan
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $sisa,
                'status' => 'approved',
                'paid_at' => now(),
                'tipe_pembayaran' => 'pelunasan',
            ]);

            // Update booking ke lunas
            $booking->update([
                'payment_status' => 'lunas',
                'total_dibayar' => $booking->total_price,
                'sisa_pelunasan' => 0,
            ]);
        });

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
            }
            // Sebaliknya, jika status berubah dari pending/batal ke diterima, increment terpakai
            elseif ($oldStatus !== 'diterima' && $newStatus === 'diterima') {
                if ($booking->schedule_id) {
                    $schedule = Schedule::find($booking->schedule_id);
                    if ($schedule) {
                        $schedule->incrementTerpakai();
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
                $cancelReq->update([
                    'status_persetujuan' => 'disetujui',
                    'approved_by' => auth()->id(),
                    'customer_dibaca' => false, // trigger notification to customer
                ]);

                // Decrement schedule if status was diterima
                if ($booking->status === 'diterima' && $booking->schedule_id) {
                    $schedule = Schedule::find($booking->schedule_id);
                    if ($schedule) {
                        $schedule->decrementTerpakai();
                    }
                }

                $booking->update([
                    'status' => 'dibatalkan',
                    'status_layanan' => 'dibatalkan',
                ]);
            } else {
                $cancelReq->update([
                    'status_persetujuan' => 'ditolak',
                    'approved_by' => auth()->id(),
                    'customer_dibaca' => false, // trigger notification to customer
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
        $bookings = Booking::with(['user', 'package.items', 'addons'])
            ->whereNotIn('status', ['ditolak', 'dibatalkan'])
            ->get();

        $totalHargaBooking = 0;
        $totalDiterima = 0;
        $totalBiayaPihakLain = 0;
        $totalGatewayFee = 0;
        $totalBersihOwner = 0;

        foreach ($bookings as $booking) {
            // Biaya Pihak Lain
            $biayaP = 0;
            if ($booking->package) {
                $biayaP += $booking->package->items->where('is_pihak_lain', true)->sum('biaya_pihak_lain');
            }
            $biayaP += $booking->addons->sum('pivot.biaya_pihak_lain');
            $booking->biaya_pihak_lain = $biayaP;

            // Gateway Fee
            $booking->gateway_fee = $booking->gateway_fee;

            // Bersih Owner
            if (in_array($booking->status, ['expired', 'dibatalkan', 'ditolak'])) {
                $booking->bersih_owner = 0;
            } else {
                $booking->bersih_owner = $booking->total_dibayar > 0
                    ? max(0, $booking->total_dibayar - $biayaP - $booking->gateway_fee)
                    : max(0, $booking->dp_amount - $biayaP);
            }

            $totalHargaBooking += $booking->total_price;
            $totalDiterima += $booking->total_dibayar;
            $totalBiayaPihakLain += $biayaP;
            $totalGatewayFee += $booking->gateway_fee;
            $totalBersihOwner += $booking->bersih_owner;
        }

        return view('owner.laporan', compact(
            'bookings',
            'totalHargaBooking',
            'totalDiterima',
            'totalBiayaPihakLain',
            'totalGatewayFee',
            'totalBersihOwner'
        ));
    }

    public function exportLaporanCsv()
    {
        $bookings = Booking::with(['user', 'package.items', 'addons'])
            ->whereNotIn('status', ['ditolak', 'dibatalkan'])
            ->get();

        $fileName = 'Laporan_Keuangan_'.date('Y-m-d').'.csv';

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
            'Biaya Pihak Lain', 'Gateway Fee', 'Bersih Owner', 'Status',
        ];

        $callback = function () use ($bookings, $columns) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, $columns, ';');

            foreach ($bookings as $booking) {
                $biayaP = 0;
                if ($booking->package) {
                    $biayaP += $booking->package->items->where('is_pihak_lain', true)->sum('biaya_pihak_lain');
                }
                $biayaP += $booking->addons->sum('pivot.biaya_pihak_lain');

                if (in_array($booking->status, ['expired', 'dibatalkan', 'ditolak'])) {
                    $bersih = 0;
                } else {
                    $bersih = $booking->total_dibayar > 0
                        ? max(0, $booking->total_dibayar - $biayaP - $booking->gateway_fee)
                        : max(0, $booking->dp_amount - $biayaP);
                }

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
                    $booking->gateway_fee,
                    $bersih,
                    $booking->status,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
