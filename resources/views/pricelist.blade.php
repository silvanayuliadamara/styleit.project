@extends('layouts.app')

@section('title', 'Pricelist')

@section('content')
<style>
    .pricelist-img-wrapper {
        width: 110px;
        height: 147px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #eadfd6;
        flex-shrink: 0;
        background-color: #fbf8f1;
    }
    .pricelist-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .pricelist-card:hover .pricelist-img {
        transform: scale(1.05);
    }
    .pricelist-img-placeholder {
        width: 100%;
        height: 100%;
        background-color: #fbf8f1;
        color: #b08a42;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    @media (max-width: 768px) {
        .pricelist-img-wrapper {
            width: 120px;
            height: 160px;
            border-radius: 12px;
        }
    }
</style>

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
                            <div class="pricelist-img-wrapper">
                                @if($package->image)
                                    <img src="{{ str_starts_with($package->image, 'images/') ? asset($package->image) : asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="pricelist-img">
                                @else
                                    <div class="pricelist-img-placeholder">
                                        <i class="bi bi-stars"></i>
                                    </div>
                                @endif
                            </div>

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