@extends('layouts.app', ['title' => 'Sandbox Payment Gateway'])

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    body {
        background-color: #FAF7F2;
        font-family: 'Outfit', sans-serif;
    }

    .sandbox-page {
        padding: 2rem 0 5rem 0;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Sandbox badge */
    .sandbox-env-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #fffbeb;
        border: 1px solid #f9edd0;
        color: #92400e;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        padding: 0.5rem 1.25rem;
        border-radius: 30px;
        margin-bottom: 1.75rem;
    }
    .sandbox-env-badge .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #d97706;
        animation: blink 1.5s ease-in-out infinite;
    }
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    /* Main simulator card */
    .simulator-card {
        width: 100%;
        max-width: 520px;
        background: #ffffff;
        border: 1px solid #eadfd6;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(176, 138, 66, 0.05);
        animation: cardSlideUp 0.5s ease-out;
    }
    @keyframes cardSlideUp {
        from { opacity: 0; transform: translateY(25px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Card header — dark brown matching project */
    .sim-header {
        background-color: #211313;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .sim-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .sim-brand-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: linear-gradient(135deg, #b08a42, #d4a853);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #fff;
    }
    .sim-brand-text {
        display: flex;
        flex-direction: column;
    }
    .sim-brand-name {
        font-size: 0.95rem;
        font-weight: 700;
        color: #efe2d5;
        line-height: 1.2;
    }
    .sim-brand-label {
        font-size: 0.65rem;
        color: #9c8a7a;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-weight: 600;
    }
    .sim-header-badge {
        background: rgba(176, 138, 66, 0.2);
        border: 1px solid #b08a42;
        color: #dfba73;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    /* Amount section */
    .sim-amount-section {
        padding: 2rem 2rem 1.5rem;
        text-align: center;
        border-bottom: 1px solid #eadfd6;
        background: #f7f4ed;
    }
    .sim-amount-label {
        font-size: 0.78rem;
        color: #9c9587;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 600;
        margin-bottom: 0.4rem;
    }
    .sim-amount-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #211313;
        letter-spacing: -1px;
        line-height: 1.1;
    }
    .sim-amount-value .currency {
        font-size: 1.2rem;
        font-weight: 500;
        color: #9c9587;
        vertical-align: top;
        margin-right: 2px;
    }
    .sim-booking-code {
        display: inline-block;
        margin-top: 0.75rem;
        background: rgba(176, 138, 66, 0.1);
        border: 1px solid rgba(176, 138, 66, 0.25);
        color: #8a6d2f;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 4px 14px;
        border-radius: 8px;
        letter-spacing: 0.5px;
    }

    /* Order details */
    .sim-details-section {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #eadfd6;
    }
    .sim-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.55rem 0;
    }
    .sim-detail-row + .sim-detail-row {
        border-top: 1px solid #f0ebe3;
    }
    .sim-detail-label {
        font-size: 0.85rem;
        color: #9c9587;
        font-weight: 400;
    }
    .sim-detail-value {
        font-size: 0.85rem;
        color: #211313;
        font-weight: 600;
        text-align: right;
        max-width: 60%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Payment method selector */
    .sim-method-section {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #eadfd6;
    }
    .sim-method-title {
        font-size: 0.78rem;
        color: #9c9587;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .sim-method-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 0.65rem;
    }
    .sim-method-item {
        background: #f7f4ed;
        border: 1.5px solid #eadfd6;
        border-radius: 14px;
        padding: 1rem 0.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
    }
    .sim-method-item:hover {
        border-color: #b08a42;
        background: #fff9ef;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(176, 138, 66, 0.08);
    }
    .sim-method-item.active {
        border-color: #b08a42;
        background: #ffffff;
        box-shadow: 0 4px 16px rgba(176, 138, 66, 0.12);
    }
    .sim-method-item.active::after {
        content: '✓';
        position: absolute;
        top: 6px;
        right: 8px;
        font-size: 0.6rem;
        color: #fff;
        font-weight: 700;
        background: #b08a42;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .sim-method-icon {
        font-size: 1.5rem;
        margin-bottom: 0.4rem;
        display: block;
        color: #b08a42;
    }
    .sim-method-name {
        font-size: 0.73rem;
        color: #7d776c;
        font-weight: 600;
        display: block;
    }
    .sim-method-item.active .sim-method-name {
        color: #211313;
    }

    /* Action buttons section */
    .sim-actions-section {
        padding: 1.75rem 2rem 2rem;
    }
    .sim-actions-title {
        font-size: 0.78rem;
        color: #9c9587;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 600;
        margin-bottom: 1rem;
        text-align: center;
    }
    .sim-btn-group {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    .sim-btn {
        width: 100%;
        padding: 0.9rem 1rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .sim-btn:hover {
        transform: translateY(-1px);
    }

    /* Success */
    .sim-btn-success {
        background: #c39a53;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(195, 154, 83, 0.25);
    }
    .sim-btn-success:hover {
        background: #ae843c;
        box-shadow: 0 6px 20px rgba(195, 154, 83, 0.35);
    }

    /* Pending */
    .sim-btn-pending {
        background: transparent;
        color: #b08a42;
        border: 1px solid #b08a42;
    }
    .sim-btn-pending:hover {
        background: #fbf8f1;
        color: #8f6c2f;
        border-color: #8f6c2f;
    }

    /* Failed */
    .sim-btn-failed {
        background: transparent;
        color: #9c4040;
        border: 1px solid #d4a0a0;
    }
    .sim-btn-failed:hover {
        background: #fdf5f5;
        color: #7c2d2d;
        border-color: #c07070;
    }

    .sim-btn i {
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .sim-btn-content {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
    }
    .sim-btn-content .sim-btn-main {
        font-weight: 600;
        font-size: 0.9rem;
    }
    .sim-btn-content .sim-btn-sub {
        font-size: 0.72rem;
        opacity: 0.7;
        font-weight: 400;
        margin-top: 1px;
    }

    /* Processing overlay */
    .sim-processing-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(250, 247, 242, 0.92);
        backdrop-filter: blur(8px);
        z-index: 9999;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1.25rem;
    }
    .sim-processing-overlay.show {
        display: flex;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .sim-processing-card {
        background: #ffffff;
        border: 1px solid #eadfd6;
        border-radius: 20px;
        padding: 3rem 2.5rem;
        text-align: center;
        box-shadow: 0 15px 40px rgba(176, 138, 66, 0.08);
        max-width: 340px;
        width: 100%;
    }
    .sim-processing-spinner {
        width: 48px;
        height: 48px;
        border: 3px solid #eadfd6;
        border-top-color: #b08a42;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 1.25rem;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .sim-processing-text {
        color: #211313;
        font-size: 1.05rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .sim-processing-sub {
        color: #9c9587;
        font-size: 0.85rem;
    }

    /* Back link */
    .sim-back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #7d776c;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    .sim-back-link:hover {
        color: #b08a42;
        transform: translateX(-4px);
    }

    /* Footer info */
    .sim-footer {
        text-align: center;
        margin-top: 1.5rem;
        max-width: 520px;
    }
    .sim-footer-box {
        background: #fffbeb;
        border: 1px solid #f9edd0;
        border-radius: 12px;
        padding: 1rem 1.5rem;
    }
    .sim-footer-text {
        font-size: 0.75rem;
        color: #78350f;
        line-height: 1.6;
        margin: 0;
    }
    .sim-footer-text code {
        color: #92400e;
        background: rgba(217, 119, 6, 0.1);
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 0.7rem;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .simulator-card {
            margin: 0 1rem;
            border-radius: 16px;
        }
        .sim-header,
        .sim-amount-section,
        .sim-details-section,
        .sim-method-section,
        .sim-actions-section {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .sim-amount-value {
            font-size: 2rem;
        }
        .sim-method-grid {
            gap: 0.5rem;
        }
    }
</style>

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
