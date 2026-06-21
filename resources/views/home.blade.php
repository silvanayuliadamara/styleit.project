@extends('layouts.app')

@section('content')
    <section class="hero-section premium-hero">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <p class="hero-label">Wedding Galery And Makeup Artist</p>
                    <h1>LISA YULI<br>BELTI</h1>
                    <p>Setiap riasan adalah karya. Setiap pengantin adalah cerita. Wujudkan momen sakral Anda dengan
                        sentuhan elegan dan glamor lembut LYB.</p>
                    <div class="hero-actions">
                        <a href="{{ route('layanan.index') }}" class="btn-dark-custom">Lihat Layanan <i
                                class="bi bi-arrow-right"></i></a>
                        <a href="{{ route('pricelist') }}" class="btn-outline-custom">Pesan Sekarang <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                    <div class="hero-stats">
                        <span>
                            <i class="bi bi-star-fill"></i>
                            <strong>4.9</strong> · 200+ pengantin
                        </span>

                        <span>
                            <i class="bi bi-award"></i>
                            <strong>Sejak</strong> 2018
                        </span>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-card">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo LYB">
                        <blockquote>“Hari paling bahagia hidup saya.”</blockquote>
                        <p>— Sasha, Wedding Gold Package</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <div class="section-heading text-center">
                <span>Kategori Layanan</span>
                <h2>Pilih Momen Spesial Anda</h2>
                <p>Empat kategori riasan dan koleksi baju untuk setiap perayaan.</p>
            </div>

            <div class="row g-4">
                @foreach ($categories as $category)
                    @php
                        $slug = $category->slug;

                        $categoryImages = [
                            'prewedding' => 'images/categories/wedding/cover.jpeg',
                            'wedding' => 'images/categories/wedding/cover.jpeg',
                            'regular' => 'images/categories/regular/cover.jpeg',
                            'baju' => 'images/categories/khusus-baju/cover.jpeg',
                        ];

                        $imagePath = $categoryImages[$slug] ?? 'images/categories/wedding/cover.jpeg';
                    @endphp

                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('layanan.kategori', $category->slug) }}" class="category-photo-card h-100"
                            style="background-image: url('{{ asset($imagePath) }}');">

                            <div class="category-photo-overlay"></div>

                            <div class="category-photo-content">
                                <small>{{ $category->headline }}</small>
                                <h3>{{ $category->name }}</h3>

                                <span>
                                    Lihat Detail <i class="bi bi-arrow-right"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding bg-soft">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
                <div class="section-heading mb-0">
                    <span>Portofolio</span>
                    <h2>Karya Terbaru</h2>
                </div>
                <a href="{{ route('portofolio') }}" class="link-gold">Lihat semua →</a>
            </div>
            <div class="row g-4">
                @foreach ($portfolioItems as $item)
                    <div class="col-md-6 col-lg-3">
                        <div class="portfolio-card">
                            <div class="portfolio-placeholder"><i class="bi bi-image"></i></div>
                            <h4>{{ $item->title }}</h4>
                            <span>{{ $item->category }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding testimoni-section bg-dark text-white" style="background-color: #211313;">
        <div class="container">
            <div class="section-heading text-center mb-5">
                <span class="text-gold" style="color: #b08a42; letter-spacing: 2px; text-transform: uppercase; font-size: 0.85rem; font-weight: 700;">Ulasan Pelanggan</span>
                <h2 class="text-white" style="color: #efe2d5; font-family: Georgia, serif; font-size: 2.25rem; font-weight: 500; margin-top: 0.5rem;">Apa Kata Mereka?</h2>
                <p class="text-muted" style="color: #a39b8f !important;">Kisah nyata kebahagiaan dari para pelanggan setia Lisa Yuli Belti.</p>
            </div>

            @if($reviews->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted" style="font-family: Arial, sans-serif; font-size: 15px;">Belum ada ulasan untuk ditampilkan.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($reviews as $review)
                        <div class="col-md-6 col-lg-4">
                            <div class="testimoni-card p-4 h-100 d-flex flex-column justify-content-between" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(234, 223, 214, 0.1); border-radius: 18px; transition: transform 0.3s ease, border-color 0.3s ease;">
                                <div>
                                    <div class="star-display mb-3" style="color: #b08a42;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}" style="font-size: 14px; margin-right: 2px;"></i>
                                        @endfor
                                    </div>
                                    <p class="review-comment" style="font-family: Georgia, serif; font-style: italic; color: #efe2d5; line-height: 1.6; font-size: 0.95rem; margin-bottom: 1.5rem;">
                                        "{{ Str::limit($review->komentar, 250, '...') }}"
                                    </p>
                                </div>
                                <div class="border-top pt-3" style="border-color: rgba(234, 223, 214, 0.1) !important;">
                                    <h5 class="customer-name mb-1" style="font-family: Arial, sans-serif; font-weight: 700; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; color: #b08a42;">
                                        {{ $review->user->name ?? 'Pelanggan LYB' }}
                                    </h5>
                                    <p class="package-name mb-0 text-muted small" style="font-family: Arial, sans-serif; font-size: 11px;">
                                        Paket: {{ $review->package->name ?? 'Layanan' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card"><i class="bi bi-shield-check"></i>
                        <h3>Riasan Tahan Lama</h3>
                        <p>Produk premium yang aman dan hasil flawless seharian.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card"><i class="bi bi-chat-heart"></i>
                        <h3>Sentuhan Personal</h3>
                        <p>Konsultasi gratis untuk look yang sesuai karakter Anda.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card"><i class="bi bi-people"></i>
                        <h3>Tim Profesional</h3>
                        <p>Berpengalaman menangani ratusan pernikahan dan event.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
