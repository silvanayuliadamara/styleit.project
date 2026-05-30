@extends('layouts.app', ['title' => $package->name . ' - Lisa Yuli Belti'])

@section('content')
<section class="page-hero compact">
    <div class="container">
        <a href="{{ route('layanan.kategori', $package->category->slug) }}" class="link-gold"><i class="bi bi-arrow-left"></i> Kembali ke {{ $package->category->name }}</a>
        <div class="row align-items-end g-4 mt-2">
            <div class="col-lg-8">
                <p class="hero-label">{{ $package->category->headline }}</p>
                <h1>{{ $package->name }}</h1>
                <p>{{ $package->description }}</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="price mb-1">Rp{{ number_format($package->price, 0, ',', '.') }}</div>
                <div class="dp">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }} · Sisa Rp{{ number_format($package->price - $package->dp_amount, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <form action="{{ route('customer.cart.store') }}" method="POST">
            @csrf
            <input type="hidden" name="package_id" value="{{ $package->id }}">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="glass-card mb-4">
                        <h3>Yang Termasuk</h3>
                        <div class="row g-3 mt-1">
                            @foreach($package->items as $item)
                                <div class="col-md-6"><div class="check-item"><i class="bi bi-check-circle-fill"></i>{{ $item->name }} {{ $item->quantity }}{{ $item->unit }}</div></div>
                            @endforeach
                        </div>
                    </div>

                    <div class="glass-card mb-4">
                        <div class="d-flex justify-content-between gap-3 flex-wrap">
                            <div>
                                <h3>Pilih Tanggal Booking</h3>
                                <p class="muted mb-0">Kalender 2 bulan ke depan. Regular maksimal {{ $package->quota_per_day }} customer/hari.</p>
                            </div>
                            <div class="calendar-legend"><span class="available"></span>Tersedia <span class="full"></span>Penuh <span class="blocked"></span>Diblokir</div>
                        </div>
                        <div class="calendar-grid mt-4">
                            @foreach($calendar as $date)
                                <label class="date-card {{ $date['status'] }}">
                                    <input type="radio" name="booking_date" value="{{ $date['date'] }}" {{ $date['status'] !== 'available' ? 'disabled' : '' }} {{ old('booking_date') === $date['date'] ? 'checked' : '' }}>
                                    <strong>{{ $date['day'] }}</strong>
                                    <span>{{ $date['month'] }}</span>
                                    <small>{{ $date['status'] === 'available' ? $date['remaining'].' slot' : ($date['status'] === 'full' ? 'Penuh' : 'Diblokir') }}</small>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="booking-panel sticky-lg-top">
                        <h3>Atur Booking</h3>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Softlens</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="softlens" id="softlensNo" value="0" checked>
                                <label class="btn btn-outline-dark rounded-pill" for="softlensNo">Tidak</label>
                                <input type="radio" class="btn-check" name="softlens" id="softlensYes" value="1">
                                <label class="btn btn-outline-dark rounded-pill" for="softlensYes">Ya</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Add-on Opsional</label>
                            @foreach($addons as $addon)
                                <label class="addon-row">
                                    <span><input type="checkbox" name="addons[]" value="{{ $addon->id }}" data-price="{{ $addon->price }}"> {{ $addon->name }}<small>{{ $addon->description }}</small></span>
                                    <strong>Rp{{ number_format($addon->price, 0, ',', '.') }}</strong>
                                </label>
                            @endforeach
                        </div>

                        <div class="total-box">
                            <div><span>Harga paket</span><strong data-base="{{ $package->price }}">Rp{{ number_format($package->price, 0, ',', '.') }}</strong></div>
                            <div><span>Add-on</span><strong id="addonTotal">Rp0</strong></div>
                            <div><span>DP checkout</span><strong>Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</strong></div>
                            <hr>
                            <div><span>Total layanan</span><strong id="grandTotal">Rp{{ number_format($package->price, 0, ',', '.') }}</strong></div>
                        </div>

                        <button type="submit" name="action" value="cart" class="btn-outline-custom w-100 text-center mt-3">Tambah Keranjang</button>
                        <button type="submit" name="action" value="checkout" class="btn-dark-custom w-100 text-center mt-3 border-0">Booking Sekarang</button>
                        <p class="muted small mt-2 mb-0">Mode preview: booking hanya tersimpan sementara di session, belum masuk database.</p>
                        <a href="https://wa.me/6281227545591?text=Halo%20admin%20LYB,%20saya%20mau%20tanya%20paket%20{{ urlencode($package->name) }}" target="_blank" class="btn-whatsapp w-100 mt-3"><i class="bi bi-whatsapp"></i> Tanya via WhatsApp</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
document.querySelectorAll('input[name="addons[]"]').forEach((checkbox) => {
    checkbox.addEventListener('change', () => {
        const base = Number(document.querySelector('[data-base]').dataset.base);
        const addonTotal = [...document.querySelectorAll('input[name="addons[]"]:checked')].reduce((sum, item) => sum + Number(item.dataset.price), 0);
        const formatter = new Intl.NumberFormat('id-ID');
        document.getElementById('addonTotal').textContent = 'Rp' + formatter.format(addonTotal);
        document.getElementById('grandTotal').textContent = 'Rp' + formatter.format(base + addonTotal);
    });
});
</script>
@endsection
