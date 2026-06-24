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