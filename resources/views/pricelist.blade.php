@extends('layouts.app')

@section('title', 'Pricelist')

@section('content')
{{-- Hero Header Section --}}
<section class="pricelist-hero">
    <div class="container text-center">
        <div class="pricelist-hero-label">
            <span class="pricelist-hero-line"></span>
            <span>PRICELIST</span>
            <span class="pricelist-hero-line"></span>
        </div>
        <h1 class="pricelist-hero-title">Daftar Harga</h1>
    </div>
</section>

{{-- Price Categories --}}
<section class="pricelist-body">
    <div class="container pricelist-container">

        @foreach ($categories as $category)
            @if ($category->packages->isNotEmpty())
                <div class="pricelist-category scroll-reveal">
                    @if ($category->headline)
                        <p class="pricelist-kicker">{{ strtoupper($category->headline) }}</p>
                    @endif

                    <h2 class="pricelist-category-title">{{ $category->name }}</h2>
                    <div class="pricelist-divider"></div>

                    {{-- Brosur Manual untuk kategori ini --}}
                    @php
                        $brochures = [];
                        if ($category->slug === 'baju') {
                            $brochures = [
                                ['url' => asset('storage/packages/attire_packages.jpg'), 'title' => 'Buku Paket Attire'],
                                ['url' => asset('storage/packages/attire_items.jpg'), 'title' => 'Brosur Satuan Attire (Premium & Luxury)']
                            ];
                        } elseif ($category->slug === 'wedding') {
                            $brochures = [
                                ['url' => asset('storage/packages/wedding_premium.jpg'), 'title' => 'Brosur Wedding Premium'],
                                ['url' => asset('storage/packages/wedding_luxury.jpg'), 'title' => 'Brosur Wedding Luxury'],
                                ['url' => asset('storage/packages/makeup_wedding.jpg'), 'title' => 'Brosur Jasa Makeup Wedding']
                            ];
                        }
                    @endphp

                    @if (!empty($brochures))
                        <div class="row mb-5 g-3 justify-content-center">
                            <div class="col-12"><p class="small text-muted mb-2"><i class="bi bi-book-half"></i> Brosur Cetak / Buku Paket Resmi:</p></div>
                            @foreach ($brochures as $b)
                                <div class="col-6 col-sm-4 col-md-3 text-center">
                                    <div class="position-relative overflow-hidden rounded-4 shadow-sm" style="background: #fff; cursor: pointer; border: 1px solid #eadfd6 !important; border-radius: 16px !important;">
                                        <a href="{{ $b['url'] }}" target="_blank" title="Klik untuk memperbesar {{ $b['title'] }}">
                                            <img src="{{ $b['url'] }}" alt="{{ $b['title'] }}" class="w-100 brochure-card-img" style="transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); display: block;">
                                        </a>
                                    </div>
                                    <span class="d-block small fw-bold text-secondary mt-2" style="font-size: 11px;">{{ $b['title'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @foreach ($category->packages as $package)
                        <div class="pricelist-card scroll-reveal">
                            <div class="pricelist-card-info">
                                <h5 class="pricelist-card-name">{{ $package->name }}</h5>
                                <p class="pricelist-card-desc">{{ $package->description }}</p>

                                @if ($package->items->isNotEmpty())
                                    <small class="pricelist-card-includes">Termasuk: 
                                        {{ $package->items->map(fn($it) => $it->name . ' (' . $it->quantity . ' ' . $it->unit . ')')->implode(', ') }}
                                    </small>
                                @endif

                                <div class="pricelist-card-action">
                                    <a href="{{ route('paket.show', $package->code) }}" class="btn btn-dark btn-sm rounded-pill px-3">
                                        Pesan Sekarang
                                    </a>
                                </div>
                            </div>

                            <div class="pricelist-card-price">
                                <h4 class="pricelist-price-amount">Rp{{ number_format($package->price, 0, ',', '.') }}</h4>
                                <small class="pricelist-price-dp">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach

    </div>
</section>
@endsection