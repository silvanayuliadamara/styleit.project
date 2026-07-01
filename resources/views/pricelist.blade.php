@extends('layouts.app')

@section('title', 'Pricelist')

@section('content')
<div class="pricelist-page">
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pricelist.css') }}">
@endpush

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

                    @if ($category->slug === 'wedding')
                        @php
                            $makeupAndAttire = $category->packages->filter(fn($p) => $p->butuh_makeup && $p->butuh_baju);
                            $makeupOnly = $category->packages->filter(fn($p) => $p->butuh_makeup && !$p->butuh_baju);
                            
                            $bajuCategory = \App\Models\ServiceCategory::where('slug', 'baju')->first();
                            $bajuPackages = $bajuCategory ? $bajuCategory->packages : collect([]);
                            $attireOnly = $category->packages->filter(fn($p) => !$p->butuh_makeup && $p->butuh_baju)
                                            ->concat($bajuPackages)
                                            ->unique('id');
                        @endphp

                        {{-- Section 1: Makeup + Attire --}}
                        @if($makeupAndAttire->isNotEmpty())
                            <h4 class="fw-bold text-gold-dark mt-4 mb-3" style="font-family: Georgia, serif; letter-spacing: 1px;">Makeup + Attire Packages</h4>
                            @foreach ($makeupAndAttire as $package)
                                <div class="pricelist-card scroll-reveal delay-{{ ($loop->index % 4) * 100 }}">
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
                                                {{ $package->items->map(fn($it) => $it->name)->implode(', ') }}
                                            </small>
                                        @endif

                                        <div class="pricelist-card-action">
                                            <a href="{{ route('paket.show', $package->code) }}" class="btn btn-dark btn-sm rounded-pill px-3">
                                                <span>Pesan Sekarang</span>
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="pricelist-card-price">
                                        <h4 class="pricelist-price-amount">Rp{{ number_format($package->price, 0, ',', '.') }}</h4>
                                        <small class="pricelist-price-dp">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{-- Section 2: Makeup Only (Single Card) --}}
                        @if($makeupOnly->isNotEmpty())
                            <h4 class="fw-bold text-gold-dark mt-5 mb-3" style="font-family: Georgia, serif; letter-spacing: 1px;">Makeup Only Packages</h4>
                            <div class="p-4 rounded-4 border bg-white shadow-sm mb-4" style="border-color: #eadfd6 !important;">
                                <div class="mb-3 text-muted small">Paket rias pengantin tanpa penyewaan attire:</div>
                                <div class="d-flex flex-column gap-3">
                                    @foreach ($makeupOnly as $package)
                                        <div class="p-3 rounded-3 border bg-body-tertiary d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="border-color: #eadfd6 !important;">
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark">{{ $package->name }}</h6>
                                                <p class="text-secondary small mb-0">{{ $package->description }}</p>
                                            </div>
                                            <div class="text-md-end d-flex flex-row flex-md-column align-items-center justify-content-between gap-2" style="min-width: 180px;">
                                                <div>
                                                    <span class="fw-bold text-gold-dark d-block" style="font-size: 16px;">Rp{{ number_format($package->price, 0, ',', '.') }}</span>
                                                    <small class="text-muted" style="font-size: 11px;">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</small>
                                                </div>
                                                <a href="{{ route('paket.show', $package->code) }}" class="btn btn-dark btn-sm rounded-pill px-3 py-1" style="font-size: 12px; background: #211313; border: none; color: #fff;">Pesan</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif



                    @elseif ($category->slug === 'baju')
                        @php
                            // 1. Paket Busana: diawali kata 'baju'/'paket' DAN (diawali 'paket' atau mengandung '+', '&', 'dan')
                            $paketBusana = $category->packages->filter(function($p) {
                                $name = strtolower($p->name);
                                $isBajuOrPaket = str_starts_with($name, 'baju') || str_starts_with($name, 'paket');
                                $isCombo = str_starts_with($name, 'paket') || str_contains($name, '+') || str_contains($name, '&') || str_contains($name, ' dan ');
                                return $isBajuOrPaket && $isCombo;
                            });
                            
                            // 2. Koleksi Busana Tunggal: diawali kata 'baju'/'paket' tapi BUKAN combo
                            $busanaTunggal = $category->packages->filter(function($p) {
                                $name = strtolower($p->name);
                                $isBajuOrPaket = str_starts_with($name, 'baju') || str_starts_with($name, 'paket');
                                $isCombo = str_starts_with($name, 'paket') || str_contains($name, '+') || str_contains($name, '&') || str_contains($name, ' dan ');
                                return $isBajuOrPaket && !$isCombo;
                            });
                            
                            // 3. Aksesoris & Perlengkapan: tidak diawali kata 'baju'/'paket'
                            $aksesorisJasa = $category->packages->filter(function($p) {
                                $name = strtolower($p->name);
                                return !str_starts_with($name, 'baju') && !str_starts_with($name, 'paket');
                            });
                        @endphp

                        {{-- Section 1: Koleksi Paket Busana --}}
                        @if($paketBusana->isNotEmpty())
                            <h4 class="fw-bold text-gold-dark mt-4 mb-3" style="font-family: Georgia, serif; letter-spacing: 1px;">Koleksi Paket Busana</h4>
                            @foreach ($paketBusana as $package)
                                <div class="pricelist-card scroll-reveal delay-{{ ($loop->index % 4) * 100 }}">
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
                                                {{ $package->items->map(fn($it) => $it->name)->implode(', ') }}
                                            </small>
                                        @endif

                                        <div class="pricelist-card-action">
                                            <a href="{{ route('paket.show', $package->code) }}" class="btn btn-dark btn-sm rounded-pill px-3">
                                                <span>Pesan Sekarang</span>
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="pricelist-card-price">
                                        <h4 class="pricelist-price-amount">Rp{{ number_format($package->price, 0, ',', '.') }}</h4>
                                        <small class="pricelist-price-dp">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{-- Section 2: Koleksi Busana Tunggal --}}
                        @if($busanaTunggal->isNotEmpty())
                            <h4 class="fw-bold text-gold-dark mt-5 mb-3" style="font-family: Georgia, serif; letter-spacing: 1px;">Koleksi Busana Tunggal</h4>
                            @foreach ($busanaTunggal as $package)
                                <div class="pricelist-card scroll-reveal delay-{{ ($loop->index % 4) * 100 }}">
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
                                                {{ $package->items->map(fn($it) => $it->name)->implode(', ') }}
                                            </small>
                                        @endif

                                        <div class="pricelist-card-action">
                                            <a href="{{ route('paket.show', $package->code) }}" class="btn btn-dark btn-sm rounded-pill px-3">
                                                <span>Pesan Sekarang</span>
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="pricelist-card-price">
                                        <h4 class="pricelist-price-amount">Rp{{ number_format($package->price, 0, ',', '.') }}</h4>
                                        <small class="pricelist-price-dp">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    @else
                        @foreach ($category->packages as $package)
                            <div class="pricelist-card scroll-reveal delay-{{ ($loop->index % 4) * 100 }}">
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
                                    <h5 class="pricelist-card-name">
                                        @if($category->slug === 'prewedding')
                                            {{ trim(preg_replace('/\(\d+jt\)/i', '', $package->name)) }}
                                        @else
                                            {{ $package->name }}
                                        @endif
                                    </h5>
                                    @if(!empty($package->description) && $category->slug !== 'prewedding')
                                        <p class="pricelist-card-desc">{{ $package->description }}</p>
                                    @endif

                                    @if ($package->items->isNotEmpty())
                                        <small class="pricelist-card-includes">Termasuk: 
                                            {{ $package->items->map(fn($it) => $it->name)->implode(', ') }}
                                        </small>
                                    @endif

                                    <div class="pricelist-card-action">
                                        <a href="{{ route('paket.show', $package->code) }}" class="btn btn-dark btn-sm rounded-pill px-3">
                                            <span>Pesan Sekarang</span>
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="pricelist-card-price">
                                    <h4 class="pricelist-price-amount">Rp{{ number_format($package->price, 0, ',', '.') }}</h4>
                                    <small class="pricelist-price-dp">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</small>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif
        @endforeach

    </div>
</section>
</div>
@endsection