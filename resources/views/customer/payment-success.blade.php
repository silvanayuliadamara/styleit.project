@extends('layouts.app', ['title' => 'Pembayaran Berhasil'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment-success.css') }}">
@endpush

@section('content')
<section class="payment-success-section">
    <div class="container d-flex flex-column align-items-center">
        <div class="w-100 mb-3 text-start" style="max-width: 520px;">
            <a href="{{ route('customer.dashboard') }}" class="text-decoration-none d-inline-flex align-items-center gap-2 back-link" style="font-size: 14px; font-weight: 500; color: #9c9587;">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
        <div class="success-card">
            {{-- Green Header with Check Icon --}}
            <div class="success-header">
                <div class="success-icon-ring">
                    <div class="success-icon-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                </div>
                <h2>DP Berhasil Dibayar</h2>
                <p>Pembayaran Anda telah kami terima</p>
            </div>

            {{-- Detail Body --}}
            <div class="success-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="detail-label">Kode Booking</span>
                        <span class="detail-value">{{ $booking->booking_code }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Metode</span>
                        <span class="detail-value">
                            <span class="method-badge">{{ $payment->metode_pembayaran }}</span>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nominal DP</span>
                        <span class="detail-value amount">Rp{{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tanggal Bayar</span>
                        <span class="detail-value">{{ $payment->updated_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>

                <hr class="success-divider">

                <div class="success-actions">
                    <a href="{{ route('customer.bookings.invoice', $booking->booking_code) }}" class="btn-invoice" id="btnInvoice">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        Lihat Invoice
                    </a>
                    <a href="{{ route('customer.dashboard') }}" class="btn-dashboard" id="btnDashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Ke Dashboard Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
