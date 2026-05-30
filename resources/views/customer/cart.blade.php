@extends('layouts.app', ['title' => 'Keranjang Booking'])

@section('content')
<section class="page-hero compact"><div class="container"><p class="hero-label">KERANJANG</p><h1>Keranjang Booking</h1><p>Periksa paket yang akan kamu checkout.</p></div></section>
<section class="section-padding">
    <div class="container">
        @if(empty($cart))
            <div class="glass-card text-center"><h3>Keranjang masih kosong</h3><p class="muted">Pilih layanan untuk mulai booking.</p><a href="{{ route('layanan.index') }}" class="btn-dark-custom">Lihat Layanan</a></div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    @foreach($cart as $item)
                        <div class="cart-item">
                            <div>
                                <small>{{ $item['category_name'] }}</small>
                                <h3>{{ $item['package_name'] }}</h3>
                                <p>Tanggal: {{ \Illuminate\Support\Carbon::parse($item['booking_date'])->format('d M Y') }} · Softlens: {{ $item['softlens'] ? 'Ya' : 'Tidak' }}</p>
                                @if(count($item['addons']))
                                    <p>Add-on: {{ collect($item['addons'])->pluck('name')->join(', ') }}</p>
                                @endif
                            </div>
                            <div class="text-lg-end">
                                <strong>Rp{{ number_format($item['total_price'], 0, ',', '.') }}</strong>
                                <form action="{{ route('customer.cart.destroy', $item['key']) }}" method="POST" class="mt-2">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button></form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-4">
                    <div class="booking-panel">
                        <h3>Ringkasan</h3>
                        <div class="total-box">
                            <div><span>Total layanan</span><strong>Rp{{ number_format(collect($cart)->sum('total_price'), 0, ',', '.') }}</strong></div>
                            <div><span>Total DP</span><strong>Rp{{ number_format(collect($cart)->sum('dp_amount'), 0, ',', '.') }}</strong></div>
                        </div>
                        <a href="{{ route('customer.checkout.index') }}" class="btn-dark-custom w-100 text-center mt-3">Lanjut Checkout</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
