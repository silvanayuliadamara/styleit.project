{{-- Layanan Index — Editorial Split Cards with Arch Frames --}}
@php
    $categoryImages = [
        'prewedding' => 'images/categories/wedding/cover.jpeg',
        'wedding' => 'storage/packages/wedding11.jpg',
        'regular' => 'images/categories/regular/cover.jpeg',
        'baju' => 'images/categories/khusus-baju/cover.jpeg',
    ];
@endphp

<section class="section-padding">
    <div class="container">
        @foreach($categories as $category)
            @php
                $imagePath = $categoryImages[$category->slug] ?? 'images/categories/wedding/cover.jpeg';
                $isEven = $loop->index % 2 !== 0;
            @endphp

            {{-- Separator between cards --}}
            @if(!$loop->first)
                <div class="lyb-cat-separator scroll-reveal">
                    <i class="bi bi-suit-diamond-fill"></i>
                </div>
            @endif

            <a href="{{ route('layanan.kategori', $category->slug) }}" class="lyb-cat-card {{ $isEven ? 'lyb-cat-reverse' : '' }} scroll-reveal">
                {{-- Arch Image Side --}}
                <div class="lyb-cat-arch-wrap">
                    <div class="lyb-cat-arch-decor"></div>
                    <div class="lyb-cat-arch">
                        <img src="{{ asset($imagePath) }}" alt="{{ $category->name }}">
                        <div class="lyb-cat-arch-shine"></div>
                    </div>
                    <div class="lyb-cat-badge">0{{ $loop->iteration }}</div>
                </div>

                {{-- Content Side --}}
                <div class="lyb-cat-body">
                    <div class="lyb-cat-icon">
                        <i class="bi {{ $category->icon }}"></i>
                    </div>
                    <span class="lyb-cat-kicker">{{ $category->headline }}</span>
                    <h3 class="lyb-cat-title">{{ $category->name }}</h3>
                    <div class="lyb-cat-divider"></div>
                    <p class="lyb-cat-desc">{{ $category->description }}</p>
                    <span class="lyb-cat-link">
                        Lihat Paket <i class="bi bi-arrow-right"></i>
                    </span>
                </div>
            </a>
        @endforeach
    </div>
</section>
