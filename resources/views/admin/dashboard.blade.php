@extends('layouts.admin', ['title' => 'Dashboard Admin — LYB'])

@section('admin_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Admin</h2>
            <p>Selamat datang, {{ Auth::user()->name }}. Berikut ringkasan booking baju hari ini.</p>
        </div>
        <span class="lyb-admin-date">{{ now()->translatedFormat('l, d F Y') }}</span>
    </header>

    {{-- Pengajuan Pembatalan Alert Banner --}}
    @if ($pendingCancellations->isNotEmpty())
        <div class="alert alert-danger border-danger rounded-4 shadow-sm p-4 mb-4" style="background-color: #fdf2f2;">
            <h5 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Ada {{ $pendingCancellations->count() }} Pengajuan Pembatalan Menunggu Persetujuan</h5>
            <p class="text-secondary small mb-3">Customer telah mengajukan pembatalan booking baju. Persetujuan pembatalan akan diproses oleh Owner, namun Anda dapat memantau detailnya di sini:</p>
            <div class="list-group rounded-3 shadow-sm overflow-hidden border">
                @foreach ($pendingCancellations as $pb)
                    <a href="{{ route('admin.bookings.show', $pb->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 border-bottom py-3" style="background: #fff;">
                        <div>
                            <strong class="text-dark">{{ $pb->booking_code }}</strong> — <span class="text-secondary">{{ $pb->user->name ?? '-' }}</span>
                            <small class="text-muted d-block mt-1">Paket: {{ $pb->package->name ?? '-' }} | Acara: {{ $pb->tanggal_acara ? $pb->tanggal_acara->translatedFormat('d M Y') : ($pb->booking_date ? $pb->booking_date->translatedFormat('d M Y') : '-') }}</small>
                        </div>
                        <span class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3" style="font-size: 12px;">
                            Lihat Detail <i class="bi bi-arrow-right-short"></i>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Stat Cards --}}
    <section class="lyb-admin-section">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon total">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Total Booking Baju</span>
                        <strong>{{ $totalBooking }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon pending">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Menunggu Konfirmasi</span>
                        <strong>{{ $bookingPending }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon aktif">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Sedang Berjalan</span>
                        <strong>{{ $bookingAktif }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon selesai">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Selesai</span>
                        <strong>{{ $bookingSelesai }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tabel Booking Terbaru --}}
    <section class="lyb-admin-section">
        <div class="lyb-admin-section-head">
            <h3>Booking Baju Terbaru</h3>
            <a href="{{ route('admin.bookings.index') }}" class="lyb-admin-link-all">
                Lihat semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="lyb-admin-table-card">
            <div class="table-responsive">
                <table class="table lyb-admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Customer</th>
                            <th>Paket Baju</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestBookings as $booking)
                            <tr>
                                <td><strong>{{ $booking->booking_code }}</strong></td>
                                <td>{{ $booking->user->name ?? '-' }}</td>
                                <td>
                                    <span class="lyb-package-name">
                                        <i class="bi bi-bag-heart"></i>
                                        {{ $booking->package->name ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $booking->booking_date->translatedFormat('d M Y') }}</td>
                                <td>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="lyb-admin-status {{ $booking->status }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.bookings.invoice', $booking) }}" class="lyb-admin-action-btn" style="background-color: #fbf8f1; border: 1px solid #eadfd6; color: #5c430e;">
                                            <i class="bi bi-receipt"></i> Invoice
                                        </a>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="lyb-admin-action-btn">
                                            Lihat
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center lyb-empty-row">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada booking baju masuk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- WhatsApp Support --}}
    <section class="lyb-admin-section">
        <h3>WhatsApp Support</h3>
        <div class="lyb-whatsapp-card">
            <div class="lyb-whatsapp-left">
                <div class="lyb-whatsapp-icon"><i class="bi bi-whatsapp"></i></div>
                <div>
                    <strong>+62 831-1226-9289</strong>
                    <p class="mb-0">Hubungi customer langsung via WhatsApp</p>
                </div>
            </div>
            <a href="https://wa.me/6283112269289" target="_blank" class="lyb-whatsapp-button">
                Buka WhatsApp
            </a>
        </div>
    </section>
@endsection
