@extends('layouts.admin', ['title' => 'Daftar Booking — LYB'])

@section('admin_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Daftar Booking</h2>
            <p>Kelola semua transaksi booking yang masuk.</p>
        </div>
    </header>

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
                <table class="table lyb-admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Booking</th>
                            <th>Customer</th>
                            <th>Paket</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td>
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
                                <td colspan="8" class="text-center lyb-empty-row">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada booking masuk.</p>
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
