@extends('layouts.app')

@section('title', 'Pricelist')

@section('content')
<section class="price-page py-5">
    <div class="container price-container">

        @php
            $priceGroups = [
                [
                    'kicker' => '',
                    'title' => 'Prewedding',
                    'packages' => [
                        [
                            'code' => 'PKG-PREWED',
                            'name' => 'Paket Prewedding',
                            'description' => 'Makeup prewedding + opsi baju pasangan',
                            'includes' => 'Henna 1x',
                            'price' => 2500000,
                            'dp' => 500000,
                        ],
                    ],
                ],
                [
                    'kicker' => 'HARI SAKRAL ANDA, SEMPURNA',
                    'title' => 'Wedding',
                    'packages' => [
                        [
                            'code' => 'PKG-WED-GOLD',
                            'name' => 'Paket Wedding Gold',
                            'description' => 'Makeup pengantin lengkap + henna 2x + melati 1x',
                            'includes' => 'Henna 2x, Melati 1x',
                            'price' => 5000000,
                            'dp' => 1000000,
                        ],
                    ],
                ],
                [
                    'kicker' => 'WISUDA & ACARA SPESIAL',
                    'title' => 'Regular',
                    'packages' => [
                        [
                            'code' => 'PKG-REG-WIS',
                            'name' => 'Paket Regular Wisuda',
                            'description' => 'Makeup wisuda glowing, maksimal 3 customer per hari',
                            'includes' => '',
                            'price' => 500000,
                            'dp' => 200000,
                        ],
                    ],
                ],
                [
                    'kicker' => 'KOLEKSI GAUN & KEBAYA',
                    'title' => 'Khusus Baju',
                    'packages' => [
                        [
                            'code' => 'PKG-BAJU-PASANGAN',
                            'name' => 'Paket Baju Pasangan',
                            'description' => 'Sewa baju pengantin pasangan, koleksi premium',
                            'includes' => '',
                            'price' => 750000,
                            'dp' => 250000,
                        ],
                    ],
                ],
            ];
        @endphp

        @foreach ($priceGroups as $group)
            <div class="price-category mb-5">
                @if ($group['kicker'])
                    <p class="section-kicker mb-1">{{ $group['kicker'] }}</p>
                @endif

                <h2 class="price-category-title">{{ $group['title'] }}</h2>
                <div class="price-line mb-4"></div>

                @foreach ($group['packages'] as $package)
                    <div class="price-card mb-4">
                        <div class="price-card-left">
                            <h5>{{ $package['name'] }}</h5>
                            <p>{{ $package['description'] }}</p>

                            @if ($package['includes'])
                                <small>Termasuk: {{ $package['includes'] }}</small>
                            @endif

                            <div class="mt-3">
                                <a href="{{ url('/paket/' . $package['code']) }}" class="btn btn-dark btn-sm rounded-pill px-3">
                                    Pesan Sekarang
                                </a>
                            </div>
                        </div>

                        <div class="price-card-right">
                            <h4>Rp{{ number_format($package['price'], 0, ',', '.') }}</h4>
                            <small>DP Rp{{ number_format($package['dp'], 0, ',', '.') }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

    </div>
</section>
@endsection
