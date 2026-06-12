@extends('layouts.admin', ['title' => 'Detail Booking — LYB'])

@section('admin_content')

    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <a href="{{ route('admin.bookings.index') }}" class="lyb-admin-back-btn">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h2 class="mt-2">Detail Booking</h2>
            <p>{{ $booking->booking_code }}</p>
        </div>
        <span class="lyb-admin-status {{ $booking->status }} lyb-status-lg">
            {{ ucfirst($booking->status) }}
        </span>
    </header>

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

        {{-- Kolom Kanan: Update Status --}}
        <div class="col-lg-4">
            <div class="lyb-admin-detail-card lyb-status-card">
                <h3 class="lyb-admin-detail-title">
                    <i class="bi bi-pencil-square"></i> Perbarui Status
                </h3>
                <form action="{{ route('admin.bookings.updateStatus', $booking) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label lyb-form-label">Status Booking</label>
                        <select name="status" class="form-select lyb-admin-select">
                            @foreach (['pending', 'confirmed', 'process', 'completed', 'cancelled', 'expired'] as $status)
                                <option value="{{ $status }}" {{ $booking->status === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="lyb-admin-btn-submit w-100">
                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

    </div>

@endsection
