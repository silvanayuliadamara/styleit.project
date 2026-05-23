@extends('layouts.app', ['title' => 'Dashboard Customer'])

@section('content')
<section class="page-hero compact">
    <div class="container">
        <p class="hero-label">CUSTOMER AREA</p>
        <h1>Halo, {{ Auth::check() ? Auth::user()->name : 'Customer Preview' }}</h1>
        <p>Kelola booking, cek status pembayaran, dan lanjut pilih layanan LYB.</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-md-4"><div class="stat-card"><span>Booking Aktif</span><strong>{{ $activeBookingCount }}</strong></div></div>
            <div class="col-md-4"><div class="stat-card"><span>Item Keranjang</span><strong>{{ count(session('cart', [])) }}</strong></div></div>
            <div class="col-md-4"><div class="stat-card"><span>Status Akun</span><strong>Aktif</strong></div></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Booking Terbaru</h3>
                        <a href="{{ route('customer.bookings.index') }}" class="link-gold">Lihat semua</a>
                    </div>
                    @forelse($bookings as $booking)
                        <div class="booking-list-item">
                            <div>
                                <strong>{{ $booking->booking_code }}</strong>
                                <p>{{ $booking->package->name }} · {{ $booking->booking_date->format('d M Y') }}</p>
                            </div>
                            <span class="status-badge {{ $booking->status }}">{{ str_replace('_', ' ', $booking->status) }}</span>
                        </div>
                    @empty
                        <p class="muted">Belum ada booking. Yuk pilih layanan dulu.</p>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-4">
                <div class="booking-panel">
                    <h3>Mulai Booking</h3>
                    <p>Pilih paket makeup atau baju, tentukan tanggal, lalu checkout dengan DP.</p>
                    <a href="{{ route('layanan.index') }}" class="btn-dark-custom w-100 text-center">Lihat Layanan</a>
                    <a href="{{ route('customer.cart.index') }}" class="btn-outline-custom w-100 text-center mt-3">Buka Keranjang</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
