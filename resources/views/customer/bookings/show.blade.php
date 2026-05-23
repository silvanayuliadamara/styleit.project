@extends('layouts.app', ['title' => 'Detail Booking ' . $booking->booking_code])

@section('content')
<section class="page-hero compact"><div class="container"><a href="{{ route('customer.bookings.index') }}" class="link-gold"><i class="bi bi-arrow-left"></i> Kembali</a><p class="hero-label mt-3">DETAIL BOOKING</p><h1>{{ $booking->booking_code }}</h1><p>{{ $booking->package->name }} · {{ $booking->booking_date->format('d M Y') }}</p></div></section>
<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-card mb-4">
                    <h3>Informasi Paket</h3>
                    <div class="booking-list-item"><div><strong>{{ $booking->package->name }}</strong><p>{{ $booking->package->category->name }} · Softlens: {{ $booking->softlens ? 'Ya' : 'Tidak' }}</p></div><strong>Rp{{ number_format($booking->subtotal, 0, ',', '.') }}</strong></div>
                    @if($booking->addons->count())
                        <h4 class="mt-4">Add-on</h4>
                        @foreach($booking->addons as $addon)
                            <div class="booking-list-item"><span>{{ $addon->name }}</span><strong>Rp{{ number_format($addon->pivot->price, 0, ',', '.') }}</strong></div>
                        @endforeach
                    @endif
                    @if($booking->notes)<h4 class="mt-4">Catatan</h4><p>{{ $booking->notes }}</p>@endif
                </div>

                <div class="glass-card">
                    <h3>Bukti Pembayaran</h3>
                    @forelse($booking->payments as $payment)
                        <div class="booking-list-item">
                            <div><strong>DP Rp{{ number_format($payment->amount, 0, ',', '.') }}</strong><p>Status: {{ $payment->status }} · {{ optional($payment->paid_at)->format('d M Y H:i') }}</p></div>
                            @if($payment->proof_image)<a href="{{ asset('storage/'.$payment->proof_image) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill">Lihat Bukti</a>@endif
                        </div>
                    @empty
                        <p class="muted">Belum ada bukti pembayaran. Hubungi admin jika ingin upload bukti manual.</p>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-4">
                <div class="booking-panel">
                    <h3>Ringkasan</h3>
                    <div class="total-box">
                        <div><span>Status booking</span><strong>{{ str_replace('_', ' ', $booking->status) }}</strong></div>
                        <div><span>Status bayar</span><strong>{{ str_replace('_', ' ', $booking->payment_status) }}</strong></div>
                        <div><span>Total layanan</span><strong>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</strong></div>
                        <div><span>DP</span><strong>Rp{{ number_format($booking->dp_amount, 0, ',', '.') }}</strong></div>
                        <div><span>Sisa</span><strong>Rp{{ number_format($booking->remaining_payment, 0, ',', '.') }}</strong></div>
                    </div>
                    <a href="https://wa.me/6281227545591?text=Halo%20admin%20LYB,%20saya%20mau%20tanya%20booking%20{{ $booking->booking_code }}" target="_blank" class="btn-whatsapp w-100 mt-3"><i class="bi bi-whatsapp"></i> Tanya Admin</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
