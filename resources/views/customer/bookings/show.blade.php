@extends('layouts.customer', ['title' => 'Detail Booking ' . $booking->booking_code])

@section('customer_content')
    <div class="mb-3">
        <a href="{{ route('customer.dashboard') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; border-color: #eadfd6; color: #211313;">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <header class="lyb-admin-page-header">
        <div>
            <h2>Detail Booking #{{ $booking->booking_code }}</h2>
            <p>Tanggal Acara: {{ ($booking->tanggal_acara ?? null) ? $booking->tanggal_acara->translatedFormat('l, d F Y') : (($booking->booking_date ?? null) ? $booking->booking_date->translatedFormat('l, d F Y') : '-') }}</p>
        </div>
        <div>
            <span class="lyb-admin-status {{ $booking->status ?? '' }} fs-6 px-3 py-2">
                {{ strtoupper(str_replace('_', ' ', $booking->status ?? '')) }}
            </span>
        </div>
    </header>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="glass-card mb-4">
                <h3>Informasi Paket</h3>
                <div class="booking-list-item">
                    <div>
                        <strong>{{ $booking->package->name ?? '-' }}</strong>
                        <p>{{ $booking->package->category->name ?? '-' }} · Softlens: {{ ($booking->softlens ?? false) ? 'Ya' : 'Tidak' }}</p>
                    </div>
                    <strong>Rp{{ number_format($booking->subtotal ?? 0, 0, ',', '.') }}</strong>
                </div>
                @if(($booking->addons ?? null) && $booking->addons->count())
                    <h4 class="mt-4">Add-on</h4>
                    @foreach($booking->addons as $addon)
                        <div class="booking-list-item">
                            <span>{{ ($addon->pivot ?? null)->nama_addon ?? $addon->name }}</span>
                            <strong>Rp{{ number_format(($addon->pivot ?? null)->subtotal ?? ($addon->pivot ?? null)->price ?? $addon->price ?? 0, 0, ',', '.') }}</strong>
                        </div>
                    @endforeach
                @endif
                @if($booking->notes ?? null)
                    <h4 class="mt-4">Catatan</h4>
                    <p>{{ $booking->notes }}</p>
                @endif
            </div>

            <div class="glass-card">
                <h3>Bukti Pembayaran</h3>
                @forelse($booking->payments ?? [] as $payment)
                    <div class="booking-list-item">
                        <div>
                            <strong>DP Rp{{ number_format($payment->amount ?? 0, 0, ',', '.') }}</strong>
                            <p>Status: {{ $payment->status ?? '' }} · {{ isset($payment->paid_at) && $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}</p>
                        </div>
                        @if($payment->proof_image ?? null)
                            <a href="{{ asset('storage/'.$payment->proof_image) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill">Lihat Bukti</a>
                        @endif
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
                    <div><span>Status booking</span><strong>{{ str_replace('_', ' ', $booking->status ?? '') }}</strong></div>
                    <div><span>Status bayar</span><strong>{{ str_replace('_', ' ', $booking->payment_status ?? '') }}</strong></div>
                    <div><span>Total layanan</span><strong>Rp{{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</strong></div>
                    <div><span>DP</span><strong>Rp{{ number_format($booking->dp_amount ?? 0, 0, ',', '.') }}</strong></div>
                    <div><span>Sisa</span><strong>Rp{{ number_format(($booking->remaining_payment ?? null) ?? (($booking->total_price ?? 0) - ($booking->dp_amount ?? 0)), 0, ',', '.') }}</strong></div>
                </div>
                <a href="https://wa.me/6281227545591?text=Halo%20admin%20LYB,%20saya%20mau%20tanya%20booking%20{{ $booking->booking_code ?? '' }}" target="_blank" class="btn-whatsapp w-100 mt-3">
                    <i class="bi bi-whatsapp"></i> Tanya Admin
                </a>
            </div>
        </div>
    </div>
@endsection
