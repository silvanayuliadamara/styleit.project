@extends('layouts.customer', ['title' => 'Detail Booking ' . $booking->booking_code])

@push('styles')
<style>
    /* ===== Booking Detail Page Styles ===== */
    .booking-detail-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        color: #6f625c;
        font-size: 13.5px;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 10px;
        border: 1px solid #eadfd6;
        background: #fffcf8;
        transition: all 0.25s ease;
    }
    .booking-detail-back:hover {
        background: #f5efe8;
        color: #211313;
        border-color: #d8c29e;
    }

    .booking-detail-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }
    .booking-detail-header h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #211313;
        margin: 0;
        font-family: Georgia, serif !important;
    }
    .booking-detail-header .booking-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 6px;
        color: #8a7a72;
        font-size: 13px;
    }
    .booking-detail-header .booking-meta i {
        color: #b08a42;
        font-size: 14px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .status-badge.selesai {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }
    .status-badge.diterima {
        background: #e3f2fd;
        color: #1565c0;
        border: 1px solid #bbdefb;
    }
    .status-badge.pending, .status-badge.menunggu_konfirmasi {
        background: #fff8e1;
        color: #f57f17;
        border: 1px solid #ffecb3;
    }
    .status-badge.ditolak, .status-badge.dibatalkan {
        background: #fce4ec;
        color: #c62828;
        border: 1px solid #f8bbd0;
    }

    /* Section Cards */
    .detail-card {
        background: #fff;
        border: 1px solid #eadfd6;
        border-radius: 20px;
        padding: 24px 28px;
        margin-bottom: 20px;
    }
    .detail-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 700;
        color: #211313;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f0ebe5;
    }
    .detail-card-title i {
        color: #b08a42;
        font-size: 18px;
    }

    /* Package Info Row */
    .package-info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
    }
    .package-info-row .package-name {
        font-size: 15px;
        font-weight: 700;
        color: #211313;
        margin-bottom: 4px;
    }
    .package-info-row .package-detail {
        font-size: 13px;
        color: #8a7a72;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .package-info-row .package-detail .dot {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: #ccc;
        display: inline-block;
    }
    .package-info-row .package-price {
        font-size: 15px;
        font-weight: 700;
        color: #211313;
        white-space: nowrap;
        font-family: Georgia, serif !important;
    }

    /* Addon Row */
    .addon-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed #f0ebe5;
    }
    .addon-info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .addon-info-row .addon-name {
        font-size: 13.5px;
        color: #6f625c;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .addon-info-row .addon-name i {
        color: #b08a42;
        font-size: 12px;
    }
    .addon-info-row .addon-price {
        font-size: 13.5px;
        font-weight: 600;
        color: #211313;
    }

    /* Catatan */
    .notes-box {
        background: #fdfaf6;
        border: 1px solid #f0ebe5;
        border-radius: 12px;
        padding: 14px 16px;
        margin-top: 16px;
        font-size: 13px;
        color: #6f625c;
        line-height: 1.6;
    }
    .notes-box strong {
        color: #211313;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 4px;
    }

    /* Payment Timeline */
    .payment-timeline {
        position: relative;
        padding-left: 0;
    }
    .payment-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 14px 0;
        border-bottom: 1px solid #f0ebe5;
    }
    .payment-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .payment-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
    }
    .payment-icon.dp {
        background: #fef3e0;
        color: #e6a817;
    }
    .payment-icon.pelunasan {
        background: #e8f5e9;
        color: #43a047;
    }
    .payment-info {
        flex: 1;
        min-width: 0;
    }
    .payment-info .payment-type {
        font-size: 13.5px;
        font-weight: 700;
        color: #211313;
        margin-bottom: 2px;
    }
    .payment-info .payment-amount {
        font-size: 15px;
        font-weight: 700;
        color: #211313;
        font-family: Georgia, serif !important;
    }
    .payment-info .payment-meta {
        font-size: 12px;
        color: #8a7a72;
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .payment-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .payment-status-dot.diterima { background: #43a047; }
    .payment-status-dot.pending { background: #ffa726; }
    .payment-status-dot.ditolak { background: #e53935; }
    .payment-action {
        align-self: center;
    }

    /* Summary Panel */
    .summary-panel {
        background: #fff;
        border: 1px solid #eadfd6;
        border-radius: 20px;
        padding: 24px 28px;
        position: sticky;
        top: 24px;
    }
    .summary-panel-title {
        font-size: 16px;
        font-weight: 700;
        color: #211313;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .summary-panel-title i {
        color: #b08a42;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f5f0eb;
        font-size: 13.5px;
    }
    .summary-row:last-child {
        border-bottom: none;
    }
    .summary-row .summary-label {
        color: #8a7a72;
    }
    .summary-row .summary-value {
        font-weight: 600;
        color: #211313;
        text-align: right;
    }
    .summary-row .summary-value.lunas {
        color: #2e7d32;
    }
    .summary-row .summary-value.selesai {
        color: #2e7d32;
    }
    .summary-row.total-row {
        background: #fdfaf6;
        margin: 12px -28px 0;
        padding: 16px 28px;
        border-bottom: none;
        border-radius: 0 0 20px 20px;
        font-size: 15px;
    }
    .summary-row.total-row .summary-label {
        font-weight: 600;
        color: #211313;
    }
    .summary-row.total-row .summary-value {
        font-family: Georgia, serif !important;
        font-size: 16px;
        font-weight: 700;
    }

    .btn-wa-detail {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 13px 20px;
        border-radius: 14px;
        background: #25d366;
        color: #fff;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        margin-top: 16px;
        transition: all 0.25s ease;
        border: none;
    }
    .btn-wa-detail:hover {
        background: #1fb658;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.3);
    }

    /* Review Section */
    .review-card {
        background: #fff;
        border: 1px solid #eadfd6;
        border-radius: 20px;
        padding: 24px 28px;
        margin-top: 20px;
    }
    .review-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 700;
        color: #211313;
        margin-bottom: 10px;
    }
    .review-card-title i {
        font-size: 18px;
    }
    .review-subtitle {
        font-size: 13px;
        color: #8a7a72;
        line-height: 1.5;
        margin-bottom: 18px;
    }

    .review-existing {
        background: #fdfaf6;
        border: 1px solid #f0ebe5;
        border-radius: 14px;
        padding: 18px;
        margin-top: 8px;
    }
    .review-stars-display {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #b08a42;
        font-size: 16px;
        margin-bottom: 10px;
    }
    .review-stars-display .rating-text {
        margin-left: 8px;
        font-size: 13px;
        color: #8a7a72;
        font-weight: 500;
    }
    .review-existing-comment {
        font-family: Georgia, serif !important;
        font-style: italic;
        color: #211313;
        font-size: 14px;
        line-height: 1.6;
        margin: 10px 0;
    }
    .review-existing-meta {
        font-size: 11.5px;
        color: #8a7a72;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Star Rating Input */
    .star-input-group {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 4px;
    }
    .star-input-group input { display: none; }
    .star-input-group label {
        font-size: 28px;
        color: #eadfd6;
        cursor: pointer;
        transition: color 0.2s ease, transform 0.15s ease;
    }
    .star-input-group label:hover { transform: scale(1.18); }
    .star-input-group label:hover,
    .star-input-group label:hover ~ label,
    .star-input-group input:checked ~ label {
        color: #b08a42;
    }

    .btn-submit-review {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px 20px;
        border-radius: 50px;
        background: #211313;
        color: #fff;
        font-weight: 700;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .btn-submit-review:hover {
        background: #3a2222;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(33, 19, 19, 0.15);
    }

    .empty-payment {
        text-align: center;
        padding: 20px 16px;
        color: #8a7a72;
        font-size: 13px;
    }
    .empty-payment i {
        font-size: 28px;
        color: #ddd;
        display: block;
        margin-bottom: 8px;
    }
</style>
@endpush

@section('customer_content')
    {{-- Back Navigation --}}
    <div class="mb-4">
        <a href="{{ route('customer.dashboard') }}" class="booking-detail-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    {{-- Header --}}
    <div class="booking-detail-header">
        <div>
            <h2>Booking #{{ $booking->booking_code }}</h2>
            <div class="booking-meta">
                <i class="bi bi-calendar3"></i>
                <span>{{ ($booking->tanggal_acara ?? null) ? $booking->tanggal_acara->translatedFormat('l, d F Y') : (($booking->booking_date ?? null) ? $booking->booking_date->translatedFormat('l, d F Y') : '-') }}</span>
            </div>
        </div>
        <span class="status-badge {{ $booking->status ?? '' }}">
            <i class="bi bi-{{ ($booking->status ?? '') === 'selesai' ? 'check-circle-fill' : (in_array($booking->status ?? '', ['ditolak', 'dibatalkan']) ? 'x-circle-fill' : 'clock-fill') }}"></i>
            {{ strtoupper(str_replace('_', ' ', $booking->status ?? '')) }}
        </span>
    </div>

    <div class="row g-4">
        {{-- Left Column --}}
        <div class="col-lg-8">
            {{-- Package Info --}}
            <div class="detail-card">
                <div class="detail-card-title">
                    <i class="bi bi-box-seam"></i> Informasi Paket
                </div>
                <div class="package-info-row">
                    <div>
                        <div class="package-name">{{ $booking->package->name ?? '-' }}</div>
                        <div class="package-detail">
                            <span>{{ $booking->package->category->name ?? '-' }}</span>
                            @if(strtolower($booking->package->category->slug ?? '') !== 'baju')
                                <span class="dot"></span>
                                <span>Softlens: {{ ($booking->softlens ?? false) ? 'Ya' : 'Tidak' }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="package-price">Rp{{ number_format($booking->subtotal ?? 0, 0, ',', '.') }}</div>
                </div>

                {{-- Add-ons --}}
                @if(($booking->addons ?? null) && $booking->addons->count())
                    <div style="margin-top: 18px; padding-top: 16px; border-top: 1px solid #f0ebe5;">
                        <div style="font-size: 13px; font-weight: 700; color: #8a7a72; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 10px;">
                            <i class="bi bi-plus-circle" style="color: #b08a42;"></i> Add-on
                        </div>
                        @foreach($booking->addons as $addon)
                            <div class="addon-info-row">
                                <span class="addon-name">
                                    <i class="bi bi-check2"></i>
                                    {{ ($addon->pivot ?? null)->nama_addon ?? $addon->name }}
                                </span>
                                <span class="addon-price">Rp{{ number_format(($addon->pivot ?? null)->subtotal ?? ($addon->pivot ?? null)->price ?? $addon->price ?? 0, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Notes --}}
                @if($booking->notes ?? null)
                    <div class="notes-box">
                        <strong><i class="bi bi-chat-left-text me-1"></i> Catatan</strong>
                        {!! nl2br(e($booking->notes)) !!}
                    </div>
                @endif
            </div>

            {{-- Payment Section --}}
            <div class="detail-card">
                <div class="detail-card-title">
                    <i class="bi bi-credit-card-2-back"></i> Riwayat Pembayaran
                </div>
                @forelse($booking->payments ?? [] as $payment)
                    @php
                        $isPelunasan = ($payment->tipe_pembayaran ?? 'dp') === 'pelunasan';
                    @endphp
                    <div class="payment-item">
                        <div class="payment-icon {{ $isPelunasan ? 'pelunasan' : 'dp' }}">
                            <i class="bi bi-{{ $isPelunasan ? 'check-circle' : 'wallet2' }}"></i>
                        </div>
                        <div class="payment-info">
                            <div class="payment-type">{{ $isPelunasan ? 'Pelunasan' : 'DP (Uang Muka)' }}</div>
                            <div class="payment-amount">Rp{{ number_format($payment->amount ?? 0, 0, ',', '.') }}</div>
                            <div class="payment-meta">
                                <span class="payment-status-dot {{ $payment->status ?? 'pending' }}"></span>
                                <span>{{ ucfirst($payment->status ?? '') }}</span>
                                <span>·</span>
                                <span>{{ isset($payment->paid_at) && $payment->paid_at ? $payment->paid_at->format('d M Y, H:i') : '-' }}</span>
                            </div>
                        </div>
                        @if($payment->proof_image ?? null)
                            <div class="payment-action">
                                <a href="{{ asset('storage/'.$payment->proof_image) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3" style="font-size: 12px; font-weight: 600;">
                                    <i class="bi bi-image me-1"></i> Bukti
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="empty-payment">
                        <i class="bi bi-receipt"></i>
                        Belum ada data pembayaran.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            {{-- Summary --}}
            <div class="summary-panel">
                <div class="summary-panel-title">
                    <i class="bi bi-receipt-cutoff"></i> Ringkasan
                </div>
                <div class="summary-row">
                    <span class="summary-label">Status Booking</span>
                    <span class="summary-value {{ $booking->status ?? '' }}">{{ ucwords(str_replace('_', ' ', $booking->status ?? '')) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Status Bayar</span>
                    <span class="summary-value {{ $booking->payment_status ?? '' }}">{{ ucwords(str_replace('_', ' ', $booking->payment_status ?? '')) }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">DP</span>
                    <span class="summary-value">Rp{{ number_format($booking->dp_amount ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Sisa Pelunasan</span>
                    <span class="summary-value">Rp{{ number_format(($booking->sisa_pelunasan ?? null) ?? (($booking->total_price ?? 0) - ($booking->dp_amount ?? 0)), 0, ',', '.') }}</span>
                </div>
                <div class="summary-row total-row">
                    <span class="summary-label">Total Layanan</span>
                    <span class="summary-value">Rp{{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- WhatsApp --}}
            <a href="https://wa.me/6281227545591?text=Halo%20admin%20LYB,%20saya%20mau%20tanya%20booking%20{{ $booking->booking_code ?? '' }}" target="_blank" class="btn-wa-detail">
                <i class="bi bi-whatsapp"></i> Tanya Admin via WhatsApp
            </a>

            {{-- Review Section --}}
            @if(($booking->payment_status ?? '') === 'lunas' && ($booking->status ?? '') === 'selesai')
                <div class="review-card" id="review-section">
                    @if($booking->review)
                        {{-- Tampilan Review yang Sudah Diberikan --}}
                        <div class="review-card-title">
                            <i class="bi bi-star-fill" style="color: #b08a42;"></i> Ulasan Anda
                        </div>
                        <div class="review-existing">
                            <div class="review-stars-display">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $booking->review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                                <span class="rating-text">{{ $booking->review->rating }}/5</span>
                            </div>
                            @if($booking->review->komentar)
                                <p class="review-existing-comment">"{{ $booking->review->komentar }}"</p>
                            @endif
                            <div class="review-existing-meta">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                Dikirim {{ $booking->review->created_at->translatedFormat('d M Y, H:i') }}
                            </div>
                        </div>
                    @else
                        {{-- Form Beri Review --}}
                        <div class="review-card-title">
                            <i class="bi bi-chat-heart" style="color: #b08a42;"></i> Beri Penilaian
                        </div>
                        <p class="review-subtitle">
                            Bagaimana pengalaman Anda? Ulasan Anda sangat berarti bagi kami untuk terus meningkatkan layanan.
                        </p>
                        <form action="{{ route('customer.bookings.review', $booking->booking_code) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small mb-2" style="color: #211313; font-size: 13px;">Rating <span class="text-danger">*</span></label>
                                <div class="star-input-group" id="starRatingInput">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                        <label for="star{{ $i }}" title="{{ $i }} bintang"><i class="bi bi-star-fill"></i></label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small mb-1" style="color: #211313; font-size: 13px;">Komentar <span class="text-muted fw-normal">(opsional)</span></label>
                                <textarea name="komentar" class="form-control" rows="3" maxlength="1000" placeholder="Ceritakan pengalaman Anda..." style="border-color: #eadfd6; font-size: 13px; border-radius: 14px; padding: 14px; resize: none;">{{ old('komentar') }}</textarea>
                            </div>
                            <button type="submit" class="btn-submit-review">
                                <i class="bi bi-send"></i> Kirim Ulasan
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
