<section class="section-padding bg-soft">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4 scroll-reveal">
            <div class="section-heading mb-0">
                <span>Portofolio</span>
                <h2>Karya Terbaru</h2>
                <div class="heading-divider"></div>
            </div>
            <a href="{{ route('portofolio') }}" class="link-gold">Lihat semua →</a>
        </div>
        <div class="row g-4">
            @foreach ($portfolioItems as $item)
                <div class="col-md-6 col-lg-3 scroll-reveal delay-{{ ($loop->index + 1) * 100 }}">
                    <div class="portfolio-card">
                        @if($item->image)
                            <img src="{{ str_starts_with($item->image, 'images/') ? asset($item->image) : asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="portfolio-img portfolio-img-{{ $item->category }}">
                        @else
                            <div class="portfolio-placeholder"><i class="bi bi-image"></i></div>
                        @endif
                        <h4>{{ $item->title }}</h4>
                        <span>{{ ['prewedding' => 'Prewedding', 'wedding' => 'Wedding', 'regular' => 'Regular', 'baju' => 'Khusus Baju'][$item->category] ?? ucfirst($item->category) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
