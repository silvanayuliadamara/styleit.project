@extends('layouts.app')

@section('title', 'Pricelist')

@section('content')
<section class="price-page py-5">
    <div class="container price-container">

        @foreach ($categories as $category)
            @if ($category->packages->isNotEmpty())
                <div class="price-category mb-5">
                    @if ($category->headline)
                        <p class="section-kicker mb-1">{{ strtoupper($category->headline) }}</p>
                    @endif

                    <h2 class="price-category-title">{{ $category->name }}</h2>
                    <div class="price-line mb-4"></div>

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
                                ['url' => asset('storage/packages/wedding_premium.png'), 'title' => 'Brosur Wedding Premium'],
                                ['url' => asset('storage/packages/wedding_luxury.png'), 'title' => 'Brosur Wedding Luxury'],
                                ['url' => asset('storage/packages/makeup_wedding.png'), 'title' => 'Brosur Jasa Makeup Wedding']
                            ];
                        }
                    @endphp

                    @if (!empty($brochures))
                        <div class="row mb-5 g-3 justify-content-center">
                            <div class="col-12"><p class="small text-muted mb-2"><i class="bi bi-book-half"></i> Brosur Cetak / Buku Paket Resmi:</p></div>
                            @foreach ($brochures as $b)
                                <div class="col-6 col-sm-4 col-md-3 text-center">
                                    <div class="position-relative overflow-hidden rounded-3 shadow-sm border border-light" style="aspect-ratio: 3/4; background: #fff; cursor: pointer; border: 1px solid #eadfd6 !important;">
                                        <a href="{{ $b['url'] }}" target="_blank" title="Klik untuk memperbesar {{ $b['title'] }}">
                                            <img src="{{ $b['url'] }}" alt="{{ $b['title'] }}" class="w-100 h-100 object-fit-cover" style="transition: transform 0.3s ease; max-width: 100%;">
                                        </a>
                                    </div>
                                    <span class="d-block small fw-bold text-secondary mt-2" style="font-size: 11px;">{{ $b['title'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @foreach ($category->packages as $package)
                        <div class="price-card mb-4">
                            <div class="price-card-left">
                                <h5>{{ $package->name }}</h5>
                                <p>{{ $package->description }}</p>

                                @if ($package->items->isNotEmpty())
                                    <small>Termasuk: 
                                        {{ $package->items->map(fn($it) => $it->name . ' (' . $it->quantity . ' ' . $it->unit . ')')->implode(', ') }}
                                    </small>
                                @endif

                                <div class="mt-3">
                                    <a href="{{ route('paket.show', $package->code) }}" class="btn btn-dark btn-sm rounded-pill px-3">
                                        Pesan Sekarang
                                    </a>
                                </div>
                            </div>

                            <div class="price-card-right">
                                <h4>Rp{{ number_format($package->price, 0, ',', '.') }}</h4>
                                <small>DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach

    </div>
</section>
@endsection
