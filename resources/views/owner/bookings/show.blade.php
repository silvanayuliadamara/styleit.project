@extends('layouts.owner', ['title' => 'Detail Booking #' . $booking->booking_code . ' — LYB'])

@section('owner_content')
    {{-- Back Link & Invoice --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('owner.bookings.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; border-color: #eadfd6; color: #211313;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Booking
        </a>
        <a href="{{ route('owner.bookings.invoice', $booking) }}" class="btn btn-sm" style="border-radius: 8px; background-color: #fbf8f1; border: 1px solid #eadfd6; color: #5c430e; font-weight: 600;">
            <i class="bi bi-receipt"></i> Cetak / Lihat Invoice
        </a>
    </div>

    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Detail Booking #{{ $booking->booking_code }}</h2>
            <p>Dibuat pada {{ $booking->created_at->translatedFormat('d F Y, H:i') }}</p>
        </div>
        <div>
            <span class="lyb-admin-status {{ $booking->status }} fs-6 px-3 py-2">
                {{ strtoupper($booking->status) }}
            </span>
        </div>
    </header>

    {{-- Pengajuan Pembatalan Alert --}}
    @if ($booking->latestCancellationRequest && $booking->latestCancellationRequest->status_persetujuan === 'diajukan')
        <div class="alert alert-warning border-warning rounded-4 shadow-sm p-4 mb-4" style="background-color: #fffbeb;">
            <h5 class="fw-bold text-dark"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i> Pengajuan Pembatalan Customer</h5>
            <p class="text-secondary small mb-3">Customer telah mengajukan permohonan pembatalan untuk booking ini dengan rincian:</p>
            <div class="p-3 bg-white rounded-3 mb-3 border border-warning-subtle small" style="white-space: pre-wrap; font-family: sans-serif; color: #4e3a27;">{{ $booking->latestCancellationRequest->alasan }}</div>
            <p class="text-secondary small mb-3">Silakan konfirmasi persetujuan Anda di bawah ini setelah melakukan transfer pengembalian dana:</p>
            <div class="d-flex gap-2">
                <form action="{{ route('owner.bookings.confirmCancel', $booking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI pembatalan booking ini?')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="btn btn-danger btn-sm fw-bold px-3" style="border-radius: 8px;">
                        <i class="bi bi-check-lg"></i> Setujui Pembatalan
                    </button>
                </form>
                <form action="{{ route('owner.bookings.confirmCancel', $booking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK pembatalan booking ini?')">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold px-3" style="border-radius: 8px; border-color: #eadfd6; color: #211313; background: #fff;">
                        <i class="bi bi-x-lg"></i> Tolak Pengajuan
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Details -->
        <div class="col-12 col-lg-8">
            {{-- Customer & Event Info --}}
            <div class="card mb-4" style="border-radius: 18px; border: 1px solid #eadfd6; background: #fff; overflow: hidden;">
                <div class="card-header bg-light py-3 border-bottom" style="border-color: #eadfd6;">
                    <h5 class="mb-0 fw-bold" style="color: #211313;"><i class="bi bi-person-fill text-gold"></i> Informasi Customer & Acara</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="text-muted small d-block">Nama Lengkap</label>
                            <span class="fw-bold">{{ $booking->user->name ?? '-' }}</span>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-muted small d-block">Email</label>
                            <span>{{ $booking->user->email ?? '-' }}</span>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-muted small d-block">Nomor Telepon</label>
                            <span>{{ $booking->user->phone ?? '-' }}</span>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="text-muted small d-block">Tanggal Acara</label>
                            <span class="fw-bold text-gold"><i class="bi bi-calendar-event"></i> {{ $booking->tanggal_acara ? $booking->tanggal_acara->translatedFormat('l, d F Y') : ($booking->booking_date ? $booking->booking_date->translatedFormat('l, d F Y') : '-') }}</span>
                        </div>
                        @if($booking->slot_waktu)
                            <div class="col-12 col-md-6">
                                <label class="text-muted small d-block">Slot MUA</label>
                                <span class="badge bg-dark" style="background: #211313 !important;">{{ strtoupper($booking->slot_waktu) }}</span>
                                @if($booking->schedule)
                                    <small class="text-muted d-block">({{ $booking->schedule->jam_mulai->format('H:i') }} - {{ $booking->schedule->jam_selesai->format('H:i') }} WIB)</small>
                                @endif
                            </div>
                        @endif
                        @if($booking->tanggal_fitting)
                            <div class="col-12 col-md-6">
                                <label class="text-muted small d-block">Tanggal Fitting Baju</label>
                                <span class="fw-bold text-gold"><i class="bi bi-scissors"></i> {{ $booking->tanggal_fitting->translatedFormat('l, d F Y') }}</span>
                                @if($booking->fitting_priority)
                                    <span class="badge bg-warning text-dark ms-2 fw-bold" style="font-size: 11px;"><i class="bi bi-star-fill"></i> Prioritas #{{ $booking->fitting_priority }}</span>
                                @endif
                            </div>
                        @endif
                        <div class="col-12">
                            <label class="text-muted small d-block">Catatan Acara / Kebutuhan Khusus</label>
                            <div class="p-3 bg-light rounded mt-1 text-secondary" style="font-size: 13px; border: 1px solid #eee;">
                                {{ $booking->notes ?? 'Tidak ada catatan.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Package Details --}}
            <div class="card mb-4" style="border-radius: 18px; border: 1px solid #eadfd6; background: #fff; overflow: hidden;">
                <div class="card-header bg-light py-3 border-bottom" style="border-color: #eadfd6;">
                    <h5 class="mb-0 fw-bold" style="color: #211313;"><i class="bi bi-box-seam-fill text-gold"></i> Detail Rincian Layanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 border-bottom">
                        <h6 class="fw-bold mb-1" style="color: #211313;">{{ $booking->package->name ?? 'Paket Kustom' }}</h6>
                        <p class="text-muted small mb-2">{{ $booking->package->description ?? '' }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Harga Paket</span>
                            <span class="fw-bold">Rp{{ number_format($booking->subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Addons --}}
                    @if($booking->addons->isNotEmpty())
                        <div class="p-3 border-bottom bg-light">
                            <h6 class="fw-bold small text-muted mb-2">Tambahan (Addon):</h6>
                            <ul class="list-unstyled mb-0">
                                @foreach($booking->addons as $addon)
                                    <li class="d-flex justify-content-between align-items-center mb-1 text-secondary" style="font-size: 13px;">
                                        <span>
                                            <i class="bi bi-plus-circle text-gold"></i>
                                            {{ $addon->pivot->nama_addon }}
                                            @if($addon->pivot->nama_option)
                                                ({{ $addon->pivot->nama_option }})
                                            @endif
                                            <small class="badge bg-secondary">x{{ $addon->pivot->qty }}</small>
                                        </span>
                                        <span>Rp{{ number_format($addon->pivot->subtotal, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="p-3 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-secondary">Subtotal Paket</span>
                            <span>Rp{{ number_format($booking->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-secondary">Subtotal Addon</span>
                            <span>Rp{{ number_format($booking->addon_total, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-top pt-2">
                            <span class="fw-bold" style="color: #211313;">Total Tagihan</span>
                            <span class="fw-bold text-gold fs-5">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Payment & Action -->
        <div class="col-12 col-lg-4">
            {{-- Status Keuangan --}}
            <div class="card mb-4" style="border-radius: 18px; border: 1px solid #eadfd6; background: #fff; overflow: hidden;">
                <div class="card-header bg-light py-3 border-bottom" style="border-color: #eadfd6;">
                    <h5 class="mb-0 fw-bold" style="color: #211313;"><i class="bi bi-wallet2 text-gold"></i> Status Keuangan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Tagihan:</span>
                        <span class="fw-bold">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-info">
                        <span>Wajib DP:</span>
                        <span>Rp{{ number_format($booking->dp_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Sudah Dibayar:</span>
                        <span class="fw-bold">Rp{{ number_format($booking->total_dibayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-danger pt-2 border-top">
                        <span class="fw-bold">Sisa Pelunasan:</span>
                        <span class="fw-bold">Rp{{ number_format($booking->sisa_pelunasan, 0, ',', '.') }}</span>
                    </div>

                    <div class="text-center pt-2">
                        <span class="badge w-100 py-2 fs-6 {{ $booking->payment_status == 'lunas' ? 'bg-success' : ($booking->payment_status == 'dp_diterima' ? 'bg-info' : ($booking->payment_status == 'dp_diupload' ? 'bg-warning' : 'bg-secondary')) }}">
                            STATUS: {{ str_replace('_', ' ', strtoupper($booking->payment_status)) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Konfirmasi DP (jika upload bukti) --}}
            @if($booking->payment_status == 'dp_diupload')
                <div class="card mb-4" style="border-radius: 18px; border: 1px solid #eadfd6; background: #fff; overflow: hidden;">
                    <div class="card-header bg-warning py-3 border-bottom text-white">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-shield-fill-check"></i> Butuh Konfirmasi DP</h5>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">Customer telah mengunggah bukti transfer DP. Harap periksa bukti pembayaran di bawah.</p>

                        @php
                            $pendingPayment = $booking->payments()->where('status', 'pending')->first();
                        @endphp
                        @if($pendingPayment && $pendingPayment->proof_image)
                            <div class="text-center mb-3">
                                <a href="{{ asset('storage/' . $pendingPayment->proof_image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $pendingPayment->proof_image) }}" alt="Bukti Transfer DP" class="img-fluid rounded" style="max-height: 240px; border: 1px solid #eadfd6;">
                                </a>
                                <small class="text-muted d-block mt-1">Klik gambar untuk memperbesar</small>
                            </div>
                        @else
                            <div class="alert alert-secondary small text-center">Bukti gambar tidak ditemukan.</div>
                        @endif

                        <form action="{{ route('owner.bookings.confirmDp', $booking->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2" style="border-radius: 10px;">
                                <i class="bi bi-check-lg"></i> Setujui & Konfirmasi DP
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Input Pelunasan (jika status booking diterima & belum lunas) --}}
            @if($booking->status == 'diterima' && $booking->payment_status != 'lunas')
                <div class="card mb-4" style="border-radius: 18px; border: 1px solid #eadfd6; background: #fff; overflow: hidden;">
                    <div class="card-header bg-success py-3 border-bottom text-white">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-cash-stack"></i> Terima Pelunasan</h5>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">Harap masukkan pelunasan setelah customer membayar sisa biaya.</p>
                        <div class="alert alert-info small py-2">
                            Nominal pelunasan: <strong>Rp{{ number_format($booking->sisa_pelunasan, 0, ',', '.') }}</strong>
                        </div>
                        <form action="{{ route('owner.bookings.confirmLunas', $booking->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2" style="border-radius: 10px;" onclick="return confirm('Apakah Anda yakin ingin memproses pelunasan booking ini?')">
                                <i class="bi bi-wallet-fill"></i> Konfirmasi Lunas
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Update Status Booking / Layanan --}}
            <div class="card" style="border-radius: 18px; border: 1px solid #eadfd6; background: #fff; overflow: hidden;">
                <div class="card-header bg-light py-3 border-bottom" style="border-color: #eadfd6;">
                    <h5 class="mb-0 fw-bold" style="color: #211313;"><i class="bi bi-gear-fill text-gold"></i> Tindakan Lainnya</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('owner.bookings.updateStatus', $booking->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Ubah Status Booking</label>
                            <select name="status" class="form-select mb-2" style="border-radius: 10px; border-color: #eadfd6;">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="menunggu_konfirmasi" {{ $booking->status == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                <option value="diterima" {{ $booking->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="ditolak" {{ $booking->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="selesai" {{ $booking->status == 'selesai' ? 'selected' : '' }}>Selesai (Completed)</option>
                                <option value="dibatalkan" {{ $booking->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Ubah Status Layanan</label>
                            <select name="status_layanan" class="form-select" style="border-radius: 10px; border-color: #eadfd6;">
                                <option value="pending" {{ $booking->status_layanan == 'pending' ? 'selected' : '' }}>Pending / Menunggu</option>
                                <option value="terjadwal" {{ $booking->status_layanan == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                                <option value="selesai" {{ $booking->status_layanan == 'selesai' ? 'selected' : '' }}>Layanan Selesai</option>
                                <option value="dibatalkan" {{ $booking->status_layanan == 'dibatalkan' ? 'selected' : '' }}>Batal</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 fw-bold" style="border-radius: 10px; background: #211313; border: none;">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
