@extends('layouts.app', ['title' => 'Portofolio - Lisa Yuli Belti'])

@section('content')
<section class="page-hero">
    <div class="container text-center">
        <p class="hero-label">GALERI</p>
        <h1>Portofolio</h1>
        <p>Karya nyata dari pengantin dan klien LYB.</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="portfolio-filter text-center mb-4">
            @foreach(['semua' => 'Semua', 'prewedding' => 'Prewedding', 'wedding' => 'Wedding', 'regular' => 'Regular', 'baju' => 'Khusus Baju'] as $key => $label)
                <button class="filter-btn {{ $loop->first ? 'active' : '' }}" data-filter="{{ $key }}">{{ $label }}</button>
            @endforeach
        </div>
        <div class="row g-4" id="portfolioGrid">
            @foreach($items as $item)
                <div class="col-md-6 col-lg-4 portfolio-item" data-category="{{ $item->category }}">
                    <div class="portfolio-card portfolio-card-lg">
                        @if($item->image)
                            <img src="{{ str_starts_with($item->image, 'images/') ? asset($item->image) : asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="portfolio-img portfolio-img-{{ $item->category }}">
                        @else
                            <div class="portfolio-placeholder"><i class="bi bi-image"></i></div>
                        @endif
                        <h4>{{ trim(preg_replace('/\s*[-–—]\s*.+$/u', '', $item->title)) }}</h4>
                        <span>{{ ['prewedding' => 'Prewedding', 'wedding' => 'Wedding', 'regular' => 'Regular', 'baju' => 'Khusus Baju'][$item->category] ?? ucfirst($item->category) }}</span>
                        <p>{{ $item->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const portfolioItems = document.querySelectorAll('.portfolio-item');

    filterButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            // 1. Hilangkan kelas 'active' dari semua tombol, lalu aktifkan tombol yang diklik
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filterValue = btn.dataset.filter;

            // 2. Animasikan mengecil dan memudar keluar (fade out & scale down) semua gambar terlebih dahulu
            portfolioItems.forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.92)';
            });

            // 3. Setelah 250ms (ketika efek memudar selesai), saring gambar mana yang akan ditampilkan
            setTimeout(() => {
                portfolioItems.forEach(item => {
                    const isMatched = filterValue === 'semua' || item.dataset.category === filterValue;
                    
                    if (isMatched) {
                        item.style.display = '';
                        // Berikan sedikit jeda (20ms) agar browser sempat merender perpindahan display sebelum memicu animasi masuk
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'scale(1)';
                        }, 20);
                    } else {
                        item.style.display = 'none';
                    }
                });
            }, 250);
        });
    });
});
</script>
@endsection
