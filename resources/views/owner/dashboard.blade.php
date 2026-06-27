@extends('layouts.owner', ['title' => 'Dashboard Owner — LYB'])

@section('owner_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header lyb-animate-fade-up">
        <div>
            <h2>Dashboard</h2>
            <p>Selamat datang kembali, {{ Auth::user()->name ?? 'Owner MUA' }}.</p>
        </div>
        <span class="lyb-admin-date">{{ now()->translatedFormat('l, d F Y') }}</span>
    </header>

    {{-- Pengajuan Pembatalan Alert Banner --}}
    @if ($pendingCancellations->isNotEmpty())
        <div class="alert alert-danger alert-danger-pulse rounded-4 shadow-sm p-4 mb-4 lyb-animate-fade-up delay-1" style="background-color: #fdf2f2;">
            <h5 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Ada {{ $pendingCancellations->count() }} Pengajuan Pembatalan Menunggu Persetujuan</h5>
            <p class="text-secondary small mb-3">Customer telah mengajukan pembatalan booking. Silakan periksa rincian rekening pengembalian dana dan konfirmasi persetujuan Anda:</p>
            <div class="list-group rounded-3 shadow-sm overflow-hidden border">
                @foreach ($pendingCancellations as $pb)
                    <a href="{{ route('owner.bookings.show', $pb->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 border-bottom py-3" style="background: #fff;">
                        <div>
                            <strong class="text-dark">{{ $pb->booking_code }}</strong> — <span class="text-secondary">{{ $pb->user->name ?? '-' }}</span>
                            <small class="text-muted d-block mt-1">Paket: {{ $pb->package->name ?? '-' }} | Acara: {{ $pb->tanggal_acara ? $pb->tanggal_acara->translatedFormat('d M Y') : ($pb->booking_date ? $pb->booking_date->translatedFormat('d M Y') : '-') }}</small>
                        </div>
                        <span class="btn btn-sm btn-danger fw-bold rounded-pill px-3" style="font-size: 12px; background-color: #dc3545; border: none;">
                            Tinjau Pengajuan <i class="bi bi-arrow-right-short"></i>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Financial & Operations Stat Cards (8 cards) --}}
    <section class="lyb-admin-section">
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card lyb-animate-fade-up delay-1">
                    <div class="lyb-stat-icon total">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Total Booking</span>
                        <strong>{{ $totalBookings }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card lyb-animate-fade-up delay-2">
                    <div class="lyb-stat-icon pending">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Menunggu DP</span>
                        <strong>{{ $bookingWaiting }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card lyb-animate-fade-up delay-3">
                    <div class="lyb-stat-icon aktif">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>DP Dibayar</span>
                        <strong>{{ $bookingDiterima }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card lyb-animate-fade-up delay-4">
                    <div class="lyb-stat-icon selesai">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Lunas</span>
                        <strong>{{ $bookingSelesai }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card lyb-animate-fade-up delay-2">
                    <div class="lyb-stat-icon harga">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Total Harga Booking</span>
                        <strong style="font-size: 13px;">Rp{{ number_format($totalOmset, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card lyb-animate-fade-up delay-3">
                    <div class="lyb-stat-icon pendapatan">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Pembayaran Diterima</span>
                        <strong style="font-size: 13px;">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card lyb-animate-fade-up delay-4">
                    <div class="lyb-stat-icon biaya">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Biaya Pihak Lain</span>
                        <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                            <strong style="font-size: 13px; margin: 0 !important; line-height: 1;">Rp{{ number_format($totalBiayaPihakLain, 0, ',', '.') }}</strong>
                            <div class="lyb-pihak-lain-detail">
                                melati: Rp{{ number_format($totalBiayaMelati, 0, ',', '.') }}<br>
                                henna: Rp{{ number_format($totalBiayaHenna, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="lyb-stat-card lyb-animate-fade-up delay-5">
                    <div class="lyb-stat-icon bersih">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Estimasi Bersih Owner</span>
                        <strong style="font-size: 13px; color: #1a7a42;">Rp{{ number_format($estimasiBersihOwner, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Chart & Quick Actions Row --}}
    <section class="lyb-animate-fade-up delay-5 mb-4">
        <div class="row g-4">
            {{-- Left Column: Interactive Trend Chart --}}
            <div class="col-12 col-lg-8">
                <div class="lyb-admin-table-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <div>
                            <h3 class="playfair-text mb-1" style="font-size: 18px; font-weight: 600; color: var(--lyb-dark);">Analitik & Tren Keuangan</h3>
                            <p class="text-secondary small mb-0">Melacak volume booking dan total pendapatan bersih Anda secara bulanan.</p>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark border px-2 py-1.5 small" style="font-size: 11px; border-radius: 6px;"><i class="bi bi-calendar3 text-gold"></i> 6 Bulan Terakhir</span>
                        </div>
                    </div>
                    <div style="position: relative; height: 260px; width: 100%;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Right Column: Quick Info & Analytics Ring --}}
            <div class="col-12 col-lg-4">
                <div class="lyb-admin-table-card p-4 h-100 d-flex flex-column justify-content-between" style="min-height: 334px;">
                    <div>
                        <h3 class="playfair-text mb-3" style="font-size: 18px; font-weight: 600; color: var(--lyb-dark);">Aksi Cepat & Kinerja</h3>
                        
                        {{-- Booking Completion Rate Indicator --}}
                        <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-4" style="background: #faf7f2; border: 1px solid rgba(176, 138, 66, 0.1);">
                            @php
                                $completionRate = $totalBookings > 0 ? round(($bookingSelesai / $totalBookings) * 100) : 0;
                            @endphp
                            <div class="position-relative d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--lyb-gold) 0%, #ecd6be 100%); color: #fff; font-weight: 700; font-size: 13px; box-shadow: 0 4px 10px rgba(176, 138, 66, 0.15); flex-shrink: 0;">
                                {{ $completionRate }}%
                            </div>
                            <div>
                                <span class="d-block fw-bold text-dark" style="font-size: 13.5px;">Rasio Booking Lunas</span>
                                <span class="text-secondary small d-block" style="font-size: 11.5px; line-height: 1.3;">{{ $bookingSelesai }} dari {{ $totalBookings }} layanan selesai.</span>
                            </div>
                        </div>

                        {{-- Quick Link buttons --}}
                        <div class="d-flex flex-column gap-2 mb-3">
                            <a href="{{ route('owner.schedules.wedding') }}" class="btn btn-sm btn-outline-dark w-100 text-start py-2.5 px-3 rounded-3 d-flex align-items-center justify-content-between" style="font-size: 13px; font-weight: 500; transition: all 0.2s; border-color: #eadfd6;">
                                <span><i class="bi bi-calendar-range me-2" style="color: var(--lyb-gold);"></i> Atur Kalender & Blokir</span>
                                <i class="bi bi-arrow-right-short"></i>
                            </a>
                            <a href="{{ route('owner.laporan') }}" class="btn btn-sm btn-outline-dark w-100 text-start py-2.5 px-3 rounded-3 d-flex align-items-center justify-content-between" style="font-size: 13px; font-weight: 500; transition: all 0.2s; border-color: #eadfd6;">
                                <span><i class="bi bi-file-earmark-bar-graph me-2" style="color: var(--lyb-gold);"></i> Unduh Laporan (CSV)</span>
                                <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>

                    {{-- WhatsApp Status Summary --}}
                    <div class="pt-3 border-top" style="border-top-style: dashed !important; border-top-color: #eadfd6 !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background-color: #25d366; box-shadow: 0 0 8px rgba(37, 211, 102, 0.6);"></div>
                                <span class="small fw-semibold text-secondary">Notifikasi WhatsApp Aktif</span>
                            </div>
                            <a href="{{ route('owner.whatsapp.index') }}" class="fw-bold text-decoration-none small hover-underline" style="color: var(--lyb-gold);">Atur <i class="bi bi-gear-fill"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tabel Booking Terbaru --}}
    <section class="lyb-admin-section lyb-animate-fade-up delay-6">
        <div class="lyb-admin-section-head">
            <h3>Upcoming Booking</h3>
            <a href="{{ route('owner.bookings.index') }}" class="lyb-admin-link-all">
                Lihat semua <i class="bi bi-arrow-right"></i>
            </a>
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
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestBookings as $booking)
                            <tr>
                                <td><strong>{{ $booking->booking_code }}</strong></td>
                                <td>{{ $booking->user->name ?? '-' }}</td>
                                <td>
                                    <span class="lyb-package-name">
                                        {{ $booking->package->name ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $booking->tanggal_acara ? $booking->tanggal_acara->translatedFormat('D, d M Y') : ($booking->booking_date ? $booking->booking_date->translatedFormat('D, d M Y') : '-') }}</td>
                                <td>
                                    @php
                                        $statusClass = 'pending';
                                        $statusText = strtoupper($booking->payment_status);
                                        if ($booking->payment_status == 'lunas') {
                                            $statusClass = 'selesai';
                                            $statusText = 'Lunas';
                                        } elseif ($booking->payment_status == 'dp_diterima') {
                                            $statusClass = 'aktif';
                                            $statusText = 'DP Dibayar';
                                        } elseif ($booking->payment_status == 'dp_diupload') {
                                            $statusClass = 'pending';
                                            $statusText = 'Menunggu Konfirmasi';
                                        } elseif ($booking->payment_status == 'belum_bayar') {
                                            $statusClass = 'pending';
                                            $statusText = 'Menunggu DP';
                                        }
                                        if ($booking->status == 'dibatalkan' || $booking->status == 'expired') {
                                            $statusClass = 'ditolak';
                                            $statusText = 'Dibatalkan';
                                        }
                                    @endphp
                                    <span class="lyb-admin-status {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center lyb-empty-row">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada data booking masuk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- ChartJS integration script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            // Premium Gradient setup for the Line Area chart
            const revenueGradient = ctx.createLinearGradient(0, 0, 0, 260);
            revenueGradient.addColorStop(0, 'rgba(176, 138, 66, 0.25)');
            revenueGradient.addColorStop(1, 'rgba(176, 138, 66, 0.00)');

            const bookingsGradient = ctx.createLinearGradient(0, 0, 0, 260);
            bookingsGradient.addColorStop(0, 'rgba(33, 19, 19, 0.15)');
            bookingsGradient.addColorStop(1, 'rgba(33, 19, 19, 0.00)');

            const labels = @json($monthLabels);
            const revenues = @json($monthlyRevenue);
            const bookings = @json($monthlyBookings);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Pendapatan Diterima (Rp)',
                            data: revenues,
                            borderColor: '#b08a42',
                            borderWidth: 3,
                            backgroundColor: revenueGradient,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#b08a42',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Jumlah Booking',
                            data: bookings,
                            borderColor: '#211313',
                            borderWidth: 2.5,
                            backgroundColor: bookingsGradient,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#211313',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            borderDash: [5, 5],
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                boxHeight: 12,
                                font: {
                                    family: "'Outfit', sans-serif",
                                    size: 11,
                                    weight: '500'
                                },
                                color: '#6f625c'
                            }
                        },
                        tooltip: {
                            backgroundColor: '#170b0b',
                            titleFont: {
                                family: "'Playfair Display', serif",
                                size: 12
                            },
                            bodyFont: {
                                family: "'Outfit', sans-serif",
                                size: 12
                            },
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: true
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: "'Outfit', sans-serif",
                                    size: 11
                                },
                                color: '#8a7a72'
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: {
                                color: 'rgba(176, 138, 66, 0.08)'
                            },
                            ticks: {
                                font: {
                                    family: "'Outfit', sans-serif",
                                    size: 11
                                },
                                color: '#8a7a72',
                                callback: function(value) {
                                    return 'Rp' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value);
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                font: {
                                    family: "'Outfit', sans-serif",
                                    size: 11
                                },
                                color: '#8a7a72',
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
