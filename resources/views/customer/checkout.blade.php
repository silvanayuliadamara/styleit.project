@extends('layouts.app', ['title' => 'Checkout Booking'])

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    body {
        background-color: #FAF7F2;
        font-family: 'Outfit', sans-serif;
    }
    .checkout-title {
        font-family: 'Outfit', sans-serif;
        color: #211313;
        font-weight: 500;
        margin-bottom: 2rem;
        font-size: 2.25rem;
    }
    .checkout-section {
        padding: 3rem 0 5rem 0;
    }
    .glass-card {
        background: #ffffff;
        border: 1px solid #eadfd6;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: none;
        margin-bottom: 2rem;
    }
    .glass-card h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.3rem;
        color: #211313;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    .form-label {
        font-family: 'Outfit', sans-serif;
        font-weight: 500;
        font-size: 0.95rem;
        color: #211313;
        margin-bottom: 0.5rem;
    }
    .form-control {
        border: 1px solid #eadfd6;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        background-color: #fff;
        color: #211313;
        font-family: 'Outfit', sans-serif;
    }
    .form-control:focus {
        border-color: #b08a42;
        box-shadow: 0 0 0 0.15rem rgba(176, 138, 66, 0.15);
    }
    .form-control:disabled {
        background-color: #f5efe4;
        border-color: #eadfd6;
        color: #6c757d;
    }
    .booking-item-card {
        background-color: #f7f4ed;
        border-radius: 16px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        border: none;
    }
    .booking-item-img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 12px;
        border: none;
    }
    .booking-item-info {
        flex-grow: 1;
    }
    .booking-item-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #211313;
        margin-bottom: 0.5rem;
    }
    .booking-item-meta {
        font-size: 0.85rem;
        color: #7d776c;
        margin-bottom: 0.25rem;
    }
    .booking-item-meta a {
        color: #b08a42;
        text-decoration: none;
        font-weight: 500;
    }
    .booking-item-meta a:hover {
        text-decoration: underline;
    }
    .booking-item-price-label {
        font-size: 0.8rem;
        color: #9c9587;
        text-align: right;
    }
    .booking-item-price-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #b08a42;
        text-align: right;
        margin-top: 0.25rem;
    }
    .payment-option {
        border: 1px solid #eadfd6;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        background-color: #fff;
        margin-bottom: 0.75rem;
    }
    .payment-option:hover {
        border-color: #b08a42;
    }
    .payment-option.active {
        border-color: #b08a42;
        background-color: #ffffff;
        box-shadow: none;
    }
    .payment-option input[type="radio"] {
        margin-right: 1.25rem;
        accent-color: #b08a42;
        width: 18px;
        height: 18px;
    }
    .payment-option-details {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        width: 100%;
    }
    .payment-option-details i {
        font-size: 1.5rem;
        color: #b08a42;
    }
    .payment-option-details .title {
        font-weight: 600;
        font-size: 1rem;
        color: #211313;
        display: block;
    }
    .payment-option-details .subtitle {
        font-size: 0.85rem;
        color: #7d776c;
        display: block;
        margin-top: 0.15rem;
    }
    .summary-card {
        background: #ffffff;
        border: 1px solid #eadfd6;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: none;
        position: sticky;
        top: 8.5rem !important;
    }
    .summary-card h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.3rem;
        color: #211313;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        color: #4f4f4f;
        align-items: center;
    }
    .summary-row.total-row {
        border-top: 1px dashed #eadfd6;
        padding-top: 0.75rem;
        margin-top: 0.75rem;
        font-weight: 600;
    }
    .status-alert-box {
        background-color: #fffbeb;
        border: 1px solid #f9edd0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        margin: 1.25rem 0;
    }
    .btn-pay-dp {
        background-color: #c39a53;
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        padding: 0.9rem;
        width: 100%;
        font-size: 1.05rem;
        transition: all 0.2s ease-in-out;
    }
    .btn-pay-dp:hover {
        background-color: #ae843c;
        color: #fff;
    }
    .btn-pay-dp:disabled {
        background-color: #c4beab;
        cursor: not-allowed;
    }
    .checkout-footer-text {
        font-size: 0.75rem;
        color: #9c9587;
        text-align: center;
        margin-top: 1rem;
        line-height: 1.4;
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

<section class="checkout-section">
    <div class="container">
        <a href="{{ route('customer.cart.index') }}" class="text-decoration-none d-inline-flex align-items-center gap-2 back-link mb-4" style="font-size: 14px; font-weight: 500; color: #7d776c;">
            <i class="bi bi-arrow-left"></i> Kembali ke Keranjang
        </a>
        <h1 class="checkout-title">Checkout</h1>
        
        <form id="checkoutForm" action="{{ route('customer.checkout.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                {{-- Kolom Kiri --}}
                <div class="col-lg-8">
                    {{-- 1. Data Pemesanan --}}
                    <div class="glass-card">
                        <h3>1. Data Pemesanan</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Pemesan</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" required placeholder="0823-1122-3344">
                            </div>

                            @if($needsAddress)
                                <div class="col-12">
                                    <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                    <textarea name="address" class="form-control" rows="3" placeholder="Alamat lengkap untuk konfirmasi" required>{{ old('address', auth()->user()->address) }}</textarea>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <label class="form-label">Username Instagram <span class="text-danger">*</span></label>
                                <input type="text" name="instagram" class="form-control" value="{{ old('instagram', auth()->user()->instagram) }}" required placeholder="@username_ig">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Catatan (opsional)</label>
                                <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="Permintaan khusus">
                            </div>
                        </div>
                    </div>

                    {{-- 2. Layanan Dipesan --}}
                    <div class="glass-card">
                        <h3>2. Layanan Dipesan</h3>
                        <div class="d-flex flex-column gap-3">
                            @foreach($cart as $item)
                                <div class="booking-item-card">
                                    <img src="{{ $item['package_image'] ? asset('storage/' . $item['package_image']) : asset('images/categories/wedding/cover.jpeg') }}" class="booking-item-img" alt="{{ $item['package_name'] }}">
                                    <div class="booking-item-info">
                                        <div class="booking-item-title">{{ $item['package_name'] }}</div>
                                        <div class="booking-item-meta">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            {{ \Carbon\Carbon::parse($item['booking_date'])->translatedFormat('l, d F Y') }}
                                            <a href="{{ route('customer.cart.index') }}" class="ms-2 text-decoration-none" style="color: #b08a42; font-size: 0.85rem;">Ubah Tanggal</a>
                                        </div>
                                        <div class="booking-item-meta">
                                            Softlens: <span class="fw-semibold">{{ $item['softlens'] ? 'Ya' : 'Tidak' }}</span>
                                            @if(!empty($item['slot_waktu']))
                                                · Slot: <span class="fw-semibold text-capitalize">{{ $item['slot_waktu'] }}</span>
                                            @endif
                                        </div>
                                        @if(!empty($item['addons']))
                                            <div class="booking-item-meta text-muted">
                                                Add-on: {{ collect($item['addons'])->pluck('name')->join(', ') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <div class="booking-item-price-label">Harga</div>
                                        <div class="booking-item-price-value">Rp{{ number_format($item['total_price'], 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 3. Metode Pembayaran DP --}}
                    <div class="glass-card">
                        <h3>3. Metode Pembayaran DP</h3>
                        <p class="text-muted small">Pembayaran DP diproses melalui payment gateway. Dana akan diteruskan ke akun merchant owner.</p>
                        
                        <div class="payment-options d-flex flex-column gap-2 mt-3">
                            <label class="payment-option active" id="option1">
                                <input type="radio" name="payment_method" value="va" checked class="me-3" onclick="togglePaymentActive('option1')">
                                <div class="payment-option-details">
                                    <i class="bi bi-bank"></i>
                                    <div>
                                        <span class="title">Virtual Account</span>
                                        <span class="subtitle">BCA / Mandiri / BNI / BRI</span>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="payment-option" id="option2">
                                <input type="radio" name="payment_method" value="qris" class="me-3" onclick="togglePaymentActive('option2')">
                                <div class="payment-option-details">
                                    <i class="bi bi-qr-code-scan"></i>
                                    <div>
                                        <span class="title">QRIS</span>
                                        <span class="subtitle">Bayar via aplikasi e-wallet apa saja</span>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="payment-option" id="option3">
                                <input type="radio" name="payment_method" value="wallet" class="me-3" onclick="togglePaymentActive('option3')">
                                <div class="payment-option-details">
                                    <i class="bi bi-wallet2"></i>
                                    <div>
                                        <span class="title">E-Wallet</span>
                                        <span class="subtitle">OVO / GoPay / DANA / ShopeePay</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan (Sticky Summary) --}}
                <div class="col-lg-4">
                    <div class="summary-card">
                        <h3>4. Ringkasan Pembayaran</h3>
                        
                        <div class="summary-row">
                            <span>Total Harga Layanan</span>
                            <strong>Rp{{ number_format(collect($cart)->sum('total_price'), 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>DP Dibayar Sekarang</span>
                            <strong style="color: #b08a42;">Rp{{ number_format(collect($cart)->sum('dp_amount'), 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-row total-row">
                            <span>Sisa Pelunasan</span>
                            <strong>Rp{{ number_format(collect($cart)->sum('remaining_payment'), 0, ',', '.') }}</strong>
                        </div>

                        <div class="status-alert-box">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold"><i class="bi bi-clock-history me-1"></i> Status:</span>
                                <span class="fw-bold" style="color: #b08a42;">Menunggu Pembayaran DP</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Batas Pembayaran:</span>
                                <span class="fw-bold text-danger">1 jam</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-pay-dp" id="payBtn">
                            Bayar DP Rp{{ number_format(collect($cart)->sum('dp_amount'), 0, ',', '.') }}
                        </button>
                        
                        <p class="checkout-footer-text">
                            Dengan menekan tombol Anda menyetujui ketentuan booking.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

{{-- Midtrans Snap JS (production only) --}}
@if(config('midtrans.is_production'))
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif
<script>

function togglePaymentActive(selectedId) {
    document.querySelectorAll('.payment-option').forEach(el => {
        el.classList.remove('active');
    });
    document.getElementById(selectedId).classList.add('active');
}

document.addEventListener('DOMContentLoaded', () => {
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const payBtn = document.getElementById('payBtn');
            const originalText = payBtn.innerHTML;
            
            // Set loading state on button
            payBtn.disabled = true;
            payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';

            const formData = new FormData(checkoutForm);
            
            fetch("{{ route('customer.checkout.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.sandbox_mode && data.sandbox_url) {
                    // Sandbox mode: redirect to sandbox simulator instead of Snap popup
                    window.location.href = data.sandbox_url;
                } else if (data.success && data.snap_token) {
                    // Production mode: Open Midtrans Snap popup immediately after checkout
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            // Confirm payment in our DB (fallback for localhost where webhook can't reach)
                            // Extract booking_code from redirect_url
                            const urlParts = data.redirect_url.split('/pembayaran/');
                            const bookingCode = urlParts[1] ? urlParts[1].replace(/\/$/, '') : '';
                            
                            fetch(`/pembayaran/${bookingCode}/confirm`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    payment_type: result.payment_type || 'midtrans',
                                    transaction_id: result.transaction_id || null,
                                    booking_codes: data.booking_codes || []
                                })
                            }).then(() => {
                                window.location.href = `/pembayaran/${bookingCode}/berhasil`;
                            }).catch(() => {
                                // Even if confirm fails, still redirect to success
                                window.location.href = `/pembayaran/${bookingCode}/berhasil`;
                            });
                        },
                        onPending: function(result) {
                            // Redirect to payment instruction page to wait
                            window.location.href = data.redirect_url;
                        },
                        onError: function(result) {
                            alert("Pembayaran gagal! Silakan coba lagi.");
                            payBtn.disabled = false;
                            payBtn.innerHTML = originalText;
                        },
                        onClose: function() {
                            // User closed the popup without completing payment
                            // Redirect to payment instruction so they can retry
                            window.location.href = data.redirect_url;
                        }
                    });
                } else if (data.success && data.redirect_url) {
                    // Fallback: redirect to payment instruction
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.message || "Terjadi kesalahan.");
                    payBtn.disabled = false;
                    payBtn.innerHTML = originalText;
                }
            })
            .catch(err => {
                console.error(err);
                let errorMsg = "Terjadi kesalahan pada server.";
                if (err.errors) {
                    errorMsg = Object.values(err.errors).flat().join("\n");
                } else if (err.message) {
                    errorMsg = err.message;
                }
                alert(errorMsg);
                payBtn.disabled = false;
                payBtn.innerHTML = originalText;
            });
        });
    }
});
</script>
@endsection
