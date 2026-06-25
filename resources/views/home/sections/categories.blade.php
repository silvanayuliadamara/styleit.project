<section class="section-padding">
    <div class="container">
        <div class="section-heading text-center scroll-reveal">
            <span>Kategori Layanan</span>
            <h2>Pilih Momen Spesial Anda</h2>
            <div class="heading-divider"></div>
            <p class="mt-3">Empat kategori riasan dan koleksi baju untuk setiap perayaan.</p>
        </div>

        <div class="row g-4">
            @foreach ($categories as $category)
                @php
                    $slug = $category->slug;

                    $categoryImages = [
                        'prewedding' => 'images/categories/wedding/cover.jpeg',
                        'wedding' => 'storage/packages/wedding11.jpg',
                        'regular' => 'images/categories/regular/cover.jpeg',
                        'baju' => 'images/categories/khusus-baju/cover.jpeg',
                    ];

                    $imagePath = $categoryImages[$slug] ?? 'images/categories/wedding/cover.jpeg';
                @endphp

                <div class="col-md-6 col-lg-3 scroll-reveal delay-{{ ($loop->index + 1) * 100 }}">
                    <a href="{{ route('layanan.kategori', $category->slug) }}" class="category-photo-card h-100">
                        <div class="category-card-img" style="background-image: url('{{ asset($imagePath) }}');"></div>
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
