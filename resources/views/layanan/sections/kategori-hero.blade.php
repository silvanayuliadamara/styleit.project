{{-- Kategori Layanan — Hero Section --}}
<section class="page-hero">
    <div class="container">
        {{-- Back Navigation --}}
        <div class="mb-3 text-start scroll-reveal">
            <a href="{{ route('layanan.index') }}" class="text-decoration-none d-inline-flex align-items-center gap-2 back-link" style="font-size: 14px; font-weight: 500;">
                <i class="bi bi-arrow-left"></i> Kembali ke Kategori Layanan
            </a>
        </div>

        <div class="text-center scroll-reveal">
            <p class="hero-label">{{ strtoupper($category->headline) }}</p>
            <h1>{{ $category->name }}</h1>
            <div class="heading-divider"></div>
            <p class="mt-3" style="max-width: 560px; margin: 0 auto;">{{ $category->description }}</p>

            {{-- Stats Strip --}}
            <div class="kategori-stats">
                <div class="kategori-stat-item">
                    <i class="bi bi-box-seam"></i>
                    <strong>{{ $category->packages->count() }}</strong>
                    <span>Paket</span>
                </div>
                <div class="kategori-stat-divider"></div>
                <div class="kategori-stat-item">
                    <i class="bi bi-tag"></i>
                    <strong>Rp{{ number_format($category->packages->min('price'), 0, ',', '.') }}</strong>
                    <span>Mulai Dari</span>
                </div>
            </div>
        </div>
    </div>
</section>
