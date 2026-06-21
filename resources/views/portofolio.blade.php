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
                            <img src="{{ str_starts_with($item->image, 'images/') ? asset($item->image) : asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="portfolio-img">
                        @else
                            <div class="portfolio-placeholder"><i class="bi bi-image"></i></div>
                        @endif
                        <h4>{{ $item->title }}</h4>
                        <span>{{ ['prewedding' => 'Prewedding', 'wedding' => 'Wedding', 'regular' => 'Regular', 'baju' => 'Khusus Baju'][$item->category] ?? ucfirst($item->category) }}</span>
                        <p>{{ $item->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<script>
document.querySelectorAll('.filter-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.dataset.filter;
        document.querySelectorAll('.portfolio-item').forEach(item => {
            item.style.display = filter === 'semua' || item.dataset.category === filter ? '' : 'none';
        });
    });
});
</script>
@endsection
