@extends('layouts.app')

@section('content')

    {{-- Hero Section --}}
    <section class="hero-section premium-hero">
        <div class="container">
            <div class="row align-items-center g-5">

                <div class="col-lg-7">
                    <p class="hero-label">PREMIUM BRIDAL STUDIO</p>

                    <h1>
                        LISA YULI <br>
                        BELTI
                    </h1>

                    <p>
                        Setiap riasan adalah karya. Setiap pengantin adalah cerita.
                        Wujudkan momen sakral Anda dengan sentuhan elegan dan glamor
                        lembut LYB.
                    </p>

                    <div class="hero-actions">
                        <a href="{{ route('layanan.index') }}"
                           class="btn-dark-custom"
                           aria-label="Lihat layanan">
                            Lihat Layanan
                            <i class="bi bi-arrow-right"></i>
                        </a>

                        <a href="{{ route('pricelist') }}"
                           class="btn-outline-custom"
                           aria-label="Pesan sekarang">
                            Pesan Sekarang
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <div class="hero-stats">
                        <span>
                            <strong>4.9</strong> · 200+ Pengantin
                        </span>

                        <span>
                            <strong>Sejak</strong> 2018
                        </span>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="hero-card text-center">
                        <img src="{{ asset('images/logo.png') }}"
                             alt="Logo LYB"
                             class="img-fluid">

                        <blockquote>
                            "Hari paling bahagia hidup saya."
                        </blockquote>

                        <p>— Sasha, Wedding Gold Package</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Kategori Layanan --}}
    <section class="section-padding">
        <div class="container">

            <div class="section-heading text-center">
                <span>Kategori Layanan</span>
                <h2>Pilih Momen Spesial Anda</h2>
                <p>
                    Empat kategori riasan dan koleksi baju untuk setiap perayaan.
                </p>
            </div>

            <div class="row g-4">

                @forelse($categories as $category)
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('layanan.kategori', $category->slug) }}"
                           class="service-card h-100">

                            <i class="bi {{ $category->icon }}"></i>

                            <small>
                                {{ $category->headline }}
                            </small>

                            <h3>
                                {{ $category->name }}
                            </h3>

                            <p>
                                {{ $category->description }}
                            </p>

                            <span>
                                Lihat Detail
                                <i class="bi bi-arrow-right"></i>
                            </span>

                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>Belum ada kategori layanan.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </section>

    {{-- Portofolio --}}
    <section class="section-padding bg-soft">
        <div class="container">

            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
                <div class="section-heading mb-0">
                    <span>Portofolio</span>
                    <h2>Karya Terbaru</h2>
                </div>

                <a href="{{ route('portofolio') }}" class="link-gold">
                    Lihat Semua →
                </a>
            </div>

            <div class="row g-4">

                @forelse($portfolioItems as $item)
                    <div class="col-md-6 col-lg-3">
                        <div class="portfolio-card">

                            @if(!empty($item->image))
                                <img src="{{ asset('storage/' . $item->image) }}"
                                     alt="{{ $item->title }}"
                                     class="img-fluid rounded">
                            @else
                                <div class="portfolio-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif

                            <h4>{{ $item->title }}</h4>
                            <span>{{ $item->category }}</span>

                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>Belum ada portofolio.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </section>

    {{-- Keunggulan --}}
    <section class="section-padding">
        <div class="container">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="bi bi-shield-check"></i>
                        <h3>Riasan Tahan Lama</h3>
                        <p>
                            Produk premium yang aman dan memberikan hasil
                            flawless sepanjang hari.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="bi bi-chat-heart"></i>
                        <h3>Sentuhan Personal</h3>
                        <p>
                            Konsultasi gratis untuk menciptakan tampilan yang
                            sesuai dengan karakter Anda.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="bi bi-people"></i>
                        <h3>Tim Profesional</h3>
                        <p>
                            Berpengalaman menangani ratusan pernikahan dan
                            berbagai event spesial.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
