@extends('layouts.app', ['title' => 'Keranjang Booking - Lisa Yuli Belti'])

@section('content')
<style>
    :root {
        --lyb-gold: #b08a42;
        --lyb-gold-light: #fbf8f1;
        --lyb-gold-border: #eadfd6;
        --lyb-dark: #211313;
        --lyb-muted: #88746a;
    }

    body {
        background-color: #FAF6F0 !important;
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: Georgia, "Times New Roman", serif !important;
        color: var(--lyb-dark) !important;
    }

    .page-hero-compact {
        padding: 40px 0;
        background-color: var(--lyb-gold-light);
        border-bottom: 1px solid var(--lyb-gold-border);
        margin-bottom: 40px;
    }
    .page-hero-compact h1 {
        font-size: 2.5rem;
        margin-bottom: 8px;
        font-weight: 700;
    }
    .page-hero-compact p {
        color: var(--lyb-muted);
        font-size: 15px;
        margin-bottom: 0;
    }

    .cart-card {
        background: #fff;
        border: 1px solid var(--lyb-gold-border);
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 8px 25px rgba(33, 19, 19, 0.02);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        position: relative;
    }
    .cart-card:hover {
        box-shadow: 0 12px 30px rgba(33, 19, 19, 0.04);
        border-color: var(--lyb-gold);
    }

    .cart-checkbox-wrapper {
        padding-right: 16px;
        flex-shrink: 0;
    }
    .cart-item-checkbox {
        width: 22px;
        height: 22px;
        cursor: pointer;
        accent-color: var(--lyb-gold);
        border: 1px solid var(--lyb-gold-border);
        border-radius: 6px;
    }

    .cart-img-wrapper {
        width: 110px;
        height: 110px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--lyb-gold-border);
        flex-shrink: 0;
        margin-right: 20px;
    }
    .cart-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .cart-img-placeholder {
        width: 100%;
        height: 100%;
        background-color: var(--lyb-gold-light);
        color: var(--lyb-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .cart-details {
        flex-grow: 1;
        padding-right: 10px;
    }
    .cart-details .category-tag {
        color: var(--lyb-gold);
        text-transform: uppercase;
        font-weight: 700;
        font-size: 11px;
        letter-spacing: 1.2px;
        display: block;
        margin-bottom: 4px;
    }
    .cart-details h3 {
        font-family: Georgia, serif !important;
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--lyb-dark);
    }
    .cart-details .detail-text {
        margin-bottom: 4px;
        font-size: 13.5px;
        color: #5d524d;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .cart-details .detail-text i {
        color: var(--lyb-gold);
    }

    .cart-actions-price {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: space-between;
        height: 110px;
        flex-shrink: 0;
        min-width: 160px;
    }
    .cart-price-info {
        text-align: right;
    }
    .cart-price-info .price-label {
        font-size: 10px;
        color: var(--lyb-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }
    .cart-price-info .price-val {
        font-family: Georgia, serif !important;
        font-size: 18px;
        font-weight: 700;
        color: var(--lyb-dark);
        display: block;
        margin-top: 1px;
    }
    .cart-price-info .dp-val {
        font-size: 12.5px;
        font-weight: 600;
        color: #d4833b;
        display: block;
        margin-top: 1px;
    }

    .cart-button-row {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .btn-edit-cart {
        background: #fff;
        border: 1px solid var(--lyb-gold-border);
        color: var(--lyb-dark);
        font-size: 12px;
        font-weight: 700;
        border-radius: 30px;
        padding: 6px 18px;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-edit-cart:hover {
        background: var(--lyb-gold-light);
        border-color: var(--lyb-gold);
        color: var(--lyb-gold);
    }

    .btn-delete-cart {
        background: transparent;
        border: none;
        color: #d9534f;
        font-size: 18px;
        padding: 0;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-delete-cart:hover {
        color: #c9302c;
        transform: scale(1.15);
    }

    .summary-card {
        background: #fff;
        border: 1px solid var(--lyb-gold-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(33, 19, 19, 0.02);
        position: sticky;
        top: 100px;
    }
    .summary-card h3 {
        font-family: Georgia, serif !important;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--lyb-dark);
        border-bottom: 1px solid var(--lyb-gold-border);
        padding-bottom: 12px;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 14px;
        font-size: 14px;
        color: #615651;
    }
    .summary-row.total-row {
        border-top: 1px solid var(--lyb-gold-border);
        padding-top: 16px;
        margin-top: 16px;
        font-size: 16px;
        font-weight: 700;
        color: var(--lyb-dark);
    }
    .summary-row strong {
        color: var(--lyb-dark);
        font-family: Georgia, serif !important;
    }
    .btn-checkout {
        background: var(--lyb-dark);
        color: #fff;
        border: none;
        border-radius: 30px;
        padding: 12px;
        font-weight: 700;
        width: 100%;
        transition: all 0.3s ease;
        margin-top: 10px;
    }
    .btn-checkout:hover {
        background: #3d2525;
        box-shadow: 0 6px 15px rgba(33, 19, 19, 0.2);
    }
    .btn-checkout:disabled {
        background: #ccc;
        cursor: not-allowed;
        box-shadow: none;
    }

    .checkout-footer-text {
        font-size: 11px;
        color: var(--lyb-muted);
        text-align: center;
        margin-top: 12px;
        line-height: 1.4;
    }
</style>

<section class="page-hero-compact">
    <div class="container">
        <h1>Keranjang</h1>
        <p>{{ count($cart) }} item dalam keranjang Anda</p>
    </div>
</section>

<section class="mb-5 pb-5">
    <div class="container">
        @if(empty($cart))
            <div class="text-center py-5 bg-white rounded-4 border" style="border-color: var(--lyb-gold-border) !important;">
                <i class="bi bi-cart-x text-muted" style="font-size: 64px;"></i>
                <h3 class="mt-4">Keranjang masih kosong</h3>
                <p class="text-secondary small">Pilih layanan premium kami untuk mulai membuat booking.</p>
                <a href="{{ route('layanan.index') }}" class="btn px-4 py-2 mt-2 rounded-pill btn-edit-cart">Lihat Layanan</a>
            </div>
        @else
            <form action="{{ route('customer.cart.select') }}" method="POST" id="cartForm">
                @csrf
                <div class="row g-4">
                    {{-- Kolom Kiri: Items --}}
                    <div class="col-lg-8">
                        @php
                            $checkoutKeys = session('checkout_keys', []);
                            $isCheckedAll = empty($checkoutKeys);
                        @endphp
                        @foreach($cart as $item)
                            @php
                                $isChecked = $isCheckedAll || in_array($item['key'], $checkoutKeys);
                            @endphp
                            <div class="cart-card">
                                {{-- Checkbox --}}
                                <div class="cart-checkbox-wrapper">
                                    <input type="checkbox" name="selected_keys[]" value="{{ $item['key'] }}" class="cart-item-checkbox" 
                                           data-price="{{ $item['total_price'] }}" 
                                           data-dp="{{ $item['dp_amount'] }}" 
                                           data-remaining="{{ $item['remaining_payment'] }}"
                                           {{ $isChecked ? 'checked' : '' }}>
                                </div>

                                {{-- Image --}}
                                <div class="cart-img-wrapper">
                                    @if(!empty($item['package_image']))
                                        <img src="{{ asset('storage/' . $item['package_image']) }}" alt="{{ $item['package_name'] }}" class="cart-img">
                                    @else
                                        <div class="cart-img-placeholder">
                                            <i class="bi bi-stars"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Details --}}
                                <div class="cart-details">
                                    <span class="category-tag">{{ $item['category_name'] }}</span>
                                    <h3>{{ $item['package_name'] }}</h3>
                                    
                                    <span class="detail-text">
                                        <i class="bi bi-calendar-check"></i> 
                                        {{ \Illuminate\Support\Carbon::parse($item['booking_date'])->translatedFormat('l, d F Y') }}
                                        @if(!empty($item['slot_waktu']))
                                            · Slot: {{ ucfirst($item['slot_waktu']) }}
                                        @endif
                                    </span>
                                    
                                    <span class="detail-text">
                                        <i class="bi bi-eye"></i> Softlens: {{ $item['softlens'] ? 'Ya' : 'Tidak' }}
                                    </span>
                                    
                                    @if(!empty($item['tanggal_fitting']))
                                        <span class="detail-text">
                                            <i class="bi bi-scissors"></i> Tanggal Fitting: {{ \Illuminate\Support\Carbon::parse($item['tanggal_fitting'])->translatedFormat('d F Y') }}
                                        </span>
                                    @endif

                                    @if(count($item['addons']))
                                        <span class="detail-text">
                                            <i class="bi bi-plus-circle"></i> Add-on: {{ collect($item['addons'])->pluck('name')->join(', ') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Action & Price --}}
                                <div class="cart-actions-price">
                                    {{-- Delete Button --}}
                                    <button type="button" class="btn-delete-cart" onclick="confirmDelete('{{ $item['key'] }}')" title="Hapus dari keranjang">
                                        <i class="bi bi-trash3"></i>
                                    </button>

                                    {{-- Price Info & Edit Button --}}
                                    <div class="cart-price-info">
                                        <span class="price-label">Total Layanan</span>
                                        <span class="price-val">Rp{{ number_format($item['total_price'], 0, ',', '.') }}</span>
                                        <span class="dp-val">DP Rp{{ number_format($item['dp_amount'], 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <div class="cart-button-row">
                                        <a href="{{ route('paket.show', $item['package_code']) }}?edit_key={{ $item['key'] }}" class="btn-edit-cart">Ubah Detail</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Kolom Kanan: Ringkasan --}}
                    <div class="col-lg-4">
                        <div class="summary-card">
                            <h3>Ringkasan</h3>
                            
                            <div class="summary-row">
                                <span>Total Harga Layanan</span>
                                <strong id="summaryTotalHarga">Rp0</strong>
                            </div>
                            <div class="summary-row">
                                <span>DP yang Dibayar Sekarang</span>
                                <strong id="summaryTotalDp" style="color: #d4833b;">Rp0</strong>
                            </div>
                            <div class="summary-row total-row">
                                <span>Sisa Pelunasan</span>
                                <strong id="summaryTotalSisa">Rp0</strong>
                            </div>

                            <button type="submit" class="btn-checkout" id="checkoutBtn">Checkout</button>
                            
                            <p class="checkout-footer-text">
                                <i class="bi bi-shield-check"></i> DP diproses melalui payment gateway aman.
                            </p>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Hidden delete forms --}}
            @foreach($cart as $item)
                <form id="delete-form-{{ $item['key'] }}" action="{{ route('customer.cart.destroy', $item['key']) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
    </div>
</section>

<script>
function confirmDelete(key) {
    if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
        document.getElementById('delete-form-' + key).submit();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.cart-item-checkbox');
    const totalHargaEl = document.getElementById('summaryTotalHarga');
    const totalDpEl = document.getElementById('summaryTotalDp');
    const totalSisaEl = document.getElementById('summaryTotalSisa');
    const checkoutBtn = document.getElementById('checkoutBtn');

    if (checkboxes.length > 0) {
        function calculateSummary() {
            let totalHarga = 0;
            let totalDp = 0;
            let totalSisa = 0;
            let checkedCount = 0;

            checkboxes.forEach((cb) => {
                if (cb.checked) {
                    totalHarga += Number(cb.dataset.price);
                    totalDp += Number(cb.dataset.dp);
                    totalSisa += Number(cb.dataset.remaining);
                    checkedCount++;
                }
            });

            const formatter = new Intl.NumberFormat('id-ID');
            if (totalHargaEl) totalHargaEl.textContent = 'Rp' + formatter.format(totalHarga);
            if (totalDpEl) totalDpEl.textContent = 'Rp' + formatter.format(totalDp);
            if (totalSisaEl) totalSisaEl.textContent = 'Rp' + formatter.format(totalSisa);

            if (checkoutBtn) {
                if (checkedCount === 0) {
                    checkoutBtn.disabled = true;
                    checkoutBtn.textContent = 'Pilih Layanan Terlebih Dahulu';
                } else {
                    checkoutBtn.disabled = false;
                    checkoutBtn.textContent = 'Checkout';
                }
            }
        }

        checkboxes.forEach((cb) => {
            cb.addEventListener('change', calculateSummary);
        });

        // Run once on load
        calculateSummary();
    }
});
</script>
@endsection
