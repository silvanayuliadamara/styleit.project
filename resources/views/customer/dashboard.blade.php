@extends('layouts.customer', ['title' => 'Dashboard Customer — LYB'])

@section('customer_content')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer-dashboard.css') }}">
@endpush

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

                                        @if(($booking->payment_status ?? '') === 'belum_bayar' && ($booking->status ?? '') === 'pending' && (!$cancelReq || $cancelReq->status_persetujuan !== 'diajukan'))
                                            <a href="{{ route('customer.payment.instruction', $booking->booking_code) }}" class="premium-action-btn btn-pay">
                                                <i class="bi bi-wallet2"></i> Bayar DP
                                            </a>
                                        @endif

                                        <a href="{{ route('customer.bookings.invoice', $booking->booking_code) }}" class="premium-action-btn btn-invoice">
                                            <i class="bi bi-receipt"></i> Invoice
                                        </a>
                                        @if(in_array($booking->status, ['pending', 'menunggu_konfirmasi', 'diterima']) && (!$cancelReq || $cancelReq->status_persetujuan !== 'diajukan'))
                                            <button type="button" class="premium-action-btn btn-cancel" onclick="openCancelModal('{{ route('customer.bookings.cancel', $booking->booking_code) }}', {{ $booking->payment_status !== 'belum_bayar' ? 'true' : 'false' }})">
                                                Batal
                                            </button>
                                        @endif
                                         @if($cancelReq && $cancelReq->status_persetujuan === 'diajukan')
                                            <form action="{{ route('customer.bookings.cancel.withdraw', $booking->booking_code) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan pembatalan?')">
                                                @csrf
                                                <button type="submit" class="premium-action-btn btn-cancel" style="background-color: #fff7ed; color: #ea580c !important; border-color: rgba(234, 88, 12, 0.25); font-size: 13px; font-weight: 600;">
                                                    Batalkan Pengajuan
                                                </button>
                                            </form>
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
                        <p class="text-secondary small mb-3" id="cancelModalSubtitle">Harap isi detail alasan dan rekening pengembalian dana DP Anda. Proses refund dilakukan secara manual oleh Admin/Owner.</p>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold small mb-1">Alasan Pembatalan <span class="text-danger">*</span></label>
                            <textarea name="alasan" class="form-control premium-form-control" rows="2" required placeholder="Misal: Acara ditunda, salah pilih tanggal, dll."></textarea>
                        </div>

                        <div class="p-3 rounded-4" id="refundSection" style="background: #fffdfb; border: 1.5px solid rgba(176, 138, 66, 0.15);">
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
        function openCancelModal(actionUrl, requiresRefund) {
            const modalEl = document.getElementById('cancelModal');
            const formEl = document.getElementById('cancelForm');
            formEl.action = actionUrl;
            
            const refundSection = document.getElementById('refundSection');
            const refundInputs = refundSection.querySelectorAll('input');
            const modalSubtitle = document.getElementById('cancelModalSubtitle');

            if (requiresRefund) {
                refundSection.style.display = 'block';
                refundInputs.forEach(input => input.required = true);
                modalSubtitle.textContent = 'Harap isi detail alasan dan rekening pengembalian dana DP Anda. Proses refund dilakukan secara manual oleh Admin/Owner.';
            } else {
                refundSection.style.display = 'none';
                refundInputs.forEach(input => {
                    input.required = false;
                    input.value = '';
                });
                modalSubtitle.textContent = 'Harap isi detail alasan pembatalan booking Anda.';
            }

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
