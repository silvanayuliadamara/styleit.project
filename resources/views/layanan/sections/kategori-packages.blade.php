{{-- Kategori Layanan — Package Cards Grid --}}
<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            @foreach($category->packages as $package)
                <div class="col-md-6 col-lg-4 scroll-reveal delay-{{ (($loop->index % 3) + 1) * 100 }}">
                    <div class="price-card h-100">

                        {{-- Package Image --}}
                        <div class="pkg-img-wrap" @if($category->slug === 'baju') style="aspect-ratio: 4/5;" @endif>
                            @if($package->image)
                                <img src="{{ str_starts_with($package->image, 'images/') ? asset($package->image) : asset('storage/' . $package->image) }}" alt="{{ $package->name }}" @if($category->slug === 'baju') style="object-fit: cover;" @endif>
                            @else
                                <div class="pkg-no-img">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Category & Title --}}
                        <div class="text-center mb-2">
                            <small>{{ $category->name }}</small>
                            <h3 class="mt-1" style="font-size: 21px;">{{ $package->name }}</h3>
                        </div>

                        {{-- Description --}}
                        <p class="text-center px-1" style="font-size: 13.5px; line-height: 1.6; color: #6f625c; min-height: 44px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $package->description }}</p>

                        {{-- Divider --}}
                        <div class="pkg-divider"><i class="bi bi-suit-diamond-fill"></i></div>

                        {{-- Price --}}
                        <div class="pkg-price-block">
                            <div class="price">Rp{{ number_format($package->price, 0, ',', '.') }}</div>
                            <div class="dp">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</div>
                        </div>

                        {{-- Divider --}}
                        <div class="pkg-divider"><i class="bi bi-suit-diamond-fill"></i></div>

                        {{-- Included Items --}}
                        @if($package->items->count())
                            <ul class="mini-list mb-4 px-1">
                                @foreach($package->items as $item)
                                    <li>{{ $item->name }} {{ $item->quantity }}{{ $item->unit }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <a href="{{ route('paket.show', $package->code) }}" class="btn-dark-custom w-100 text-center mt-auto">Pesan Sekarang</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
