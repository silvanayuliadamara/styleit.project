@extends('layouts.app')

@section('title', 'Invoice INV-' . $booking->booking_code . ' — LYB')

@section('content')
<div class="invoice-wrap">

    <div class="invoice-topnav">
        @php
            if (request()->routeIs('owner.*'))       $backUrl = route('owner.bookings.index');
            elseif (request()->routeIs('admin.*'))   $backUrl = route('admin.bookings.index');
            else                                     $backUrl = route('customer.bookings.index');
        @endphp
        <a href="{{ $backUrl }}" class="invoice-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button class="btn-cetak" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Invoice
        </button>
    </div>

    <div class="invoice-card">
        <div class="invoice-inner">

            <div class="invoice-header">
                <div class="invoice-brand-area">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="invoice-brand-logo">
                    <div>
                        <p class="invoice-brand-name">LISA YULI BELTI</p>
                        <p class="invoice-brand-sub">WEDDING GALLERY DAN MAKEUP ARTIST</p>
                    </div>
                </div>
                <div class="invoice-number-area">
                    <p class="invoice-label">INVOICE</p>
                    <p class="invoice-code">INV-{{ $booking->booking_code }}</p>
                    <p class="invoice-booking-ref">Booking: {{ $booking->booking_code }}</p>
                </div>
            </div>

            <div class="invoice-body">

                <div class="invoice-meta-row">
                    <div>
                        <p class="invoice-section-label">Ditagihkan Kepada</p>
                        <p class="invoice-customer-name">{{ ($booking->user ?? null)->name ?? '-' }}</p>
                        @if(($booking->user ?? null) && ($booking->user->phone ?? null))
                            <p class="invoice-customer-detail">{{ $booking->user->phone }}</p>
                        @endif
                        @if(($booking->user ?? null) && ($booking->user->instagram ?? null))
                            <p class="invoice-customer-detail">IG: {{ '@' . $booking->user->instagram }}</p>
                        @endif
                        @if(($booking->user ?? null) && ($booking->user->address ?? null))
                            <p class="invoice-customer-detail">{{ $booking->user->address }}</p>
                        @endif
                    </div>

                    <div class="invoice-status-col">
                        <p class="invoice-section-label">Status</p>
                        @php
                            $badgeClass = 'sbadge-pending';
                            $badgeLabel = 'Menunggu Pembayaran';

                            if (in_array($booking->status ?? '', ['dibatalkan', 'expired'])) {
                                $badgeClass = 'sbadge-dibatalkan';
                                $badgeLabel = 'Dibatalkan';
                            } elseif (($booking->payment_status ?? '') === 'lunas') {
                                $badgeClass = 'sbadge-lunas';
                                $badgeLabel = 'Lunas';
                            } elseif (($booking->payment_status ?? '') === 'dp_diterima') {
                                $badgeClass = 'sbadge-dp-diterima';
                                $badgeLabel = 'DP Diterima';
                            } elseif (($booking->payment_status ?? '') === 'dp_diupload') {
                                $badgeClass = 'sbadge-menunggu-dp';
                                $badgeLabel = 'Menunggu Konfirmasi DP';
                            } elseif (($booking->payment_status ?? '') === 'belum_bayar') {
                                $badgeClass = 'sbadge-menunggu-dp';
                                $badgeLabel = 'Menunggu DP';
                            }
                        @endphp
                        <span class="invoice-status-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        <p class="invoice-booking-date">
                            Tanggal Booking:
                            <strong>{{ ($booking->created_at ?? null) ? $booking->created_at->translatedFormat('l, d F Y') : '-' }}</strong>
                        </p>
                    </div>
                </div>

                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <p class="invoice-item-name">{{ $booking->package->name ?? 'Paket Layanan' }}</p>
                                @if($booking->tanggal_acara ?? null)
                                    <p class="invoice-item-desc">Tgl Acara: {{ $booking->tanggal_acara->translatedFormat('d M Y') }}</p>
                                @endif
                                @if($booking->slot_waktu ?? null)
                                    <p class="invoice-item-desc">Slot Waktu: {{ ucfirst($booking->slot_waktu) }}</p>
                                @endif
                                @if($booking->softlens ?? null)
                                    <p class="invoice-item-desc">Softlens: Ya</p>
                                @endif
                                @if($booking->notes ?? null)
                                    <p class="invoice-item-desc">{{ $booking->notes }}</p>
                                @endif
                            </td>
                            <td>Rp{{ number_format(($booking->subtotal ?? null) ?? ($booking->total_price ?? 0), 0, ',', '.') }}</td>
                        </tr>

                        @foreach(($booking->addons ?? []) as $addon)
                        <tr>
                            <td>
                                <p class="invoice-item-name">
                                    {{ ($addon->pivot ?? null)->nama_addon ?? $addon->name }}
                                    @if((($addon->pivot ?? null)->qty ?? 1) > 1)({{ $addon->pivot->qty }}x)@endif
                                </p>
                                @if(($addon->pivot ?? null)->nama_option ?? null)
                                    <p class="invoice-item-desc">Opsi: {{ $addon->pivot->nama_option }}</p>
                                @endif
                            </td>
                            <td>Rp{{ number_format(($addon->pivot ?? null)->subtotal ?? ($addon->pivot ?? null)->price ?? $addon->price ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


                <div class="invoice-summary">
                    <div class="invoice-summary-row">
                        <span>Total Harga Layanan</span>
                        <span>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="invoice-summary-row">
                        <span>DP</span>
                        <span>Rp{{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="invoice-summary-row">
                        <span>Total Pembayaran Diterima</span>
                        <span @if(($booking->total_dibayar ?? 0) == 0) style="color:#c62828;" @endif>
                            Rp{{ number_format($booking->total_dibayar ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="invoice-summary-row">
                        <span>Sisa Pelunasan</span>
                        <span>
                            @if(in_array($booking->status ?? '', ['dibatalkan', 'expired']))
                                Rp0
                            @else
                                Rp{{ number_format($booking->sisa_pelunasan ?? ($booking->total_price - ($booking->total_dibayar ?? 0)), 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                </div>

                @if(($booking->payment_status === 'belum_bayar') && !in_array($booking->status ?? '', ['dibatalkan', 'expired']))
                <div class="invoice-notice d-flex justify-content-between align-items-center flex-wrap gap-2" style="background-color: #fffbeb; border: 1px solid #f9edd0; border-radius: 12px; padding: 1rem; color: #8a6d3b; font-family: Arial, sans-serif; font-size: 13px;">
                    <span>Batas pembayaran DP: <strong>1 jam</strong> sejak invoice dibuat.</span>
                    @if(!request()->routeIs('owner.*') && !request()->routeIs('admin.*'))
                        <a href="{{ route('customer.payment.instruction', $booking->booking_code) }}" class="btn btn-sm btn-dark" style="background-color: #211313; border: none; border-radius: 8px; font-weight: 600; padding: 6px 16px; color: #efe2d5; text-decoration: none;">
                            Bayar DP Sekarang <i class="bi bi-arrow-right"></i>
                        </a>
                    @endif
                </div>
                @endif

            </div>


            <div class="invoice-footer-biz">
                <p><strong>LISA YULI BELTI</strong> — Wedding Gallery dan Makeup Artist</p>
                <p>Jl. Lintas Raya Padang-Bukittinggi, Pasar Lubuk Alung, Kab. Padang Pariaman, Sumatera Barat</p>
                <p>
                    WA Makeup: <a href="https://wa.me/6281227545591">+62 812-2754-5591</a>
                    &nbsp;•&nbsp;
                    WA Gallery: <a href="https://wa.me/6283112269289">+62 831-1226-9289</a>
                </p>
                <em>Terima kasih telah mempercayakan momen spesial Anda kepada kami.</em>
            </div>

        </div>
    </div>

</div>
@endsection