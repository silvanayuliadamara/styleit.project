@extends('layouts.admin', ['title' => 'Daftar Booking Baju — LYB'])

@section('admin_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Daftar Booking Baju</h2>
            <p>Kelola semua transaksi booking baju yang masuk.</p>
        </div>
        <div style="min-width: 260px;">
            <form action="{{ route('admin.bookings.index') }}" method="GET" class="position-relative">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control ps-4 pe-5 py-2 rounded-pill shadow-sm" placeholder="Cari kode/nama..." style="border: 1px solid #eadfd6; font-size: 13px; background: #fffcf8;">
                <button type="submit" class="position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent pe-3 text-muted">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </header>

    {{-- Filter Quick Actions --}}
    <div class="d-flex gap-2 mb-3 flex-wrap align-items-center">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm rounded-pill px-3 {{ !request('payment_status') ? 'btn-dark' : 'btn-outline-dark' }}" style="font-size: 12px; font-weight: 600;">
            Semua
        </a>
        <a href="{{ route('admin.bookings.index', ['payment_status' => 'belum_bayar']) }}" class="btn btn-sm rounded-pill px-3 {{ request('payment_status') === 'belum_bayar' ? 'btn-dark' : 'btn-outline-dark' }}" style="font-size: 12px; font-weight: 600;">
            Menunggu DP
        </a>
        <a href="{{ route('admin.bookings.index', ['payment_status' => 'dp_diupload']) }}" class="btn btn-sm rounded-pill px-3 {{ request('payment_status') === 'dp_diupload' ? 'btn-dark' : 'btn-outline-dark' }}" style="font-size: 12px; font-weight: 600;">
            Perlu Konfirmasi
        </a>
        <a href="{{ route('admin.bookings.index', ['payment_status' => 'dp_diterima']) }}" class="btn btn-sm rounded-pill px-3 {{ request('payment_status') === 'dp_diterima' ? 'btn-dark' : 'btn-outline-dark' }}" style="font-size: 12px; font-weight: 600;">
            DP Dibayar
        </a>
        <a href="{{ route('admin.bookings.index', ['payment_status' => 'lunas']) }}" class="btn btn-sm rounded-pill px-3 {{ request('payment_status') === 'lunas' ? 'btn-dark' : 'btn-outline-dark' }}" style="font-size: 12px; font-weight: 600;">
            Lunas
        </a>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabel Semua Booking --}}
    <section class="lyb-admin-section">
        <div class="lyb-admin-table-card">
            <div class="table-responsive">
                <table class="table lyb-admin-table align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>KODE</th>
                            <th>CUSTOMER</th>
                            <th>PAKET</th>
                            <th>TANGGAL</th>
                            <th>TOTAL</th>
                            <th>DP</th>
                            <th>DIBAYAR</th>
                            <th>SISA</th>
                            <th>STATUS</th>
                            <th class="text-end">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td><strong>{{ $booking->booking_code }}</strong></td>
                                <td>
                                    <div class="fw-semibold">{{ $booking->user->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="lyb-package-name">
                                        <i class="bi bi-bag-heart"></i>
                                        {{ $booking->package->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-nowrap">{{ $booking->tanggal_acara ? $booking->tanggal_acara->translatedFormat('D, d M Y') : ($booking->booking_date ? $booking->booking_date->translatedFormat('D, d M Y') : '-') }}</td>
                                <td>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($booking->dp_amount, 0, ',', '.') }}</td>
                                <td class="text-nowrap" style="color: #2d6e25; font-weight: 600;">Rp{{ number_format($booking->total_dibayar, 0, ',', '.') }}</td>
                                <td class="text-nowrap" style="color: #a03131; font-weight: 600;">Rp{{ number_format($booking->sisa_pelunasan, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $sClass = 'pending';
                                        $sText = strtoupper($booking->payment_status);
                                        if ($booking->payment_status == 'lunas') {
                                            $sClass = 'selesai'; $sText = 'Lunas';
                                        } elseif ($booking->payment_status == 'dp_diterima') {
                                            $sClass = 'aktif'; $sText = 'DP Dibayar';
                                        } elseif ($booking->payment_status == 'dp_diupload') {
                                            $sClass = 'pending'; $sText = 'Perlu Konfirmasi';
                                        } elseif ($booking->payment_status == 'belum_bayar') {
                                            $sClass = 'pending'; $sText = 'Menunggu DP';
                                        }
                                        if ($booking->status == 'dibatalkan') {
                                            $sClass = 'ditolak'; $sText = 'Dibatalkan';
                                        } elseif ($booking->status == 'expired') {
                                            $sClass = 'ditolak'; $sText = 'Expired';
                                        }
                                    @endphp
                                    <span class="lyb-admin-status {{ $sClass }}">
                                        {{ $sText }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.bookings.invoice', $booking) }}" class="lyb-admin-action-btn" style="background-color: #fbf8f1; border: 1px solid #eadfd6; color: #5c430e;">
                                            <i class="bi bi-receipt"></i> Invoice
                                        </a>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="lyb-admin-action-btn">
                                            <i class="bi bi-eye-fill"></i> Lihat
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center lyb-empty-row">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada booking baju masuk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($bookings->hasPages())
                <div class="lyb-admin-pagination">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
