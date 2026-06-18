@extends('layouts.app', ['title' => 'Pembayaran Berhasil'])

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    body {
        background-color: #FAF7F2;
        font-family: 'Outfit', sans-serif;
    }
    .payment-success-section {
        padding: 5rem 0 7rem 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 70vh;
    }
    .success-card {
        max-width: 520px;
        width: 100%;
        background: #ffffff;
        border: 1px solid #eadfd6;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 12px 40px rgba(176, 138, 66, 0.06);
        text-align: center;
        animation: fadeInUp 0.6s ease-out;
    }

    /* Header hijau */
    .success-header {
        background: linear-gradient(135deg, #2E7D5B 0%, #3da47a 100%);
        padding: 2.5rem 2rem 2rem 2rem;
        position: relative;
    }
    .success-icon-ring {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem auto;
        animation: scaleIn 0.5s ease-out 0.2s both;
    }
    .success-icon-inner {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .success-icon-inner svg {
        width: 32px;
        height: 32px;
        color: #2E7D5B;
    }
    .success-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 0.25rem 0;
    }
    .success-header p {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.75);
        margin: 0;
    }

    /* Confetti dots decoration */
    .success-header::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            radial-gradient(circle at 10% 20%, rgba(255,255,255,0.1) 1px, transparent 1px),
            radial-gradient(circle at 80% 40%, rgba(255,255,255,0.08) 1.5px, transparent 1.5px),
            radial-gradient(circle at 30% 75%, rgba(255,255,255,0.1) 1px, transparent 1px),
            radial-gradient(circle at 90% 10%, rgba(255,255,255,0.12) 1px, transparent 1px),
            radial-gradient(circle at 60% 60%, rgba(255,255,255,0.06) 2px, transparent 2px);
        pointer-events: none;
    }

    /* Body detail */
    .success-body {
        padding: 2rem 2rem 2.5rem 2rem;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        text-align: left;
    }
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }
    .detail-item.full-width {
        grid-column: 1 / -1;
    }
    .detail-label {
        font-size: 0.78rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #9c9587;
        font-weight: 600;
    }
    .detail-value {
        font-size: 1rem;
        color: #211313;
        font-weight: 600;
    }
    .detail-value.amount {
        font-size: 1.35rem;
        font-weight: 700;
        color: #2E7D5B;
    }
    .detail-value .method-badge {
        display: inline-block;
        background-color: #f0ede6;
        border: 1px solid #eadfd6;
        color: #6b5b3e;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 3px 12px;
        border-radius: 20px;
    }

    /* Divider */
    .success-divider {
        border: none;
        border-top: 1px dashed #eadfd6;
        margin: 0 0 2rem 0;
    }

    /* Buttons */
    .success-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .btn-invoice {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background-color: #c39a53;
        color: #ffffff;
        border: none;
        border-radius: 14px;
        padding: 0.95rem;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        transition: all 0.25s ease;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-invoice:hover {
        background-color: #ae843c;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(195, 154, 83, 0.25);
    }
    .btn-invoice svg {
        width: 18px;
        height: 18px;
    }
    .btn-dashboard {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background-color: transparent;
        color: #b08a42;
        border: 1px solid #b08a42;
        border-radius: 14px;
        padding: 0.95rem;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        transition: all 0.25s ease;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-dashboard:hover {
        background-color: #fbf8f1;
        color: #8f6c2f;
        border-color: #8f6c2f;
        transform: translateY(-1px);
    }
    .btn-dashboard svg {
        width: 18px;
        height: 18px;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.5);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Responsive */
    @media (max-width: 576px) {
        .success-card {
            margin: 0 1rem;
            border-radius: 18px;
        }
        .success-header {
            padding: 2rem 1.5rem 1.5rem 1.5rem;
        }
        .success-body {
            padding: 1.5rem 1.5rem 2rem 1.5rem;
        }
        .detail-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .detail-value.amount {
            font-size: 1.2rem;
        }
    }
</style>

<section class="payment-success-section">
    <div class="container d-flex justify-content-center">
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
