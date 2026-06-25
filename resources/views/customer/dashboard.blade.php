@extends('layouts.customer', ['title' => 'Dashboard Customer — LYB'])

@section('customer_content')
<style>
    /* Premium Colors and Styling for Personal Dashboard */
    :root {
        --premium-gold: #b08a42;
        --premium-gold-dark: #8c6a2f;
        --premium-gold-light: #d6b87d;
        --premium-dark: #211313;
        --premium-dark-light: #3a2222;
        --card-border: rgba(176, 138, 66, 0.15);
        --card-bg-white: #ffffff;
    }

    /* Welcome Banner Card */
    .premium-welcome-card {
        background: linear-gradient(135deg, #1c0e0e 0%, #301a1a 50%, #442626 100%);
        border-radius: 24px;
        padding: 32px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(176, 138, 66, 0.25);
        box-shadow: 0 12px 30px rgba(33, 19, 19, 0.08);
        margin-bottom: 32px;
    }
    
    .premium-welcome-card::after {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(176, 138, 66, 0.15) 0%, rgba(176, 138, 66, 0) 70%);
        top: -100px;
        right: -80px;
        pointer-events: none;
    }

    .welcome-card-content {
        position: relative;
        z-index: 2;
    }

    .welcome-card-content h2 {
        font-weight: 700;
        font-size: 26px;
        letter-spacing: -0.5px;
        color: #ffffff !important;
        margin-bottom: 8px;
    }

    .welcome-card-content p {
        color: #eadfd6 !important;
        font-weight: 400;
        font-size: 14px;
        margin-bottom: 0;
        max-width: 680px;
        line-height: 1.6;
    }

    .welcome-avatar {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #b08a42 0%, #e0c897 100%);
        color: #ffffff;
        font-size: 26px;
        font-weight: 700;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(176, 138, 66, 0.35);
        border: 2.5px solid #ffffff;
    }

    /* Stat Cards */
    .premium-stat-card {
        background: var(--card-bg-white);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 8px 24px rgba(176, 138, 66, 0.02);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        height: 100%;
    }

    .premium-stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 32px rgba(176, 138, 66, 0.08);
        border-color: rgba(176, 138, 66, 0.35);
    }

    .premium-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        transition: all 0.3s ease;
    }

    .premium-stat-card:hover .premium-stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .premium-stat-icon.total {
        background: linear-gradient(135deg, #f5f3ff 0%, #ddd6fe 100%);
        color: #7c3aed;
    }

    .premium-stat-icon.pending {
        background: linear-gradient(135deg, #fffbeb 0%, #fde68a 100%);
        color: #d97706;
    }

    .premium-stat-icon.selesai {
        background: linear-gradient(135deg, #ecfdf5 0%, #a7f3d0 100%);
        color: #059669;
    }

    .premium-stat-info {
        display: flex;
        flex-direction: column;
    }

    .premium-stat-info span {
        font-size: 12px;
        font-weight: 600;
        color: #8a7a72;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }

    .premium-stat-info strong {
        font-size: 30px;
        font-weight: 700;
        color: #211313;
        line-height: 1;
    }

    /* Table Design */
    .premium-table-card {
        background: #ffffff;
        border: 1px solid var(--card-border);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(33, 19, 19, 0.02);
        margin-top: 8px;
    }

    .premium-table-card .lyb-admin-table thead th {
        background: #faf6f0;
        color: #5c4a40;
        font-weight: 700;
        font-size: 11px;
        letter-spacing: 1.5px;
        padding: 16px 24px;
        border-bottom: 1.5px solid var(--card-border);
        text-transform: uppercase;
    }

    .premium-table-card .lyb-admin-table tbody td {
        padding: 18px 24px;
        font-size: 14px;
        border-bottom: 1px solid #fcf9f5;
    }

    .premium-table-card .lyb-admin-table tbody tr {
        transition: background-color 0.25s ease;
    }

    .premium-table-card .lyb-admin-table tbody tr:hover {
        background-color: #fffbf7;
    }

    .premium-table-card .lyb-admin-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Modern Badge Status */
    .premium-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 12px;
        letter-spacing: 0.2px;
        border: 1.5px solid transparent;
        line-height: 1;
    }

    .premium-status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .premium-status-badge.completed {
        background: #ecfdf5;
        color: #059669;
        border-color: #a7f3d0;
    }
    .premium-status-badge.completed::before { background-color: #059669; }

    .premium-status-badge.confirmed {
        background: #f0fdfa;
        color: #0d9488;
        border-color: #99f6e4;
    }
    .premium-status-badge.confirmed::before { background-color: #0d9488; }

    .premium-status-badge.pending-confirm {
        background: #f5f3ff;
        color: #7c3aed;
        border-color: #ddd6fe;
    }
    .premium-status-badge.pending-confirm::before { background-color: #7c3aed; }

    .premium-status-badge.waiting-payment {
        background: #fffbeb;
        color: #d97706;
        border-color: #fde68a;
    }
    .premium-status-badge.waiting-payment::before { background-color: #d97706; }

    .premium-status-badge.waiting-cancel {
        background: #fff7ed;
        color: #ea580c;
        border-color: #ffedd5;
    }
    .premium-status-badge.waiting-cancel::before { background-color: #ea580c; }

    .premium-status-badge.cancelled {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }
    .premium-status-badge.cancelled::before { background-color: #dc2626; }

    .premium-status-badge.expired {
        background: #f3f4f6;
        color: #4b5563;
        border-color: #e5e7eb;
    }
    .premium-status-badge.expired::before { background-color: #4b5563; }

    /* Action Buttons */
    .premium-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1.5px solid transparent;
        cursor: pointer;
    }

    .premium-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .premium-action-btn.btn-pay {
        background: linear-gradient(135deg, #b08a42 0%, #d6b87d 100%);
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(176, 138, 66, 0.2);
    }

    .premium-action-btn.btn-pay:hover {
        background: linear-gradient(135deg, #8c6a2f 0%, #b08a42 100%);
        box-shadow: 0 6px 16px rgba(176, 138, 66, 0.3);
    }

    .premium-action-btn.btn-review {
        background: #fffdf5;
        color: #b08a42 !important;
        border-color: rgba(176, 138, 66, 0.4);
    }

    .premium-action-btn.btn-review:hover {
        background: #b08a42;
        color: #ffffff !important;
        border-color: #b08a42;
    }

    .premium-action-btn.btn-invoice {
        background: #ffffff;
        color: #6f625c !important;
        border-color: #eadfd6;
    }

    .premium-action-btn.btn-invoice:hover {
        background: #fbf9f6;
        color: #211313 !important;
        border-color: #c9b8b8;
    }

    .premium-action-btn.btn-cancel {
        background: #fff5f5;
        color: #dc2626 !important;
        border-color: rgba(220, 38, 38, 0.2);
    }

    .premium-action-btn.btn-cancel:hover {
        background: #dc2626;
        color: #ffffff !important;
        border-color: #dc2626;
    }

    /* Modal Form Custom */
    .premium-modal-card {
        border-radius: 24px !important;
        overflow: hidden;
        border: none !important;
    }

    .premium-form-control {
        border: 1.5px solid #eadfd6;
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 14px;
        transition: all 0.25s ease;
    }

    .premium-form-control:focus {
        border-color: #b08a42;
        box-shadow: 0 0 0 3.5px rgba(176, 138, 66, 0.12);
        outline: none;
    }

    /* Search Bar */
    .premium-search-wrapper {
        position: relative;
        max-width: 320px;
        width: 100%;
    }

    .premium-search-wrapper i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #b08a42;
        font-size: 15px;
        pointer-events: none;
        transition: color 0.2s ease;
    }

    .premium-search-input {
        width: 100%;
        padding: 10px 16px 10px 42px;
        border: 1.5px solid #eadfd6;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 500;
        color: #211313;
        background: #ffffff;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .premium-search-input::placeholder {
        color: #b8a89e;
        font-weight: 400;
    }

    .premium-search-input:focus {
        outline: none;
        border-color: #b08a42;
        box-shadow: 0 0 0 3.5px rgba(176, 138, 66, 0.1);
    }

    .premium-search-input:focus + i,
    .premium-search-wrapper:focus-within i {
        color: #8c6a2f;
    }

    /* Empty state */
    .premium-empty-state {
        padding: 60px 24px !important;
        text-align: center;
    }

    .premium-empty-state i {
        font-size: 48px;
        color: #d8c8be;
        margin-bottom: 16px;
        display: inline-block;
    }

    .premium-empty-state p {
        color: #6f625c;
        font-size: 15px;
        margin-bottom: 0;
    }

    .search-no-result td {
        padding: 48px 24px !important;
        text-align: center;
    }

    .booking-row {
        transition: opacity 0.2s ease;
    }
</style>

    {{-- Welcome Header Card --}}
    <header class="premium-welcome-card">
        <div class="welcome-card-content d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
            <div class="d-flex align-items-center gap-3">
                <div class="welcome-avatar flex-shrink-0 d-none d-sm-flex">
                    {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'C' }}
                </div>
                <div>
                    <h2>Halo, {{ Auth::check() ? explode(' ', Auth::user()->name)[0] : 'Customer' }}!</h2>
                    <p>Selamat datang di dashboard personal Anda. Pantau status booking, tagihan invoice, dan riwayat pesanan dengan mudah di satu tempat.</p>
                </div>
            </div>
            @if($activeBookingCount > 0)
                <div class="badge py-2 px-3 text-white rounded-pill d-inline-flex align-items-center gap-2 align-self-start align-self-md-center" style="background-color: rgba(176, 138, 66, 0.25); border: 1.5px solid rgba(176, 138, 66, 0.35); font-weight: 500; font-size: 13px;">
                    <span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background-color: #ffd700; box-shadow: 0 0 8px #ffd700;"></span>
                    {{ $activeBookingCount }} Booking Aktif
                </div>
            @endif
        </div>
    </header>

    {{-- Notifikasi Hasil Pengajuan Pembatalan --}}
    @foreach ($unreadCancellationNotifs ?? [] as $notif)
        @php
            $isApproved = $notif->status_persetujuan === 'disetujui';
            $bookingCode = $notif->booking->booking_code ?? '-';
        @endphp
        <div class="alert alert-dismissible fade show mb-4 rounded-4 shadow-sm px-4 py-3"
             style="background-color: {{ $isApproved ? '#ecfdf5' : '#fffbeb' }}; border: 1.5px solid {{ $isApproved ? '#a7f3d0' : '#fde68a' }}; color: #211313;">
            <div class="d-flex align-items-center gap-3">
                <div style="font-size: 28px; line-height: 1;">
                    {!! $isApproved ? '<i class="bi bi-check-circle-fill" style="color:#059669;"></i>' : '<i class="bi bi-x-circle-fill" style="color:#dc2626;"></i>' !!}
                </div>
                <div class="flex-grow-1">
                    @if ($isApproved)
                        <strong class="text-success" style="font-size: 15px;">Pengajuan pembatalan DISETUJUI</strong>
                        <div class="small text-secondary mt-1">
                            Booking <strong>{{ $bookingCode }}</strong> telah resmi dibatalkan oleh Owner.
                            Pengembalian dana (refund) akan diproses sesuai rekening yang Anda daftarkan.
                        </div>
                    @else
                        <strong class="text-warning" style="font-size: 15px;">Pengajuan pembatalan DITOLAK</strong>
                        <div class="small text-secondary mt-1">
                            Permohonan pembatalan booking <strong>{{ $bookingCode }}</strong> tidak disetujui oleh Owner.
                            Status booking kembali aktif. Hubungi kami jika ada pertanyaan.
                        </div>
                    @endif
                </div>
                <form action="{{ route('customer.cancellations.dismiss', $notif->id) }}" method="POST" class="ms-2">
                    @csrf
                    <button type="submit" class="btn btn-sm fw-semibold rounded-pill px-3 py-1.5"
                            style="background: {{ $isApproved ? '#d1fae5' : '#fef3c7' }}; border: 1.5px solid {{ $isApproved ? '#a7f3d0' : '#fde68a' }}; color: {{ $isApproved ? '#047857' : '#b45309' }}; font-size: 12px; transition: all 0.2s ease;">
                        <i class="bi bi-check-lg me-1"></i> Mengerti
                    </button>
                </form>
            </div>
        </div>
    @endforeach

    {{-- Stat Cards --}}
    <section class="lyb-admin-section">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="premium-stat-card">
                    <div class="premium-stat-icon total">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div class="premium-stat-info">
                        <span>Total Booking</span>
                        <strong>{{ $totalBookingCount }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="premium-stat-card">
                    <div class="premium-stat-icon pending">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="premium-stat-info">
                        <span>Booking Aktif</span>
                        <strong>{{ $activeBookingCount }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="premium-stat-card">
                    <div class="premium-stat-icon selesai">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="premium-stat-info">
                        <span>Selesai</span>
                        <strong>{{ $completedBookingCount }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tabel Riwayat Booking --}}
    <section class="lyb-admin-section">
        <div class="lyb-admin-section-head d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
            <div>
                <h3 class="fw-bold m-0" style="font-size: 20px; color: #211313; font-family: 'Outfit', sans-serif;">Riwayat Booking</h3>
                <span class="small text-secondary fw-medium" id="bookingCountLabel">Total: {{ $bookings->count() }} Data</span>
            </div>
            <div class="premium-search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" id="searchBooking" class="premium-search-input" placeholder="Cari kode, paket, tanggal...">
            </div>
        </div>

        <div class="premium-table-card">
            <div class="table-responsive">
                <table class="table lyb-admin-table align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>KODE</th>
                            <th>PAKET</th>
                            <th>TANGGAL ACARA</th>
                            <th>TOTAL BIAYA</th>
                            <th>STATUS</th>
                            <th class="text-end">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr class="booking-row">
                                <td>
                                    <strong class="text-dark" style="font-size: 14px;">{{ $booking->booking_code }}</strong>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $booking->package->name ?? '-' }}</div>
                                    <div class="small text-secondary mt-0.5" style="font-size: 12px;">{{ $booking->package->category->name ?? '-' }}</div>
                                </td>
                                <td class="text-nowrap">
                                    <div class="fw-medium text-dark">
                                        {{ ($booking->tanggal_acara ?? null) ? $booking->tanggal_acara->translatedFormat('d M Y') : (($booking->booking_date ?? null) ? $booking->booking_date->translatedFormat('d M Y') : '-') }}
                                    </div>
                                    @if($booking->slot_waktu)
                                        <div class="small text-secondary mt-0.5" style="font-size: 12px;">
                                            <i class="bi bi-clock me-1 text-gold" style="color: #b08a42;"></i>{{ ucfirst($booking->slot_waktu) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-semibold text-dark">
                                    Rp{{ number_format($booking->total_price ?? 0, 0, ',', '.') }}
                                </td>
                                <td>
                                    @php
                                        $sClass = 'waiting-payment';
                                        $sText = strtoupper($booking->payment_status ?? '');
                                        if (($booking->payment_status ?? '') == 'lunas') {
                                            $sClass = 'completed'; $sText = 'Lunas';
                                        } elseif (($booking->payment_status ?? '') == 'dp_diterima') {
                                            $sClass = 'confirmed'; $sText = 'DP Dibayar';
                                        } elseif (($booking->payment_status ?? '') == 'dp_diupload') {
                                            $sClass = 'pending-confirm'; $sText = 'Perlu Konfirmasi';
                                        } elseif (($booking->payment_status ?? '') == 'belum_bayar') {
                                            $sClass = 'waiting-payment'; $sText = 'Menunggu DP';
                                        }
                                        if (($booking->status ?? '') == 'dibatalkan') {
                                            $sClass = 'cancelled'; $sText = 'Dibatalkan';
                                        } elseif (($booking->status ?? '') == 'expired') {
                                            $sClass = 'expired'; $sText = 'Expired';
                                        }
                                        
                                        // Cek pengajuan pembatalan customer
                                        $cancelReq = $booking->latestCancellationRequest ?? null;
                                        if ($cancelReq && $cancelReq->status_persetujuan === 'diajukan') {
                                            $sClass = 'waiting-cancel';
                                            $sText = 'Menunggu Batal';
                                        }
                                    @endphp
                                    <span class="premium-status-badge {{ $sClass }}">
                                        {{ $sText }}
                                    </span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <div class="d-flex justify-content-end gap-2 align-items-center">
                                        @if(($booking->status ?? '') === 'selesai' && ($booking->payment_status ?? '') === 'lunas')
                                            @if($booking->review)
                                                <span class="badge py-2 px-3 text-dark d-inline-flex align-items-center gap-1" style="background-color: #fbf5ef; border: 1px solid #eadfd6; border-radius: 20px; font-weight: 600;">
                                                    <i class="bi bi-star-fill" style="color: #b08a42;"></i> {{ $booking->review->rating }}
                                                </span>
                                            @else
                                                <a href="{{ route('customer.bookings.show', $booking->booking_code) }}#review-section" class="premium-action-btn btn-review">
                                                    <i class="bi bi-star"></i> Beri Review
                                                </a>
                                            @endif
                                        @endif

                                        @if(($booking->payment_status ?? '') === 'belum_bayar' && ($booking->status ?? '') === 'pending')
                                            <a href="{{ route('customer.payment.instruction', $booking->booking_code) }}" class="premium-action-btn btn-pay">
                                                <i class="bi bi-wallet2"></i> Bayar DP
                                            </a>
                                        @endif

                                        <a href="{{ route('customer.bookings.invoice', $booking->booking_code) }}" class="premium-action-btn btn-invoice">
                                            <i class="bi bi-receipt"></i> Invoice
                                        </a>
                                        @if(in_array($booking->status, ['pending', 'menunggu_konfirmasi', 'diterima']) && (!$cancelReq || $cancelReq->status_persetujuan !== 'diajukan'))
                                            <button type="button" class="premium-action-btn btn-cancel" onclick="openCancelModal('{{ route('customer.bookings.cancel', $booking->booking_code) }}')">
                                                Batal
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center premium-empty-state">
                                    <i class="bi bi-inbox-fill"></i>
                                    <p class="mt-2 fw-semibold text-dark">Belum ada riwayat booking.</p>
                                    <small class="text-secondary d-block mt-1">Booking paket pernikahan atau make-up artist pertama Anda sekarang.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Modal Pengajuan Pembatalan --}}
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content premium-modal-card border-0 shadow-lg">
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="cancelModalLabel">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i> Pengajuan Pembatalan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cancelForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body px-4 pb-4">
                        <p class="text-secondary small mb-3">Harap isi detail alasan dan rekening pengembalian dana DP Anda. Proses refund dilakukan secara manual oleh Admin/Owner.</p>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small mb-1">Alasan Pembatalan <span class="text-danger">*</span></label>
                            <textarea name="alasan" class="form-control premium-form-control" rows="2" required placeholder="Misal: Acara ditunda, salah pilih tanggal, dll."></textarea>
                        </div>

                        <div class="p-3 rounded-4" style="background: #fffdfb; border: 1.5px solid rgba(176, 138, 66, 0.15);">
                            <h6 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2" style="font-size: 13px;">
                                <i class="bi bi-credit-card-2-front text-gold"></i> Rekening Pengembalian Dana (Refund)
                            </h6>
                            
                            <div class="mb-2">
                                <label class="form-label small mb-1" style="font-size: 11px; font-weight: 500;">Nama Bank / E-Wallet <span class="text-danger">*</span></label>
                                <input type="text" name="bank_name" class="form-control premium-form-control form-control-sm" required placeholder="Contoh: BCA, Mandiri, GoPay, OVO">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small mb-1" style="font-size: 11px; font-weight: 500;">Nomor Rekening / No. HP <span class="text-danger">*</span></label>
                                <input type="text" name="bank_account" class="form-control premium-form-control form-control-sm" required placeholder="Contoh: 1234567890">
                            </div>
                            <div>
                                <label class="form-label small mb-1" style="font-size: 11px; font-weight: 500;">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                                <input type="text" name="account_holder" class="form-control premium-form-control form-control-sm" required placeholder="Contoh: Silvana Yulia">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-sm btn-light rounded-pill px-3 py-2 fw-semibold" data-bs-dismiss="modal" style="border: 1px solid #eadfd6;">Tutup</button>
                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-4 py-2 fw-bold" style="border: none; background: #dc2626;">Kirim Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCancelModal(actionUrl) {
            const modalEl = document.getElementById('cancelModal');
            const formEl = document.getElementById('cancelForm');
            formEl.action = actionUrl;
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }

        // Live Search Filtering
        (function() {
            const searchInput = document.getElementById('searchBooking');
            const countLabel = document.getElementById('bookingCountLabel');
            const tbody = document.querySelector('.premium-table-card tbody');
            if (!searchInput || !tbody) return;

            const rows = Array.from(tbody.querySelectorAll('tr.booking-row'));
            const totalCount = rows.length;

            // Create no-result row (hidden by default)
            const noResultRow = document.createElement('tr');
            noResultRow.className = 'search-no-result';
            noResultRow.style.display = 'none';
            noResultRow.innerHTML = '<td colspan="6" class="premium-empty-state"><i class="bi bi-search" style="font-size:40px;color:#d8c8be;"></i><p class="mt-2 fw-semibold text-dark">Tidak ada hasil ditemukan.</p><small class="text-secondary d-block mt-1">Coba kata kunci lain.</small></td>';
            tbody.appendChild(noResultRow);

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let visibleCount = 0;

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const match = !query || text.includes(query);
                    row.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                });

                noResultRow.style.display = (visibleCount === 0 && totalCount > 0) ? '' : 'none';
                countLabel.textContent = query
                    ? 'Ditemukan: ' + visibleCount + ' dari ' + totalCount + ' Data'
                    : 'Total: ' + totalCount + ' Data';
            });
        })();
    </script>
@endsection
