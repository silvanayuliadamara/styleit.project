@extends('layouts.owner', ['title' => 'Dashboard Owner — LYB'])

@section('owner_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Dashboard Overview</h2>
            <p>Selamat datang kembali, {{ Auth::user()->name ?? 'Owner MUA' }}.</p>
        </div>
        <span class="lyb-admin-date">{{ now()->translatedFormat('l, d F Y') }}</span>
    </header>

    {{-- Pengajuan Pembatalan Alert Banner --}}
    @if ($pendingCancellations->isNotEmpty())
        <div class="alert alert-danger border-danger rounded-4 shadow-sm p-4 mb-4" style="background-color: #fdf2f2;">
            <h5 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Ada {{ $pendingCancellations->count() }} Pengajuan Pembatalan Menunggu Persetujuan</h5>
            <p class="text-secondary small mb-3">Customer telah mengajukan pembatalan booking. Silakan periksa rincian rekening pengembalian dana dan konfirmasi persetujuan Anda:</p>
            <div class="list-group rounded-3 shadow-sm overflow-hidden border">
                @foreach ($pendingCancellations as $pb)
                    <a href="{{ route('owner.bookings.show', $pb->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 border-bottom py-3" style="background: #fff;">
                        <div>
                            <strong class="text-dark">{{ $pb->booking_code }}</strong> — <span class="text-secondary">{{ $pb->user->name ?? '-' }}</span>
                            <small class="text-muted d-block mt-1">Paket: {{ $pb->package->name ?? '-' }} | Acara: {{ $pb->tanggal_acara ? $pb->tanggal_acara->translatedFormat('d M Y') : ($pb->booking_date ? $pb->booking_date->translatedFormat('d M Y') : '-') }}</small>
                        </div>
                        <span class="btn btn-sm btn-danger fw-bold rounded-pill px-3" style="font-size: 12px; background-color: #dc3545; border: none;">
                            Tinjau Pengajuan <i class="bi bi-arrow-right-short"></i>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Financial & Operations Stat Cards (8 cards) --}}
    <section class="lyb-admin-section">
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon total">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Total Booking</span>
                        <strong>{{ $totalBookings }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon pending">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Menunggu DP</span>
                        <strong>{{ $bookingWaiting }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon aktif">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>DP Dibayar</span>
                        <strong>{{ $bookingDiterima }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon selesai">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Lunas</span>
                        <strong>{{ $bookingSelesai }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon harga">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Total Harga Booking</span>
                        <strong style="font-size: 13px;">Rp{{ number_format($totalOmset, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon pendapatan">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Pembayaran Diterima</span>
                        <strong style="font-size: 13px;">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon biaya">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Biaya Pihak Lain</span>
                        <strong style="font-size: 13px;">Rp{{ number_format($totalBiayaPihakLain, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon bersih">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Estimasi Bersih Owner</span>
                        <strong style="font-size: 13px; color: #1a7a42;">Rp{{ number_format($estimasiBersihOwner, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tabel Booking Terbaru --}}
    <section class="lyb-admin-section">
        <div class="lyb-admin-section-head">
            <h3>Upcoming Booking</h3>
            <a href="{{ route('owner.bookings.index') }}" class="lyb-admin-link-all">
                Lihat semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="lyb-admin-table-card">
            <div class="table-responsive">
                <table class="table lyb-admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>KODE</th>
                            <th>CUSTOMER</th>
                            <th>PAKET</th>
                            <th>TANGGAL</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestBookings as $booking)
                            <tr>
                                <td><strong>{{ $booking->booking_code }}</strong></td>
                                <td>{{ $booking->user->name ?? '-' }}</td>
                                <td>
                                    <span class="lyb-package-name">
                                        {{ $booking->package->name ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $booking->tanggal_acara ? $booking->tanggal_acara->translatedFormat('D, d M Y') : ($booking->booking_date ? $booking->booking_date->translatedFormat('D, d M Y') : '-') }}</td>
                                <td>
                                    @php
                                        $statusClass = 'pending';
                                        $statusText = strtoupper($booking->payment_status);
                                        if ($booking->payment_status == 'lunas') {
                                            $statusClass = 'selesai';
                                            $statusText = 'Lunas';
                                        } elseif ($booking->payment_status == 'dp_diterima') {
                                            $statusClass = 'aktif';
                                            $statusText = 'DP Dibayar';
                                        } elseif ($booking->payment_status == 'dp_diupload') {
                                            $statusClass = 'pending';
                                            $statusText = 'Menunggu Konfirmasi';
                                        } elseif ($booking->payment_status == 'belum_bayar') {
                                            $statusClass = 'pending';
                                            $statusText = 'Menunggu DP';
                                        }
                                        if ($booking->status == 'dibatalkan' || $booking->status == 'expired') {
                                            $statusClass = 'ditolak';
                                            $statusText = 'Dibatalkan';
                                        }
                                    @endphp
                                    <span class="lyb-admin-status {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center lyb-empty-row">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada data booking masuk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection