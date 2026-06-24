@extends('layouts.customer', ['title' => 'Dashboard Customer — LYB'])

@section('customer_content')
    {{-- Page Header --}}
    <header class="lyb-admin-page-header">
        <div>
            <h2>Halo, {{ Auth::check() ? explode(' ', Auth::user()->name)[0] : 'Customer' }}</h2>
            <p>Pantau booking & invoice Anda di sini.</p>
        </div>
    </header>

    {{-- Notifikasi Hasil Pengajuan Pembatalan --}}
    @foreach ($unreadCancellationNotifs ?? [] as $notif)
        @php
            $isApproved = $notif->status_persetujuan === 'disetujui';
            $bookingCode = $notif->booking->booking_code ?? '-';
        @endphp
        <div class="alert alert-dismissible fade show mb-3 rounded-4 shadow-sm px-4 py-3"
             style="background-color: {{ $isApproved ? '#f0fdf4' : '#fff8f0' }}; border: 1.5px solid {{ $isApproved ? '#86efac' : '#fbbf24' }}; color: #211313;">
            <div class="d-flex align-items-center gap-3">
                <div style="font-size: 28px; line-height: 1;">
                    {!! $isApproved ? '<i class="bi bi-check-circle-fill" style="color:#16a34a;"></i>' : '<i class="bi bi-x-circle-fill" style="color:#d97706;"></i>' !!}
                </div>
                <div class="flex-grow-1">
                    @if ($isApproved)
                        <strong>Pengajuan pembatalan <span class="text-success">DISETUJUI</span></strong>
                        <div class="small text-secondary mt-1">
                            Booking <strong>{{ $bookingCode }}</strong> telah resmi dibatalkan oleh Owner.
                            Pengembalian dana (refund) akan diproses sesuai rekening yang Anda daftarkan.
                        </div>
                    @else
                        <strong>Pengajuan pembatalan <span class="text-warning">DITOLAK</span></strong>
                        <div class="small text-secondary mt-1">
                            Permohonan pembatalan booking <strong>{{ $bookingCode }}</strong> tidak disetujui oleh Owner.
                            Status booking kembali aktif. Hubungi kami jika ada pertanyaan.
                        </div>
                    @endif
                </div>
                <form action="{{ route('customer.cancellations.dismiss', $notif->id) }}" method="POST" class="ms-2">
                    @csrf
                    <button type="submit" class="btn btn-sm fw-bold rounded-pill px-3"
                            style="background: {{ $isApproved ? '#dcfce7' : '#fef3c7' }}; border: 1px solid {{ $isApproved ? '#86efac' : '#fbbf24' }}; color: #211313; font-size: 12px;">
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
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon total">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Total Booking</span>
                        <strong>{{ $totalBookingCount }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon pending">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Aktif</span>
                        <strong>{{ $activeBookingCount }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="lyb-stat-card">
                    <div class="lyb-stat-icon selesai">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="lyb-stat-info">
                        <span>Selesai</span>
                        <strong>{{ $completedBookingCount }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tabel Riwayat Booking --}}
    <section class="lyb-admin-section">
        <div class="lyb-admin-section-head">
            <h3>Riwayat Booking</h3>
        </div>

        <div class="lyb-admin-table-card">
            <div class="table-responsive">
                <table class="table lyb-admin-table align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>KODE</th>
                            <th>PAKET</th>
                            <th>TANGGAL</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                            <th class="text-end">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td><strong>{{ $booking->booking_code }}</strong></td>
                                <td>{{ $booking->package->name ?? '-' }}</td>
                                <td class="text-nowrap">
                                    {{ ($booking->tanggal_acara ?? null) ? $booking->tanggal_acara->translatedFormat('d M Y') : (($booking->booking_date ?? null) ? $booking->booking_date->translatedFormat('d M Y') : '-') }}
                                    @if($booking->slot_waktu)
                                        <div class="small text-secondary mt-1">({{ ucfirst($booking->slot_waktu) }})</div>
                                    @endif
                                </td>
                                <td>Rp{{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $sClass = 'pending';
                                        $sText = strtoupper($booking->payment_status ?? '');
                                        if (($booking->payment_status ?? '') == 'lunas') {
                                            $sClass = 'completed'; $sText = 'Lunas';
                                        } elseif (($booking->payment_status ?? '') == 'dp_diterima') {
                                            $sClass = 'confirmed'; $sText = 'DP Dibayar';
                                        } elseif (($booking->payment_status ?? '') == 'dp_diupload') {
                                            $sClass = 'pending'; $sText = 'Perlu Konfirmasi';
                                        } elseif (($booking->payment_status ?? '') == 'belum_bayar') {
                                            $sClass = 'pending'; $sText = 'Menunggu DP';
                                        }
                                        if (($booking->status ?? '') == 'dibatalkan') {
                                            $sClass = 'cancelled'; $sText = 'Dibatalkan';
                                        } elseif (($booking->status ?? '') == 'expired') {
                                            $sClass = 'expired'; $sText = 'Expired';
                                        }
                                        
                                        // Cek pengajuan pembatalan customer
                                        $cancelReq = $booking->latestCancellationRequest ?? null;
                                        if ($cancelReq && $cancelReq->status_persetujuan === 'diajukan') {
                                            $sClass = 'pending';
                                            $sText = 'Menunggu Batal';
                                        }
                                    @endphp
                                    <span class="lyb-admin-status {{ $sClass }}">
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
                                                <a href="{{ route('customer.bookings.show', $booking->booking_code) }}#review-section" class="lyb-admin-action-btn text-white" style="background-color: #b08a42; border: none;">
                                                    <i class="bi bi-star"></i> Beri Review
                                                </a>
                                            @endif
                                        @endif

                                        @if(($booking->payment_status ?? '') === 'belum_bayar' && ($booking->status ?? '') === 'pending')
                                            <a href="{{ route('customer.payment.instruction', $booking->booking_code) }}" class="lyb-admin-action-btn text-white" style="background-color: #b08a42; border: none;">
                                                <i class="bi bi-wallet2"></i> Bayar DP
                                            </a>
                                        @endif

                                        <a href="{{ route('customer.bookings.invoice', $booking->booking_code) }}" class="lyb-admin-action-btn" style="background-color: #fbf8f1; border: 1px solid #eadfd6; color: #5c430e;">
                                            <i class="bi bi-receipt"></i> Invoice
                                        </a>
                                        @if(in_array($booking->status, ['pending', 'menunggu_konfirmasi', 'diterima']) && (!$cancelReq || $cancelReq->status_persetujuan !== 'diajukan'))
                                            <button type="button" class="lyb-admin-action-btn bg-danger text-white" style="border: none;" onclick="openCancelModal('{{ route('customer.bookings.cancel', $booking->booking_code) }}')">
                                                Batal
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center lyb-empty-row">
                                    <i class="bi bi-inbox"></i>
                                    <p>Belum ada riwayat booking.</p>
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
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="cancelModalLabel"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i> Pengajuan Pembatalan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cancelForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p class="text-secondary small">Harap isi detail alasan dan rekening pengembalian dana DP Anda. Proses refund dilakukan secara manual oleh Admin/Owner.</p>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small mb-1">Alasan Pembatalan <span class="text-danger">*</span></label>
                            <textarea name="alasan" class="form-control rounded-3" rows="2" required placeholder="Misal: Acara ditunda, salah pilih tanggal, dll."></textarea>
                        </div>

                        <div class="p-3 rounded-4" style="background: #fdfaf7; border: 1px solid #f4ede6;">
                            <h6 class="fw-bold mb-2 text-dark" style="font-size: 13px;"><i class="bi bi-wallet2 me-1 text-gold"></i> Rekening Pengembalian Dana (Refund)</h6>
                            
                            <div class="mb-2">
                                <label class="form-label small mb-1" style="font-size: 11px;">Nama Bank / E-Wallet <span class="text-danger">*</span></label>
                                <input type="text" name="bank_name" class="form-control form-control-sm rounded-3" required placeholder="Contoh: BCA, Mandiri, GoPay, OVO">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small mb-1" style="font-size: 11px;">Nomor Rekening / No. HP <span class="text-danger">*</span></label>
                                <input type="text" name="bank_account" class="form-control form-control-sm rounded-3" required placeholder="Contoh: 1234567890">
                            </div>
                            <div>
                                <label class="form-label small mb-1" style="font-size: 11px;">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                                <input type="text" name="account_holder" class="form-control form-control-sm rounded-3" required placeholder="Contoh: Silvana Yulia">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-sm btn-light rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-sm btn-danger rounded-3 px-3 fw-bold" style="border: none;">Kirim Pengajuan</button>
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
    </script>
@endsection
