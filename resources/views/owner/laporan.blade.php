@extends('layouts.owner', ['title' => 'Laporan Keuangan — LYB'])

@section('owner_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Laporan Keuangan</h2>
            <p>Rekapitulasi seluruh transaksi booking MUA dan perhitungan bersih owner.</p>
        </div>
        <a href="{{ route('owner.laporan.export') }}" class="lyb-admin-action-btn" style="padding: 10px 20px; font-size: 13px;">
            <i class="bi bi-download me-2"></i> Export CSV
        </a>
    </header>

    {{-- Summary Cards --}}
    <section class="lyb-admin-section">
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon harga">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Total Harga Booking</span>
                        <strong style="font-size:16px;">Rp{{ number_format($totalHargaBooking, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon pendapatan">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Total Diterima (Cash In)</span>
                        <strong style="font-size:16px;">Rp{{ number_format($totalDiterima, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon biaya">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Total Biaya Pihak Lain</span>
                        <strong style="font-size:16px;">Rp{{ number_format($totalBiayaPihakLain, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon biaya" style="background: #f8e8e8;">
                        <i class="bi bi-bank"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Total Biaya Gateway/Admin</span>
                        <strong style="font-size:16px;">Rp{{ number_format($totalGatewayFee, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="lyb-stat-card" style="border-color: #b08a42; background: #fffdf5;">
                    <div class="lyb-stat-icon bersih" style="width: 56px; height: 56px; font-size: 26px;">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span style="font-size: 13px; font-weight: 700; color: #b08a42;">Estimasi Bersih Owner</span>
                        <strong style="font-size: 22px; color: #1a7a42;">Rp{{ number_format($totalBersihOwner, 0, ',', '.') }}</strong>
                        <small class="text-muted" style="font-size: 11px;">Diterima − Biaya Pihak Lain − Fee Gateway</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Detailed Transaction Table --}}
    <section class="lyb-admin-section">
        <div class="lyb-admin-section-head">
            <h3>Detail Transaksi</h3>
        </div>

        <div class="lyb-admin-table-card">
            <div class="table-responsive">
                <table class="table lyb-admin-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>KODE</th>
                            <th>CUSTOMER</th>
                            <th>PAKET</th>
                            <th>TANGGAL</th>
                            <th>HARGA</th>
                            <th>DIBAYAR</th>
                            <th>BIAYA PIHAK LAIN</th>
                            <th>GATEWAY FEE</th>
                            <th class="text-end" style="color: #1a7a42;">BERSIH OWNER</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td><strong>{{ $booking->booking_code }}</strong></td>
                                <td>{{ $booking->user->name ?? '-' }}</td>
                                <td>
                                    <span class="lyb-package-name">{{ $booking->package->name ?? '-' }}</span>
                                </td>
                                <td class="text-nowrap">
                                    {{ $booking->tanggal_acara
                                        ? $booking->tanggal_acara->translatedFormat('d M Y')
                                        : ($booking->booking_date ? $booking->booking_date->translatedFormat('d M Y') : '-') }}
                                </td>
                                <td>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td style="color: #2d6e25; font-weight: 600;">
                                    Rp{{ number_format($booking->total_dibayar, 0, ',', '.') }}
                                </td>
                                <td style="color: #a03131;">
                                    Rp{{ number_format($booking->biaya_pihak_lain ?? 0, 0, ',', '.') }}
                                </td>
                                <td style="color: #896414;">
                                    Rp{{ number_format($booking->gateway_fee ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="text-end" style="color: #1a7a42; font-weight: 700;">
                                    Rp{{ number_format($booking->bersih_owner ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center lyb-empty-row">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada data transaksi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
