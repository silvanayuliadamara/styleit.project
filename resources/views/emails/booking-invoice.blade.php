<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Booking {{ $booking->booking_code }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f7f5f2;
            color: #333333;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            border: 1px solid #eadfd6;
        }
        .header {
            background-color: #211313;
            color: #efe2d5;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 22px;
            letter-spacing: 2px;
            font-weight: 700;
        }
        .header p {
            margin: 0;
            font-size: 11px;
            letter-spacing: 1px;
            color: #b08a42;
        }
        .content {
            padding: 30px;
        }
        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            color: #211313;
            margin-bottom: 20px;
            border-bottom: 2px solid #b08a42;
            padding-bottom: 8px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 14px;
        }
        .meta-label {
            font-weight: bold;
            color: #6f5a4c;
            width: 120px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fff9e6;
            color: #b08a42;
        }
        .status-dp-confirmed {
            background-color: #e6f7ff;
            color: #1890ff;
        }
        .status-lunas {
            background-color: #f6ffed;
            color: #52c41a;
        }
        .status-dibatalkan {
            background-color: #fff1f0;
            color: #ff4d4f;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .item-table th {
            background-color: #fbf8f1;
            border-bottom: 2px solid #eadfd6;
            text-align: left;
            padding: 10px;
            font-size: 14px;
            color: #6f5a4c;
        }
        .item-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #efe2d5;
            font-size: 14px;
            vertical-align: top;
        }
        .item-name {
            font-weight: bold;
            color: #211313;
            margin: 0 0 4px 0;
        }
        .item-desc {
            font-size: 12px;
            color: #777777;
            margin: 0;
        }
        .summary-container {
            width: 100%;
            margin-bottom: 30px;
        }
        .summary-row {
            text-align: right;
            padding: 4px 0;
            font-size: 14px;
        }
        .summary-label {
            display: inline-block;
            width: 200px;
            color: #6f5a4c;
        }
        .summary-value {
            display: inline-block;
            width: 120px;
            font-weight: bold;
            color: #211313;
        }
        .summary-total {
            border-top: 1px solid #eadfd6;
            margin-top: 8px;
            padding-top: 8px;
            font-size: 16px;
        }
        .summary-total .summary-value {
            color: #b08a42;
        }
        .action-button-container {
            text-align: center;
            margin: 30px 0;
        }
        .action-button {
            background-color: #211313;
            color: #efe2d5 !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 15px;
            display: inline-block;
            transition: background-color 0.2s;
        }
        .notice-box {
            background-color: #fffbeb;
            border: 1px solid #f9edd0;
            border-radius: 8px;
            padding: 15px;
            color: #8a6d3b;
            font-size: 13px;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        .footer {
            background-color: #fbf8f1;
            border-top: 1px solid #eadfd6;
            padding: 25px;
            text-align: center;
            font-size: 12px;
            color: #777777;
            line-height: 1.6;
        }
        .footer a {
            color: #b08a42;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="email-container">
    <div class="header">
        <h1>LISA YULI BELTI</h1>
        <p>WEDDING GALLERY & MAKEUP ARTIST</p>
    </div>

    <div class="content">
        <div class="invoice-title">
            INVOICE #{{ $booking->booking_code }}
        </div>

        <table class="meta-table">
            <tr>
                <td class="meta-label">Ditagihkan Kepada:</td>
                <td>
                    <strong>{{ $booking->user->name }}</strong><br>
                    {{ $booking->user->phone }}<br>
                    @if($booking->user->instagram)
                        IG: {{ '@' . ltrim($booking->user->instagram, '@') }}<br>
                    @endif
                    @if($booking->user->address)
                        {{ $booking->user->address }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="meta-label">Tanggal Booking:</td>
                <td>{{ $booking->created_at ? $booking->created_at->translatedFormat('d F Y H:i') : '-' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Status Pembayaran:</td>
                <td>
                    @php
                        $badgeClass = 'status-pending';
                        $badgeLabel = 'Menunggu Pembayaran DP';

                        if ($booking->payment_status === 'lunas') {
                            $badgeClass = 'status-lunas';
                            $badgeLabel = 'Lunas';
                        } elseif ($booking->payment_status === 'dp_diterima') {
                            $badgeClass = 'status-dp-confirmed';
                            $badgeLabel = 'DP Diterima';
                        } elseif (in_array($booking->status, ['dibatalkan', 'expired'])) {
                            $badgeClass = 'status-dibatalkan';
                            $badgeLabel = 'Dibatalkan';
                        }
                    @endphp
                    <span class="status-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                </td>
            </tr>
        </table>

        <table class="item-table">
            <thead>
                <tr>
                    <th>Item Layanan</th>
                    <th style="text-align: right; width: 120px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <p class="item-name">{{ $booking->package->name ?? 'Paket Layanan' }}</p>
                        @if($booking->tanggal_acara)
                            <p class="item-desc">Hari 1: {{ $booking->tanggal_acara->translatedFormat('d M Y') }} ({{ ucfirst($booking->slot_waktu) }})</p>
                        @endif
                        @if($booking->tanggal_acara_2)
                            <p class="item-desc">Hari 2: {{ \Carbon\Carbon::parse($booking->tanggal_acara_2)->translatedFormat('d M Y') }} ({{ ucfirst($booking->slot_waktu_2) }})</p>
                        @endif
                        @if($booking->tanggal_acara_3)
                            <p class="item-desc">Hari 3: {{ \Carbon\Carbon::parse($booking->tanggal_acara_3)->translatedFormat('d M Y') }} ({{ ucfirst($booking->slot_waktu_3) }})</p>
                        @endif
                        @if($booking->softlens)
                            <p class="item-desc">Softlens: Ya</p>
                        @endif
                        @if($booking->notes)
                            @php
                                $cleanNotes = preg_replace('/Tanggal Acara (Kedua|Ketiga): \d{4}-\d{2}-\d{2}/', '', $booking->notes);
                                $cleanNotes = preg_replace('/Slot Hari (2|3): (pagi|siang)/', '', $cleanNotes);
                                $cleanNotes = trim(preg_replace('/\s+/', ' ', $cleanNotes));
                            @endphp
                            @if(!empty($cleanNotes))
                                <p class="item-desc">Catatan: {{ $cleanNotes }}</p>
                            @endif
                        @endif
                    </td>
                    <td style="text-align: right;">Rp{{ number_format($booking->subtotal ?? $booking->total_price, 0, ',', '.') }}</td>
                </tr>
                @foreach(($booking->addons ?? []) as $addon)
                <tr>
                    <td>
                        <p class="item-name">
                            {{ ($addon->pivot ?? null)->nama_addon ?? $addon->name }}
                            @if((($addon->pivot ?? null)->qty ?? 1) > 1)({{ $addon->pivot->qty }}x)@endif
                        </p>
                        @if(($addon->pivot ?? null)->nama_option ?? null)
                            <p class="item-desc">Opsi: {{ $addon->pivot->nama_option }}</p>
                        @endif
                    </td>
                    <td style="text-align: right;">Rp{{ number_format(($addon->pivot ?? null)->subtotal ?? ($addon->pivot ?? null)->price ?? $addon->price ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary-container">
            <div class="summary-row">
                <span class="summary-label">Total Harga Layanan:</span>
                <span class="summary-value">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Down Payment (DP):</span>
                <span class="summary-value">Rp{{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total Dibayar:</span>
                <span class="summary-value" @if(($booking->total_dibayar ?? 0) == 0) style="color:#c62828;" @endif>
                    Rp{{ number_format($booking->total_dibayar ?? 0, 0, ',', '.') }}
                </span>
            </div>
            <div class="summary-row summary-total">
                <span class="summary-label" style="font-weight: bold;">Sisa Pelunasan:</span>
                <span class="summary-value">
                    @if(in_array($booking->status, ['dibatalkan', 'expired']))
                        Rp0
                    @else
                        Rp{{ number_format($booking->sisa_pelunasan ?? ($booking->total_price - ($booking->total_dibayar ?? 0)), 0, ',', '.') }}
                    @endif
                </span>
            </div>
        </div>

        @if($booking->payment_status === 'belum_bayar' && !in_array($booking->status, ['dibatalkan', 'expired']))
        <div class="notice-box">
            <strong>Batas Pembayaran DP:</strong> 1 jam sejak invoice dibuat. Silakan bayar DP untuk mengonfirmasi jadwal booking Anda.
        </div>
        @endif

        <div class="action-button-container">
            <a href="{{ route('customer.bookings.invoice', [$booking->booking_code, 'source' => 'email']) }}" class="action-button">
                Lihat Invoice Lengkap
            </a>
        </div>
    </div>

    <div class="footer">
        <p><strong>Lisa Yuli Belti — Wedding Gallery & MUA</strong></p>
        <p>Jl. Lintas Raya Padang-Bukittinggi, Pasar Lubuk Alung, Kab. Padang Pariaman, Sumatera Barat</p>
        <p>
            WA Makeup: <a href="https://wa.me/6281227545591">+62 812-2754-5591</a> | 
            WA Gallery: <a href="https://wa.me/6283112269289">+62 831-1226-9289</a>
        </p>
        <p style="font-size: 11px; margin-top: 15px; color: #999999;">
            Email ini dikirimkan secara otomatis oleh sistem StyleIt / Lisa Yuli Belti. Harap jangan membalas email ini secara langsung.
        </p>
    </div>
</div>

</body>
</html>
