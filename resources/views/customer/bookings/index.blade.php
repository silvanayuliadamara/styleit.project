@extends('layouts.app', ['title' => 'Booking Saya'])

@section('content')
<section class="page-hero compact"><div class="container"><p class="hero-label">BOOKING SAYA</p><h1>Riwayat Booking</h1><p>Lihat status booking dan pembayaran kamu.</p></div></section>
<section class="section-padding">
    <div class="container">
        <div class="glass-card">
            <div class="table-responsive">
                <table class="table align-middle customer-table">
                    <thead><tr><th>Kode</th><th>Paket</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Pembayaran</th><th></th></tr></thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td><strong>{{ $booking->booking_code }}</strong></td>
                                <td>{{ $booking->package->name }}<br><small>{{ $booking->package->category->name }}</small></td>
                                <td>{{ $booking->booking_date->format('d M Y') }}</td>
                                <td>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td><span class="status-badge {{ $booking->status }}">{{ str_replace('_', ' ', $booking->status) }}</span></td>
                                <td><span class="status-badge payment">{{ str_replace('_', ' ', $booking->payment_status) }}</span></td>
                                <td><a href="{{ route('customer.bookings.show', $booking->booking_code) }}" class="btn btn-sm btn-outline-dark rounded-pill">Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-5">Belum ada booking.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
