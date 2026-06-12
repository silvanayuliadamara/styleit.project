@extends('layouts.admin', ['title' => 'Dashboard Admin — LYB'])

@section('admin_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Dashboard Admin</h2>
            <p>Selamat datang, {{ Auth::user()->name }}. Berikut ringkasan transaksi hari ini.</p>
        </div>
        <span class="lyb-admin-date">{{ now()->translatedFormat('l, d F Y') }}</span>
    </header>

    {{-- Stat Cards --}}
    <section class="lyb-admin-section">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon total">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Total Booking</span>
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
            <h3>Booking Terbaru</h3>
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
                            <th>Paket</th>
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
                                <td class="text-end">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="lyb-admin-action-btn">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center lyb-empty-row">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada booking masuk.</p>
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
