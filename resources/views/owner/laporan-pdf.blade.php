<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan — Lisa Yuli Belti MUA</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            padding: 20px 0 15px;
            border-bottom: 3px solid #b08a42;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            font-weight: 700;
            color: #211313;
            margin-bottom: 4px;
        }
        .header .subtitle {
            font-size: 11px;
            color: #8a7a72;
        }
        .header .period {
            font-size: 12px;
            font-weight: 700;
            color: #b08a42;
            margin-top: 6px;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .summary-item {
            display: table-cell;
            width: 20%;
            padding: 8px 6px;
            text-align: center;
            border: 1px solid #eadfd6;
            background: #fffcf8;
        }
        .summary-item .label {
            font-size: 8px;
            text-transform: uppercase;
            color: #8a7a72;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .summary-item .value {
            font-size: 11px;
            font-weight: 700;
            color: #211313;
        }
        .summary-item.highlight {
            background: #fffdf5;
            border-color: #b08a42;
        }
        .summary-item.highlight .value {
            color: #1a7a42;
            font-size: 12px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th {
            background: #211313;
            color: #fff;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 6px;
            text-align: left;
            border: 1px solid #211313;
        }
        table.data th.text-end {
            text-align: right;
        }
        table.data td {
            padding: 6px;
            border: 1px solid #eadfd6;
            font-size: 9px;
            vertical-align: top;
        }
        table.data tr:nth-child(even) {
            background: #faf8f5;
        }
        .text-end { text-align: right; }
        .text-green { color: #1a7a42; font-weight: 700; }
        .text-red { color: #a03131; }
        .text-gold { color: #896414; }
        .fw-bold { font-weight: 700; }
        .footer {
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #eadfd6;
            font-size: 9px;
            color: #8a7a72;
        }
        .pihak-detail {
            font-size: 7px;
            color: #8a7a72;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan — Lisa Yuli Belti MUA</h1>
        <div class="subtitle">Rekapitulasi transaksi booking (hanya transaksi aktif/masuk)</div>
        <div class="period">Periode: {{ $periodLabel }}</div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="label">Total Harga Booking</div>
            <div class="value">Rp{{ number_format($totalHargaBooking, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Diterima</div>
            <div class="value">Rp{{ number_format($totalDiterima, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Biaya Pihak Lain</div>
            <div class="value text-red">Rp{{ number_format($totalBiayaPihakLain, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Gateway Fee</div>
            <div class="value text-gold">Rp{{ number_format($totalGatewayFee, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item highlight">
            <div class="label">Estimasi Bersih Owner</div>
            <div class="value">Rp{{ number_format($totalBersihOwner, 0, ',', '.') }}</div>
        </div>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Customer</th>
                <th>Paket</th>
                <th>Tanggal</th>
                <th>Harga</th>
                <th>Dibayar</th>
                <th>Biaya Pihak Lain</th>
                <th>Gateway</th>
                <th class="text-end">Bersih Owner</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $index => $booking)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="fw-bold">{{ $booking->booking_code }}</td>
                    <td>{{ $booking->user->name ?? '-' }}</td>
                    <td>{{ $booking->package->name ?? '-' }}</td>
                    <td>{{ $booking->tanggal_acara ? $booking->tanggal_acara->format('d/m/Y') : '-' }}</td>
                    <td>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td class="text-green">Rp{{ number_format($booking->total_dibayar, 0, ',', '.') }}</td>
                    <td class="text-red">
                        Rp{{ number_format($booking->biaya_pihak_lain ?? 0, 0, ',', '.') }}
                        @if(($booking->biaya_melati ?? 0) > 0 || ($booking->biaya_henna ?? 0) > 0)
                            <div class="pihak-detail">
                                @if(($booking->biaya_melati ?? 0) > 0) Melati: Rp{{ number_format($booking->biaya_melati, 0, ',', '.') }} @endif
                                @if(($booking->biaya_henna ?? 0) > 0) Henna: Rp{{ number_format($booking->biaya_henna, 0, ',', '.') }} @endif
                            </div>
                        @endif
                    </td>
                    <td class="text-gold">Rp{{ number_format($booking->gateway_fee ?? 0, 0, ',', '.') }}</td>
                    <td class="text-end text-green">Rp{{ number_format($booking->bersih_owner ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px; color: #8a7a72;">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB — Lisa Yuli Belti MUA &copy; {{ date('Y') }}
    </div>
</body>
</html>
