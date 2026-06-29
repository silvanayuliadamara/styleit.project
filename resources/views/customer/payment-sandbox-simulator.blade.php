@extends('layouts.app', ['title' => 'Sandbox Payment Gateway'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment-sandbox.css') }}">
@endpush

@section('content')
<section class="sandbox-page">
    <a href="{{ route('customer.dashboard') }}" class="sim-back-link">
        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>

    <div class="sandbox-env-badge">
        <span class="dot"></span>
        Sandbox Mode — Simulasi
    </div>

    <div class="simulator-card">
        {{-- Header --}}
        <div class="sim-header">
            <div class="sim-brand">
                <div class="sim-brand-icon">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>
                <div class="sim-brand-text">
                    <span class="sim-brand-name">StyleIt Gateway</span>
                    <span class="sim-brand-label">Payment Simulator</span>
                </div>
            </div>
            <span class="sim-header-badge">Sandbox</span>
        </div>

        {{-- Amount --}}
        <div class="sim-amount-section">
            <div class="sim-amount-label">Total Pembayaran DP</div>
            <div class="sim-amount-value">
                <span class="currency">Rp</span>{{ number_format($booking->dp_amount, 0, ',', '.') }}
            </div>
            <span class="sim-booking-code">{{ $booking->booking_code }}</span>
        </div>

        {{-- Details --}}
        <div class="sim-details-section">
            <div class="sim-detail-row">
                <span class="sim-detail-label">Pelanggan</span>
                <span class="sim-detail-value">{{ $booking->user->name }}</span>
            </div>
            <div class="sim-detail-row">
                <span class="sim-detail-label">Paket</span>
                <span class="sim-detail-value">{{ $booking->package->name ?? '-' }}</span>
            </div>
            <div class="sim-detail-row">
                <span class="sim-detail-label">Tanggal Acara</span>
                <span class="sim-detail-value">{{ $booking->tanggal_acara ? $booking->tanggal_acara->format('d M Y') : '-' }}</span>
            </div>
            <div class="sim-detail-row">
                <span class="sim-detail-label">Total Harga</span>
                <span class="sim-detail-value">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="sim-detail-row">
                <span class="sim-detail-label">Sisa Pelunasan</span>
                <span class="sim-detail-value">Rp{{ number_format($booking->remaining_payment, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Payment Method --}}
        <div class="sim-method-section">
            <div class="sim-method-title">Metode Pembayaran</div>
            <div class="sim-method-grid">
                <div class="sim-method-item active" data-method="bank_transfer" onclick="selectMethod(this)">
                    <span class="sim-method-icon"><i class="bi bi-bank"></i></span>
                    <span class="sim-method-name">Virtual Account</span>
                </div>
                <div class="sim-method-item" data-method="qris" onclick="selectMethod(this)">
                    <span class="sim-method-icon"><i class="bi bi-qr-code-scan"></i></span>
                    <span class="sim-method-name">QRIS</span>
                </div>
                <div class="sim-method-item" data-method="e_wallet" onclick="selectMethod(this)">
                    <span class="sim-method-icon"><i class="bi bi-wallet2"></i></span>
                    <span class="sim-method-name">E-Wallet</span>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="sim-actions-section">
            <div class="sim-actions-title">Simulasi Hasil Pembayaran</div>
            <div class="sim-btn-group">
                <button class="sim-btn sim-btn-success" onclick="simulatePayment('success')">
                    <i class="bi bi-check-circle-fill"></i>
                    <div class="sim-btn-content">
                        <span class="sim-btn-main">Simulasi Bayar Berhasil</span>
                        <span class="sim-btn-sub">Status → DP Diterima, Booking Diterima</span>
                    </div>
                </button>
                <button class="sim-btn sim-btn-pending" onclick="simulatePayment('pending')">
                    <i class="bi bi-clock-fill"></i>
                    <div class="sim-btn-content">
                        <span class="sim-btn-main">Simulasi Pending</span>
                        <span class="sim-btn-sub">Status tetap → Menunggu Pembayaran</span>
                    </div>
                </button>
                <button class="sim-btn sim-btn-failed" onclick="simulatePayment('failed')">
                    <i class="bi bi-x-circle-fill"></i>
                    <div class="sim-btn-content">
                        <span class="sim-btn-main">Simulasi Gagal / Expired</span>
                        <span class="sim-btn-sub">Status → Dibatalkan</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <div class="sim-footer">
        <div class="sim-footer-box">
            <p class="sim-footer-text">
                Ini adalah <strong>mode simulasi sandbox</strong>. Tidak ada transaksi nyata yang diproses.<br>
                Untuk payment gateway sungguhan, set <code>MIDTRANS_IS_PRODUCTION=true</code> di <code>.env</code>
            </p>
        </div>
    </div>
</section>

{{-- Processing Overlay --}}
<div class="sim-processing-overlay" id="processingOverlay">
    <div class="sim-processing-card">
        <div class="sim-processing-spinner"></div>
        <div class="sim-processing-text" id="processingText">Memproses pembayaran...</div>
        <div class="sim-processing-sub" id="processingSub">Mohon tunggu sebentar</div>
    </div>
</div>

<script>
    let selectedMethod = 'bank_transfer';

    function selectMethod(el) {
        document.querySelectorAll('.sim-method-item').forEach(item => {
            item.classList.remove('active');
        });
        el.classList.add('active');
        selectedMethod = el.dataset.method;
    }

    function simulatePayment(type) {
        const overlay = document.getElementById('processingOverlay');
        const processingText = document.getElementById('processingText');
        const processingSub = document.getElementById('processingSub');

        const methodMap = {
            'bank_transfer': 'Virtual Account (Sandbox)',
            'qris': 'QRIS (Sandbox)',
            'e_wallet': 'E-Wallet (Sandbox)',
        };
        const paymentType = methodMap[selectedMethod] || 'Sandbox';

        if (type === 'success') {
            processingText.textContent = 'Memproses pembayaran...';
            processingSub.textContent = 'Mengonfirmasi ke server...';
            overlay.classList.add('show');

            fetch("{{ route('customer.payment.confirm', $booking->booking_code) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    payment_type: paymentType,
                    transaction_id: 'sandbox-sim-' + Date.now(),
                    booking_codes: @json($bookingCodes ?? [])
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Server error: ' + response.status);
                return response.json();
            })
            .then(data => {
                processingText.textContent = '✓ Pembayaran Berhasil!';
                processingSub.textContent = 'Mengalihkan ke halaman sukses...';

                setTimeout(() => {
                    window.location.href = "{{ route('customer.payment.success', $booking->booking_code) }}";
                }, 1500);
            })
            .catch(error => {
                overlay.classList.remove('show');
                alert('Gagal memproses simulasi: ' + error.message);
            });

        } else if (type === 'pending') {
            processingText.textContent = 'Simulasi pending...';
            processingSub.textContent = 'Status pembayaran tetap menunggu';
            overlay.classList.add('show');

            setTimeout(() => {
                processingText.textContent = '⏳ Status: Pending';
                processingSub.textContent = 'Mengalihkan ke halaman instruksi...';

                setTimeout(() => {
                    window.location.href = "{{ route('customer.payment.instruction', $booking->booking_code) }}";
                }, 1200);
            }, 1500);

        } else if (type === 'failed') {
            processingText.textContent = 'Simulasi pembayaran gagal...';
            processingSub.textContent = 'Membatalkan transaksi...';
            overlay.classList.add('show');

            fetch("{{ route('midtrans.notification') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    order_id: '{{ $booking->booking_code }}-sandbox',
                    transaction_status: 'expire',
                    fraud_status: null,
                    payment_type: paymentType
                })
            })
            .then(response => response.json())
            .then(data => {
                processingText.textContent = '✗ Pembayaran Gagal';
                processingSub.textContent = 'Mengalihkan ke daftar booking...';

                setTimeout(() => {
                    window.location.href = "{{ route('customer.bookings.index') }}";
                }, 1500);
            })
            .catch(error => {
                overlay.classList.remove('show');
                alert('Gagal memproses simulasi: ' + error.message);
            });
        }
    }
</script>
@endsection
