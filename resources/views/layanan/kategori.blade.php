@extends('layouts.app', ['title' => $category->name . ' - Lisa Yuli Belti'])

@section('content')
<section class="page-hero">
    <div class="container text-center">
        <p class="hero-label">{{ strtoupper($category->headline) }}</p>
        <h1>{{ $category->name }}</h1>
        <p>{{ $category->description }}</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">


        <div class="row g-4">
            @foreach($category->packages as $package)
                <div class="col-md-6 col-lg-4">
                    <div class="price-card h-100 {{ $package->is_popular ? 'popular' : '' }}">
                        @if($package->is_popular)<span class="popular-badge">Terfavorit</span>@endif
                        
                        @if($package->image)
                            <div class="mb-3 overflow-hidden rounded-4 shadow-sm" style="aspect-ratio: 3/4; border: 1px solid var(--lyb-line) !important; background: #f9f5f0;">
                                <img src="{{ str_starts_with($package->image, 'images/') ? asset($package->image) : asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-100 h-100" style="object-fit: cover; object-position: center; transition: transform 0.3s ease; display: block;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            </div>
                        @else
                            <div class="mb-3 overflow-hidden rounded-4 bg-light d-flex align-items-center justify-content-center text-muted shadow-sm" style="aspect-ratio: 3/4; border: 1px solid var(--lyb-line) !important;">
                                <i class="bi bi-image" style="font-size: 24px;"></i>
                            </div>
                        @endif

                        <small>{{ $category->name }}</small>
                        <h3>{{ $package->name }}</h3>
                        <p>{{ $package->description }}</p>
                        <div class="price">Rp{{ number_format($package->price, 0, ',', '.') }}</div>
                        <div class="dp">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</div>
                        @if($package->items->count())
                            <ul class="mini-list">
                                @foreach($package->items as $item)
                                    <li>{{ $item->name }} {{ $item->quantity }}{{ $item->unit }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <a href="{{ route('paket.show', $package->code) }}" class="btn-dark-custom w-100 text-center mt-auto">Pesan Sekarang</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
