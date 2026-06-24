@extends('layouts.app', ['title' => 'Instruksi Pembayaran'])

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    body {
        background-color: #FAF7F2;
        font-family: 'Outfit', sans-serif;
    }
    .payment-instruction-section {
        padding: 4rem 0 6rem 0;
    }
    .instruction-kicker {
        font-size: 0.85rem;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #b08a42;
        font-weight: 700;
        text-align: center;
        margin-bottom: 0.75rem;
        display: block;
    }
    .instruction-title {
        font-size: 2.25rem;
        font-weight: 500;
        color: #211313;
        text-align: center;
        margin-bottom: 0.25rem;
    }
    .instruction-subtitle {
        font-size: 1rem;
        color: #7d776c;
        text-align: center;
        margin-bottom: 3rem;
    }
    
    /* Layout Kartu Pembayaran */
    .instruction-card {
        max-width: 650px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #eadfd6;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(176, 138, 66, 0.03);
    }
    .instruction-card-header {
        background-color: #211313;
        color: #efe2d5;
        padding: 1.5rem 2rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .payment-method-badge {
        font-size: 0.75rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-weight: 700;
        color: #eadfd6;
    }
    .status-badge {
        background-color: rgba(176, 138, 66, 0.2);
        border: 1px solid #b08a42;
        color: #dfba73;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 4px 14px;
        border-radius: 20px;
    }
    .header-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .instruction-card-body {
        padding: 2.5rem 2rem;
    }
    .info-label {
        font-size: 0.9rem;
        color: #9c9587;
        margin-bottom: 0.5rem;
    }
    .nominal-val {
        font-size: 2.25rem;
        font-weight: 700;
        color: #211313;
        margin-bottom: 2rem;
    }
    
    /* Input & Copy Area */
    .va-copy-box {
        display: flex;
        align-items: center;
        background-color: #f7f4ed;
        border: 1px solid #eadfd6;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 2rem;
    }
    .va-number {
        font-size: 1.35rem;
        font-weight: 700;
        color: #211313;
        flex-grow: 1;
        letter-spacing: 1px;
    }
    .btn-copy-va {
        background: transparent;
        border: none;
        color: #b08a42;
        font-size: 1.25rem;
        cursor: pointer;
        padding: 0;
        transition: color 0.2s ease;
    }
    .btn-copy-va:hover {
        color: #8f6c2f;
    }

    /* E-Wallet Phone Input Area */
    .ewallet-input-box {
        margin-bottom: 2rem;
    }
    .ewallet-logos {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
        align-items: center;
    }
    .ewallet-logo {
        height: 24px;
        object-fit: contain;
        filter: grayscale(20%);
    }
    
    /* Countdown Timer */
    .timer-box {
        background-color: #fffbeb;
        border: 1px solid #f9edd0;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        margin-bottom: 2rem;
    }
    .timer-label {
        font-size: 0.85rem;
        color: #8a6d3b;
        margin-bottom: 0.25rem;
    }
    .timer-val {
        font-size: 2rem;
        font-weight: 700;
        color: #b08a42;
        font-family: Georgia, serif;
    }
    
    /* Status Auto Conf */
    .status-loader-box {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        color: #9c9587;
        margin-bottom: 2.5rem;
    }
    .spinner-custom {
        width: 1rem;
        height: 1rem;
        border: 2px solid #b08a42;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spin 0.75s linear infinite;
    }
    
    /* Step Collapses / Accordions */
    .instruction-steps h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #211313;
        margin-bottom: 1rem;
    }
    .accordion-item-custom {
        border-bottom: 1px solid #eadfd6;
        padding: 1rem 0;
    }
    .accordion-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.95rem;
        color: #211313;
    }
    .accordion-body-custom {
        padding-top: 0.75rem;
        font-size: 0.875rem;
        color: #655c56;
        line-height: 1.6;
        display: none;
    }
    .accordion-body-custom ol {
        padding-left: 1.25rem;
        margin-bottom: 0;
    }
    .accordion-body-custom li {
        margin-bottom: 0.5rem;
    }
    
    /* Bottom Actions */
    .instruction-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-top: 2.5rem;
    }
    .btn-reopen-snap {
        background-color: #c39a53;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 0.9rem;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        transition: all 0.2s ease;
        text-align: center;
        text-decoration: none;
    }
    .btn-reopen-snap:hover {
        background-color: #ae843c;
        color: #ffffff;
    }
    .btn-check-status {
        background-color: transparent;
        color: #b08a42;
        border: 1px solid #b08a42;
        border-radius: 12px;
        padding: 0.9rem;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        transition: all 0.2s ease;
        text-align: center;
        text-decoration: none;
    }
    .btn-check-status:hover {
        background-color: #fbf8f1;
        color: #8f6c2f;
        border-color: #8f6c2f;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .back-link {
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .back-link:hover {
        color: #b08a42 !important;
        transform: translateX(-4px);
    }
</style>

<section class="payment-instruction-section">
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('customer.dashboard') }}" class="text-decoration-none d-inline-flex align-items-center gap-2 back-link" style="font-size: 14px; font-weight: 500; color: #7d776c;">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
        <span class="instruction-kicker">Instruksi Pembayaran</span>
        <h1 class="instruction-title">Selesaikan Pembayaran DP</h1>
        <div class="instruction-subtitle">Kode Booking: <strong>{{ $booking->booking_code }}</strong></div>
        
        <div class="instruction-card">
            <div class="instruction-card-header">
                <div class="header-top">
                    <span class="payment-method-badge">
                        @if($method === 'Virtual Account')
                            VIRTUAL ACCOUNT
                        @elseif($method === 'QRIS')
                            QRIS
                        @else
                            E-WALLET
                        @endif
                    </span>
                    <span class="status-badge">Pending</span>
                </div>
                <h4 class="header-title">Menunggu Pembayaran DP</h4>
            </div>
            
            <div class="instruction-card-body">
                {{-- 1. Nominal --}}
                <div class="info-label">Nominal yang harus dibayar</div>
                <div class="nominal-val">Rp{{ number_format($booking->dp_amount, 0, ',', '.') }}</div>
                
                {{-- 2. Detail Pembayaran sesuai Metode --}}
                @if($method === 'Virtual Account')
                    <div class="info-label">Nomor Virtual Account</div>
                    <div class="va-copy-box">
                        <span class="va-number" id="vaNum">{{ $vaNumber }}</span>
                        <button class="btn-copy-va" onclick="copyVa()" title="Salin nomor VA">
                            <i class="bi bi-copy"></i>
                        </button>
                    </div>
                @elseif($method === 'QRIS')
                    <div class="text-center mb-4">
                        <div class="p-3 bg-white border rounded-4 d-inline-block shadow-sm">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=LYB-BOOKING-{{ $booking->booking_code }}" alt="QRIS QR Code" style="width: 160px; height: 160px; display: block;">
                        </div>
                        <p class="text-muted small mt-2">Scan QR ini dengan aplikasi e-wallet Anda</p>
                    </div>
                @else
                    {{-- E-Wallet (OVO / GoPay / DANA / ShopeePay) --}}
                    <div class="ewallet-input-box">
                        <div class="info-label">Pilihan E-Wallet</div>
                        <div class="ewallet-logos">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_sweat.png" class="ewallet-logo" alt="OVO" onerror="this.src='https://placehold.co/60x20?text=OVO'">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg" class="ewallet-logo" alt="GoPay" onerror="this.src='https://placehold.co/60x20?text=GoPay'">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Logo_dana_blue.svg" class="ewallet-logo" alt="DANA" onerror="this.src='https://placehold.co/60x20?text=DANA'">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/f/fe/ShopeePay_logo.svg" class="ewallet-logo" alt="ShopeePay" onerror="this.src='https://placehold.co/60x20?text=ShopeePay'">
                        </div>
                        
                        <div class="info-label">Masukkan Nomor HP E-Wallet</div>
                        <div class="input-group mb-3">
                            <input type="text" id="walletPhone" class="form-control rounded-4-left border-end-0" placeholder="Contoh: 08123456789" value="{{ auth()->user()->phone }}">
                            <button class="btn btn-dark-custom rounded-4-right px-4" type="button" onclick="requestEwalletPayment()" style="background-color: #b08a42; border-color: #b08a42; color: #fff; font-weight: 600;">Bayar Sekarang</button>
                        </div>
                        <div id="walletAlert" class="alert alert-success d-none rounded-3 py-2 small" role="alert"></div>
                    </div>
                @endif
                
                {{-- 3. Countdown Timer --}}
                <div class="timer-box">
                    <div class="timer-label">Selesaikan dalam</div>
                    <div class="timer-val" id="countdown">59:59</div>
                </div>
                
                {{-- 4. Status Loading --}}
                <div class="status-loader-box">
                    <div class="spinner-custom"></div>
                    <span>Menunggu konfirmasi otomatis dari payment gateway...</span>
                </div>
                
                {{-- 5. Panduan / Cara Pembayaran --}}
                <div class="instruction-steps">
                    <h4>Panduan Pembayaran</h4>
                    
                    @if($method === 'Virtual Account')
                        <div class="accordion-item-custom">
                            <div class="accordion-header-custom" onclick="toggleAccordion('step1', this)">
                                <span>ATM BCA</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <div class="accordion-body-custom" id="step1">
                                <ol>
                                    <li>Masukkan kartu ATM BCA & PIN.</li>
                                    <li>Pilih menu **Transaksi Lainnya** > **Transfer** > **Ke Rek BCA Virtual Account**.</li>
                                    <li>Masukkan nomor Virtual Account: <strong>{{ $vaNumber }}</strong>.</li>
                                    <li>Periksa detail tagihan, masukkan nominal bayar, lalu konfirmasi transaksi.</li>
                                </ol>
                            </div>
                        </div>
                        <div class="accordion-item-custom">
                            <div class="accordion-header-custom" onclick="toggleAccordion('step2', this)">
                                <span>Mobile Banking BCA (m-BCA)</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <div class="accordion-body-custom" id="step2">
                                <ol>
                                    <li>Buka aplikasi m-BCA dan masukkan kode akses Anda.</li>
                                    <li>Pilih menu **m-Transfer** > **BCA Virtual Account**.</li>
                                    <li>Masukkan nomor Virtual Account: <strong>{{ $vaNumber }}</strong>.</li>
                                    <li>Masukkan nominal pembayaran (jika diminta), konfirmasi rincian tagihan, lalu masukkan PIN m-BCA Anda.</li>
                                </ol>
                            </div>
                        </div>
                        <div class="accordion-item-custom">
                            <div class="accordion-header-custom" onclick="toggleAccordion('step3', this)">
                                <span>ATM Mandiri / Bank Lain</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <div class="accordion-body-custom" id="step3">
                                <ol>
                                    <li>Masukkan kartu ATM dan PIN.</li>
                                    <li>Pilih menu **Transfer** > **Ke Rekening Bank Lain** atau **Transfer Virtual Account**.</li>
                                    <li>Masukkan kode Bank BCA (014) diikuti nomor VA: <strong>014{{ $vaNumber }}</strong>.</li>
                                    <li>Konfirmasi nama merchant dan nominal, lalu selesaikan pembayaran.</li>
                                </ol>
                            </div>
                        </div>
                    @elseif($method === 'QRIS')
                        <div class="accordion-item-custom">
                            <div class="accordion-header-custom" onclick="toggleAccordion('qstep1', this)">
                                <span>Gopay / OVO / DANA / ShopeePay</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <div class="accordion-body-custom" id="qstep1">
                                <ol>
                                    <li>Buka aplikasi e-wallet Anda (GoPay, OVO, DANA, ShopeePay, atau aplikasi m-banking).</li>
                                    <li>Pilih menu **Scan** atau **Bayar (QR)**.</li>
                                    <li>Arahkan kamera ke kode QR yang tertera pada layar, atau upload screenshot kode QR jika Anda membuka via HP.</li>
                                    <li>Rincian pembayaran akan muncul secara otomatis. Periksa detailnya, lalu tekan **Bayar** dan masukkan PIN e-wallet Anda.</li>
                                </ol>
                            </div>
                        </div>
                    @else
                        {{-- E-Wallet --}}
                        <div class="accordion-item-custom">
                            <div class="accordion-header-custom" onclick="toggleAccordion('ewstep1', this)">
                                <span>OVO / DANA / GoPay Push Notification</span>
                                <i class="bi bi-chevron-down"></i>
                            </div>
                            <div class="accordion-body-custom" id="ewstep1">
                                <ol>
                                    <li>Masukkan nomor handphone yang aktif dan terdaftar pada aplikasi e-wallet Anda di kolom input di atas.</li>
                                    <li>Tekan tombol **Bayar Sekarang**.</li>
                                    <li>Sistem akan mengirimkan permintaan pembayaran langsung ke aplikasi e-wallet Anda.</li>
                                    <li>Buka aplikasi e-wallet Anda (atau klik push notifikasi yang muncul), periksa tagihan masuk, lalu selesaikan pembayaran menggunakan PIN Anda.</li>
                                </ol>
                            </div>
                        </div>
                    @endif
                </div>
                
                {{-- 6. Aksi Penutup --}}
                <div class="instruction-actions">
                    <button class="btn-reopen-snap" id="btnOpenSnap">Bayar Sekarang (Buka Snap Gateway)</button>
                    <a href="{{ route('customer.bookings.index') }}" class="btn-check-status">Cek Riwayat Booking</a>
                </div>

                @if(config('app.debug') || !config('midtrans.is_production'))
                    <div class="mt-4 p-3 border border-warning rounded-4 bg-light text-center">
                        <h6 class="text-warning fw-bold mb-2"><i class="bi bi-bug-fill"></i> Local Developer Sandbox Helper</h6>
                        <p class="small text-muted mb-3" style="font-size: 0.8rem; line-height: 1.4;">Karena localhost tidak dapat menerima webhook dari server Midtrans di internet, klik tombol di bawah untuk mensimulasikan webhook POST secara lokal.</p>
                        <button type="button" class="btn btn-warning btn-sm w-100 fw-bold py-2" onclick="simulateWebhookPayment(event)" style="border-radius: 10px; color: #211313;">
                            <i class="bi bi-send-fill"></i> Simulasikan Webhook Bayar Berhasil (POST)
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Midtrans Snap Integration --}}
@if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif
<script>

    // Copy VA Function
    function copyVa() {
        const textToCopy = document.getElementById('vaNum').innerText;
        navigator.clipboard.writeText(textToCopy).then(() => {
            alert('Nomor Virtual Account berhasil disalin!');
        }).catch(err => {
            console.error('Gagal menyalin: ', err);
        });
    }

    // Toggle Accordion Function
    function toggleAccordion(id, el) {
        const body = document.getElementById(id);
        const icon = el.querySelector('i');
        if (body.style.display === 'block') {
            body.style.display = 'none';
            icon.className = 'bi bi-chevron-down';
        } else {
            body.style.display = 'block';
            icon.className = 'bi bi-chevron-up';
        }
    }
    
    // Simulate E-Wallet Payment Request
    function requestEwalletPayment() {
        const phone = document.getElementById('walletPhone').value;
        const alertBox = document.getElementById('walletAlert');
        if (!phone) {
            alert('Masukkan nomor HP terlebih dahulu.');
            return;
        }
        alertBox.className = 'alert alert-success d-block';
        alertBox.innerText = 'Permintaan pembayaran sebesar Rp{{ number_format($booking->dp_amount, 0, ",", ".") }} telah dikirim ke nomor ' + phone + '. Silakan konfirmasi di aplikasi e-wallet Anda.';
        
        // Auto trigger snap for real payment gateway process
        triggerSnapPayment();
    }

    // Countdown Timer Logic
    const createdAt = {{ $booking->created_at->timestamp }};
    const expiresAt = createdAt + 3600; // 1 hour expiration
    
    function startTimer() {
        const countdownEl = document.getElementById('countdown');
        const timerInterval = setInterval(() => {
            const now = Math.floor(Date.now() / 1000);
            const remaining = expiresAt - now;
            
            if (remaining <= 0) {
                clearInterval(timerInterval);
                countdownEl.innerText = 'Waktu Pembayaran Habis';
                countdownEl.style.color = '#d9534f';
                return;
            }
            
            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            
            const formattedMinutes = String(minutes).padStart(2, '0');
            const formattedSeconds = String(seconds).padStart(2, '0');
            
            countdownEl.innerText = `${formattedMinutes}:${formattedSeconds}`;
        }, 1000);
    }

    // Launch Midtrans Snap Popup
    function triggerSnapPayment() {
        const token = "{{ $snapToken }}";
        if (token) {
            window.snap.pay(token, {
                onSuccess: function(result) {
                    // Confirm payment status in our DB (fallback for localhost where webhook can't reach)
                    fetch("{{ route('customer.payment.confirm', $booking->booking_code) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            payment_type: result.payment_type || 'midtrans',
                            transaction_id: result.transaction_id || null
                        })
                    }).then(() => {
                        window.location.href = "{{ route('customer.payment.success', $booking->booking_code) }}";
                    }).catch(() => {
                        // Even if confirm fails, still redirect
                        window.location.href = "{{ route('customer.payment.success', $booking->booking_code) }}";
                    });
                },
                onPending: function(result) {
                    console.log("Pembayaran pending...");
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                },
                onClose: function() {
                    console.log("Pop-up pembayaran ditutup.");
                }
            });
        }
    }

    // Sandbox webhook simulation function
    function simulateWebhookPayment(event) {
        if (!confirm('Simulasikan pembayaran sukses via POST ke localhost?')) return;
        
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';

        fetch("{{ route('customer.payment.confirm', $booking->booking_code) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                payment_type: 'qris',
                transaction_id: 'dummy-simulation-123456'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Server returned ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            alert('Simulasi pembayaran berhasil dikonfirmasi! Halaman akan otomatis beralih ke halaman sukses dalam beberapa detik.');
            // Polling interval will automatically detect the DB change and redirect the page.
        })
        .catch(error => {
            console.error('Error simulating payment:', error);
            alert('Gagal mengirim simulasi pembayaran: ' + error.message);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        startTimer();
        
        // Auto trigger snap on page load
        setTimeout(triggerSnapPayment, 800);
        
        // Reopen snap on click
        document.getElementById('btnOpenSnap').addEventListener('click', triggerSnapPayment);

        // Polling simulation: check if payment status has changed to accepted
        // In real app, webhook will update booking status, and we check here every 5 seconds.
        const bookingCode = "{{ $booking->booking_code }}";
        const checkInterval = setInterval(() => {
            fetch(`/customer/bookings/${bookingCode}`)
                .then(r => r.text())
                .then(html => {
                    // Simple check if the booking details show accepted status
                    // Or retrieve a small JSON status check endpoint.
                    // For demo/simplicity, if we request booking detail and find "diterima" or "dp_diterima":
                    if (html.includes('diterima') || html.includes('dp_diterima') || html.includes('LUNAS')) {
                        clearInterval(checkInterval);
                        window.location.href = "{{ route('customer.payment.success', $booking->booking_code) }}";
                    }
                })
                .catch(e => console.error(e));
        }, 5000);
    });
</script>
@endsection
