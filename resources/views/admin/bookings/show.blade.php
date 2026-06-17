@extends('layouts.admin', ['title' => 'Detail Booking — LYB'])

@section('admin_content')

    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                <a href="{{ route('admin.bookings.index') }}" class="lyb-admin-back-btn me-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('admin.bookings.invoice', $booking) }}" class="btn btn-sm" style="border-radius: 8px; background-color: #fbf8f1; border: 1px solid #eadfd6; color: #5c430e; font-weight: 600;">
                    <i class="bi bi-receipt"></i> Cetak / Lihat Invoice
                </a>
            </div>
            <h2>Detail Booking</h2>
            <p>{{ $booking->booking_code }}</p>
        </div>
        <span class="lyb-admin-status {{ $booking->status }} lyb-status-lg">
            {{ ucfirst($booking->status) }}
        </span>

    {{-- Info Pengajuan Pembatalan (Read-Only untuk Admin) --}}
    @if ($booking->latestCancellationRequest && $booking->latestCancellationRequest->status_persetujuan === 'diajukan')
        <div class="alert alert-warning border-warning rounded-4 shadow-sm p-4 mb-4" style="background-color: #fffbeb;">
            <h5 class="fw-bold text-dark"><i class="bi bi-info-circle-fill text-warning me-2"></i> Pengajuan Pembatalan Customer</h5>
            <p class="text-secondary small mb-3">Customer telah mengajukan permohonan pembatalan. Saat ini sedang <strong>menunggu persetujuan Owner</strong>. Berikut rincian pengembalian dana:</p>
            <div class="p-3 bg-white rounded-3 border border-warning-subtle small mb-0" style="white-space: pre-wrap; font-family: sans-serif; color: #4e3a27;">{{ $booking->latestCancellationRequest->alasan }}</div>
        </div>
    @endif



    {{-- Flash Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-3" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Kolom Kiri: Info Booking --}}
        <div class="col-lg-8">

            {{-- Info Customer --}}
            <div class="lyb-admin-detail-card mb-4">
                <h3 class="lyb-admin-detail-title">
                    <i class="bi bi-person"></i> Informasi Customer
                </h3>
                <div class="lyb-admin-detail-grid">
                    <div>
                        <span>Nama</span>
                        <strong>{{ $booking->user->name ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>No. HP</span>
                        <strong>{{ $booking->user->phone ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Email</span>
                        <strong>{{ $booking->user->email ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Instagram</span>
                        <strong>{{ $booking->user->instagram ? '@' . $booking->user->instagram : '-' }}</strong>
                    </div>
                </div>
            </div>

            {{-- Info Paket --}}
            <div class="lyb-admin-detail-card mb-4">
                <h3 class="lyb-admin-detail-title">
                    <i class="bi bi-bag-heart"></i> Detail Paket
                </h3>
                <div class="lyb-admin-detail-grid">
                    <div>
                        <span>Paket</span>
                        <strong>{{ $booking->package->name ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Kategori</span>
                        <strong>{{ $booking->package->category->name ?? '-' }}</strong>
                    </div>
                    <div>
                        <span>Tanggal Booking</span>
                        <strong>{{ $booking->booking_date->translatedFormat('l, d F Y') }}</strong>
                    </div>
                    <div>
                        <span>Softlens</span>
                        <strong>{{ $booking->softlens ? 'Ya' : 'Tidak' }}</strong>
                    </div>
                    @if ($booking->tanggal_fitting)
                        <div>
                            <span>Tanggal Fitting Baju</span>
                            <strong class="text-gold">
                                <i class="bi bi-scissors"></i>
                                {{ $booking->tanggal_fitting->translatedFormat('l, d F Y') }}
                            </strong>
                        </div>
                        <div>
                            <span>Urutan Prioritas Fitting</span>
                            <strong>
                                @if ($booking->fitting_priority)
                                    <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 11px; font-weight: 700;">
                                        <i class="bi bi-star-fill"></i> Prioritas #{{ $booking->fitting_priority }}
                                    </span>
                                @else
                                    -
                                @endif
                            </strong>
                        </div>
                    @endif
                </div>

                {{-- Addon --}}
                @if ($booking->addons->count() > 0)
                    <div class="lyb-admin-addon-list mt-3">
                        <span class="lyb-admin-label-sm">Tambahan (Addon):</span>
                        <div class="d-flex flex-wrap gap-2 mt-1">
                            @foreach ($booking->addons as $addon)
                                <span class="lyb-addon-badge">
                                    {{ $addon->name }} — Rp{{ number_format($addon->pivot->price, 0, ',', '.') }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Catatan --}}
                @if ($booking->notes)
                    <div class="lyb-admin-notes mt-3">
                        <span class="lyb-admin-label-sm">Catatan dari Customer:</span>
                        <p class="mb-0 mt-1">{{ $booking->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Rincian Pembayaran --}}
            <div class="lyb-admin-detail-card mb-4">
                <h3 class="lyb-admin-detail-title">
                    <i class="bi bi-receipt"></i> Rincian Pembayaran
                </h3>
                <div class="lyb-admin-payment-summary">
                    <div>
                        <span>Subtotal Paket</span>
                        <strong>Rp{{ number_format($booking->subtotal, 0, ',', '.') }}</strong>
                    </div>
                    <div>
                        <span>Total Addon</span>
                        <strong>Rp{{ number_format($booking->addon_total, 0, ',', '.') }}</strong>
                    </div>
                    <div>
                        <span>Total Harga</span>
                        <strong>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                    </div>
                    <div>
                        <span>DP</span>
                        <strong>Rp{{ number_format($booking->dp_amount, 0, ',', '.') }}</strong>
                    </div>
                    <div class="lyb-payment-remaining">
                        <span>Sisa Pelunasan</span>
                        <strong>Rp{{ number_format($booking->remaining_payment, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            {{-- Bukti Pembayaran --}}
            @if ($booking->payments->count() > 0)
                <div class="lyb-admin-detail-card">
                    <h3 class="lyb-admin-detail-title">
                        <i class="bi bi-image"></i> Bukti Pembayaran
                    </h3>
                    @foreach ($booking->payments as $payment)
                        <div class="lyb-payment-proof">
                            <div class="lyb-proof-meta">
                                <span>Jumlah: <strong>Rp{{ number_format($payment->amount, 0, ',', '.') }}</strong></span>
                                <span
                                    class="lyb-admin-status {{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
                                @if ($payment->paid_at)
                                    <span class="text-muted">{{ $payment->paid_at->translatedFormat('d M Y, H:i') }}</span>
                                @endif
                            </div>
                            @if ($payment->proof_image)
                                <img src="{{ asset('storage/' . $payment->proof_image) }}" alt="Bukti Pembayaran"
                                    class="lyb-proof-img">
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        {{-- Kolom Kanan: Status Booking --}}
        <div class="col-lg-4">
            <div class="lyb-admin-detail-card">
                <h3 class="lyb-admin-detail-title">
                    <i class="bi bi-info-square"></i> Status Booking
                </h3>
                <div class="mb-3 text-center">
                    <span class="badge py-2 px-3 fs-6 w-100 {{ $booking->payment_status == 'lunas' ? 'bg-success' : ($booking->payment_status == 'dp_diterima' ? 'bg-info' : ($booking->payment_status == 'dp_diupload' ? 'bg-warning text-dark' : 'bg-secondary')) }}">
                        PEMBAYARAN: {{ strtoupper(str_replace('_', ' ', $booking->payment_status)) }}
                    </span>
                </div>
                <div class="mb-4 text-center">
                    <span class="badge py-2 px-3 fs-6 w-100 {{ $booking->status == 'diterima' ? 'bg-success' : ($booking->status == 'dibatalkan' || $booking->status == 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">
                        BOOKING: {{ strtoupper(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>
                <div class="p-3 rounded-4" style="background-color: #fdfaf7; border: 1px solid #eadfd6;">
                    <p class="small text-secondary mb-0">
                        <i class="bi bi-shield-lock-fill text-gold me-1"></i>
                        Persetujuan booking, konfirmasi DP/pembayaran, dan persetujuan pembatalan dikelola sepenuhnya oleh <strong>Owner</strong>. Admin hanya memiliki akses untuk memantau data.
                    </p>
                </div>
            </div>
        </div>

    </div>

@endsection
