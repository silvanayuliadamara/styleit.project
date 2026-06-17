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
        {{-- Pricelist Book / Manual Brochure Section --}}
        @php
            $brochures = [];
            if ($category->slug === 'baju') {
                $brochures = [
                    ['url' => asset('storage/packages/attire_packages.jpg'), 'title' => 'Buku Paket Attire'],
                    ['url' => asset('storage/packages/attire_items.jpg'), 'title' => 'Brosur Satuan Attire (Premium & Luxury)']
                ];
            } elseif ($category->slug === 'wedding') {
                $brochures = [
                    ['url' => asset('storage/packages/wedding_premium.png'), 'title' => 'Brosur Wedding Premium'],
                    ['url' => asset('storage/packages/wedding_luxury.png'), 'title' => 'Brosur Wedding Luxury'],
                    ['url' => asset('storage/packages/makeup_wedding.png'), 'title' => 'Brosur Jasa Makeup Wedding']
                ];
            }
        @endphp

        @if (!empty($brochures))
            <div class="row mb-5 justify-content-center">
                <div class="col-lg-10">
                    <div class="glass-card p-4 rounded-4 shadow-sm border border-gold-light" style="background: rgba(251, 248, 241, 0.5); border: 1px solid #eadfd6 !important;">
                        <h4 class="fw-bold mb-3 text-gold-dark text-center"><i class="bi bi-book-half me-2"></i> Buku Paket Manual / Pricelist Resmi</h4>
                        <p class="text-secondary text-center small mb-4">Berikut adalah brosur/pricelist cetak resmi dari Lisa Yuli Belti. Klik gambar untuk memperbesar.</p>
                        <div class="row g-3 justify-content-center">
                            @foreach ($brochures as $b)
                                <div class="col-6 col-sm-4 text-center">
                                    <div class="position-relative overflow-hidden rounded-3 shadow-sm border border-light" style="aspect-ratio: 3/4; background: #fff; cursor: pointer; border: 1px solid #eadfd6 !important;">
                                        <a href="{{ $b['url'] }}" target="_blank" title="Klik untuk memperbesar {{ $b['title'] }}">
                                            <img src="{{ $b['url'] }}" alt="{{ $b['title'] }}" class="w-100 h-100 object-fit-cover hover-zoom" style="transition: transform 0.3s ease; max-width: 100%;">
                                        </a>
                                    </div>
                                    <span class="d-block small fw-bold text-secondary mt-2" style="font-size: 11px;">{{ $b['title'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4">
            @foreach($category->packages as $package)
                <div class="col-md-6 col-lg-4">
                    <div class="price-card h-100 {{ $package->is_popular ? 'popular' : '' }}">
                        @if($package->is_popular)<span class="popular-badge">Terfavorit</span>@endif
                        
                        @if($package->image)
                            <div class="mb-3 overflow-hidden rounded-4 shadow-sm" style="aspect-ratio: 16/10; border: 1px solid var(--lyb-line) !important;">
                                <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-100 h-100 object-fit-cover" style="transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                            </div>
                        @else
                            <div class="mb-3 overflow-hidden rounded-4 bg-light d-flex align-items-center justify-content-center text-muted shadow-sm" style="aspect-ratio: 16/10; border: 1px solid var(--lyb-line) !important;">
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
