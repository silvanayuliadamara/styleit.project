{{-- Kategori Layanan — Package Cards Grid --}}
<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            @if($category->slug === 'wedding')
                @php
                    $makeupAndAttire = $category->packages->filter(fn($p) => $p->butuh_makeup && $p->butuh_baju);
                    $makeupOnly = $category->packages->filter(fn($p) => $p->butuh_makeup && !$p->butuh_baju);
                    
                    $bajuCategory = \App\Models\ServiceCategory::where('slug', 'baju')->first();
                    $bajuPackages = $bajuCategory ? $bajuCategory->packages : collect([]);
                    $attireOnly = $category->packages->filter(fn($p) => !$p->butuh_makeup && $p->butuh_baju)
                                    ->concat($bajuPackages)
                                    ->unique('id');
                @endphp

                {{-- 1. Makeup + Attire --}}
                @if($makeupAndAttire->isNotEmpty())
                    <div class="col-12 mb-3 mt-2 scroll-reveal">
                        <h2 class="fw-bold text-gold-dark" style="font-family: Georgia, serif;">Makeup + Attire Packages</h2>
                        <div class="pricelist-divider" style="height: 1px; background: linear-gradient(90deg, #d4b87a 0%, #eadfd6 60%, transparent 100%); width: 100%; margin-bottom: 20px;"></div>
                    </div>
                    @foreach($makeupAndAttire as $package)
                        <div class="col-md-6 col-lg-4 scroll-reveal delay-{{ (($loop->index % 3) + 1) * 100 }}">
                            <div class="price-card h-100">
                                {{-- Package Image --}}
                                <div class="pkg-img-wrap">
                                    @if($package->image)
                                        <img src="{{ str_starts_with($package->image, 'images/') ? asset($package->image) : asset('storage/' . $package->image) }}" alt="{{ $package->name }}">
                                    @else
                                        <div class="pkg-no-img">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="text-center mb-2">
                                    <small>{{ $category->name }}</small>
                                    <h3 class="mt-1" style="font-size: 21px;">{{ $package->name }}</h3>
                                </div>

                                <p class="text-center px-1" style="font-size: 13.5px; line-height: 1.6; color: #6f625c; min-height: 44px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $package->description }}</p>

                                <div class="pkg-divider"><i class="bi bi-suit-diamond-fill"></i></div>

                                <div class="pkg-price-block">
                                    <div class="price">Rp{{ number_format($package->price, 0, ',', '.') }}</div>
                                    <div class="dp">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</div>
                                </div>

                                <div class="pkg-divider"><i class="bi bi-suit-diamond-fill"></i></div>

                                @if($package->items->count())
                                    <ul class="mini-list mb-4 px-1">
                                        @foreach($package->items as $item)
                                            <li>{{ $item->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <a href="{{ route('paket.show', $package->code) }}" class="btn-dark-custom w-100 text-center mt-auto">Pesan Sekarang</a>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- 2. Makeup Only (Single Card) --}}
                @if($makeupOnly->isNotEmpty())
                    <div class="col-12 mb-3 mt-5 scroll-reveal">
                        <h2 class="fw-bold text-gold-dark" style="font-family: Georgia, serif;">Makeup Only Packages</h2>
                        <div class="pricelist-divider" style="height: 1px; background: linear-gradient(90deg, #d4b87a 0%, #eadfd6 60%, transparent 100%); width: 100%; margin-bottom: 20px;"></div>
                    </div>
                    <div class="col-12 col-lg-8 mx-auto scroll-reveal mb-5">
                        <div class="price-card d-flex flex-column h-100 p-4 p-md-5" style="border: 2px solid var(--lyb-gold); background: #fdfbf7;">
                            <div class="text-center mb-4">
                                <span class="text-uppercase fw-bold text-gold-dark" style="letter-spacing: 2px; font-size: 12px;">Wedding Package</span>
                                <h3 class="fw-bold mt-1 text-dark" style="font-family: Georgia, serif; font-size: 28px;">Makeup Only Packages</h3>
                                <p class="text-muted small mx-auto mt-2" style="max-width: 500px;">Paket rias pengantin lengkap tanpa penyewaan pakaian. Sangat cocok jika Anda sudah memiliki pakaian adat/modern sendiri.</p>
                            </div>
                            <div class="pkg-divider mb-4"><i class="bi bi-suit-diamond-fill"></i></div>
                            <div class="d-flex flex-column gap-4">
                                @foreach($makeupOnly as $package)
                                    <div class="p-3 p-md-4 rounded-4 border bg-white shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="border-color: #eadfd6 !important;">
                                        <div style="flex: 1;">
                                            <h4 class="fw-bold text-dark mb-1" style="font-size: 18px;">{{ $package->name }}</h4>
                                            <p class="text-secondary small mb-2 mb-md-0" style="line-height: 1.5; max-width: 480px;">{{ $package->description }}</p>
                                            @if($package->items->count())
                                                <div class="mt-2">
                                                    <span class="fw-semibold text-muted" style="font-size: 11px; letter-spacing: 1px; text-transform: uppercase;">Termasuk:</span>
                                                    <div class="d-flex flex-wrap gap-x-3 gap-y-1 mt-1">
                                                        @foreach($package->items as $item)
                                                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 11px; font-weight: 500;">
                                                                <i class="bi bi-check2 text-gold-dark me-1"></i>{{ $item->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-md-end d-flex flex-row flex-md-column justify-content-between align-items-center gap-2" style="min-width: 180px;">
                                            <div>
                                                <div class="fw-bold text-gold-dark" style="font-size: 20px;">Rp{{ number_format($package->price, 0, ',', '.') }}</div>
                                                <div class="text-muted small" style="font-size: 11px;">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</div>
                                            </div>
                                            <a href="{{ route('paket.show', $package->code) }}" class="btn-dark-custom btn-sm px-4 py-2" style="font-size: 13px;">Pesan Sekarang</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                 @endif

                {{-- 3. Paket Wedding Lainnya (Fallback) --}}
                @php
                    $otherPackages = $category->packages->diff($makeupAndAttire)->diff($makeupOnly);
                @endphp

                @if($otherPackages->isNotEmpty())
                    <div class="col-12 mb-3 mt-5 scroll-reveal">
                        <h2 class="fw-bold text-gold-dark" style="font-family: Georgia, serif;">Wedding Packages</h2>
                        <div class="pricelist-divider" style="height: 1px; background: linear-gradient(90deg, #d4b87a 0%, #eadfd6 60%, transparent 100%); width: 100%; margin-bottom: 20px;"></div>
                    </div>
                    @foreach($otherPackages as $package)
                        <div class="col-md-6 col-lg-4 scroll-reveal delay-{{ (($loop->index % 3) + 1) * 100 }} mb-4">
                            <div class="price-card h-100">
                                {{-- Package Image --}}
                                <div class="pkg-img-wrap">
                                    @if($package->image)
                                        <img src="{{ str_starts_with($package->image, 'images/') ? asset($package->image) : asset('storage/' . $package->image) }}" alt="{{ $package->name }}">
                                    @else
                                        <div class="pkg-no-img">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="text-center mb-2">
                                    <small>{{ $category->name }}</small>
                                    <h3 class="mt-1" style="font-size: 21px;">{{ $package->name }}</h3>
                                </div>

                                <p class="text-center px-1" style="font-size: 13.5px; line-height: 1.6; color: #6f625c; min-height: 44px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $package->description }}</p>

                                <div class="pkg-divider"><i class="bi bi-suit-diamond-fill"></i></div>

                                <div class="pkg-price-block">
                                    <div class="price">Rp{{ number_format($package->price, 0, ',', '.') }}</div>
                                    <div class="dp">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</div>
                                </div>

                                <div class="pkg-divider"><i class="bi bi-suit-diamond-fill"></i></div>

                                @if($package->items->count())
                                    <ul class="mini-list mb-4 px-1">
                                        @foreach($package->items as $item)
                                            <li>{{ $item->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <a href="{{ route('paket.show', $package->code) }}" class="btn-dark-custom w-100 text-center mt-auto">Pesan Sekarang</a>
                            </div>
                        </div>
                    @endforeach
                @endif


            @elseif($category->slug === 'baju')
                @php
                    // 1. Paket Busana: diawali kata 'baju'/'paket' DAN (diawali 'paket' atau mengandung '+', '&', 'dan')
                    $paketBusana = $category->packages->filter(function($p) {
                        $name = strtolower($p->name);
                        $isBajuOrPaket = str_starts_with($name, 'baju') || str_starts_with($name, 'paket');
                        $isCombo = str_starts_with($name, 'paket') || str_contains($name, '+') || str_contains($name, '&') || str_contains($name, ' dan ');
                        return $isBajuOrPaket && $isCombo;
                    });
                    
                    // 2. Koleksi Busana Tunggal: diawali kata 'baju'/'paket' tapi BUKAN combo
                    $busanaTunggal = $category->packages->filter(function($p) {
                        $name = strtolower($p->name);
                        $isBajuOrPaket = str_starts_with($name, 'baju') || str_starts_with($name, 'paket');
                        $isCombo = str_starts_with($name, 'paket') || str_contains($name, '+') || str_contains($name, '&') || str_contains($name, ' dan ');
                        return $isBajuOrPaket && !$isCombo;
                    });
                    
                    // 3. Aksesoris & Perlengkapan: tidak diawali kata 'baju'/'paket'
                    $aksesorisJasa = $category->packages->filter(function($p) {
                        $name = strtolower($p->name);
                        return !str_starts_with($name, 'baju') && !str_starts_with($name, 'paket');
                    });
                @endphp

                {{-- 1. Koleksi Paket Busana --}}
                @if($paketBusana->isNotEmpty())
                    <div class="col-12 mb-3 mt-2 scroll-reveal">
                        <h2 class="fw-bold text-gold-dark" style="font-family: Georgia, serif;">Koleksi Paket Busana</h2>
                        <p class="text-muted small">Pilihan set busana lengkap berpasangan dengan aksesoris adat premium untuk hari istimewa Anda.</p>
                        <div class="pricelist-divider" style="height: 1px; background: linear-gradient(90deg, #d4b87a 0%, #eadfd6 60%, transparent 100%); width: 100%; margin-bottom: 20px;"></div>
                    </div>
                    @foreach($paketBusana as $package)
                        <div class="col-md-6 col-lg-4 scroll-reveal delay-{{ (($loop->index % 3) + 1) * 100 }}">
                            <div class="price-card h-100">
                                {{-- Package Image --}}
                                <div class="pkg-img-wrap" style="aspect-ratio: 4/5;">
                                    @if($package->image)
                                        <img src="{{ str_starts_with($package->image, 'images/') ? asset($package->image) : asset('storage/' . $package->image) }}" alt="{{ $package->name }}" style="object-fit: cover;">
                                    @else
                                        <div class="pkg-no-img">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="text-center mb-2">
                                    <small>{{ $category->name }}</small>
                                    <h3 class="mt-1" style="font-size: 21px;">{{ $package->name }}</h3>
                                </div>

                                <p class="text-center px-1" style="font-size: 13.5px; line-height: 1.6; color: #6f625c; min-height: 44px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $package->description }}</p>

                                <div class="pkg-divider"><i class="bi bi-suit-diamond-fill"></i></div>

                                <div class="pkg-price-block">
                                    <div class="price">Rp{{ number_format($package->price, 0, ',', '.') }}</div>
                                    <div class="dp">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</div>
                                </div>

                                <div class="pkg-divider"><i class="bi bi-suit-diamond-fill"></i></div>

                                @if($package->items->count())
                                    <ul class="mini-list mb-4 px-1">
                                        @foreach($package->items as $item)
                                            <li>{{ $item->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <a href="{{ route('paket.show', $package->code) }}" class="btn-dark-custom w-100 text-center mt-auto">Pesan Sekarang</a>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- 2. Koleksi Busana Tunggal (Baju Satuan) --}}
                @if($busanaTunggal->isNotEmpty())
                    <div class="col-12 mb-3 mt-5 scroll-reveal">
                        <h2 class="fw-bold text-gold-dark" style="font-family: Georgia, serif;">Koleksi Busana Tunggal</h2>
                        <p class="text-muted small">Penyewaan busana satuan (kebaya, gaun, atau melayu) tanpa paket aksesoris adat.</p>
                        <div class="pricelist-divider" style="height: 1px; background: linear-gradient(90deg, #d4b87a 0%, #eadfd6 60%, transparent 100%); width: 100%; margin-bottom: 20px;"></div>
                    </div>
                    @foreach($busanaTunggal as $package)
                        <div class="col-md-6 col-lg-4 scroll-reveal delay-{{ (($loop->index % 3) + 1) * 100 }}">
                            <div class="price-card h-100">
                                {{-- Package Image --}}
                                <div class="pkg-img-wrap" style="aspect-ratio: 4/5;">
                                    @if($package->image)
                                        <img src="{{ str_starts_with($package->image, 'images/') ? asset($package->image) : asset('storage/' . $package->image) }}" alt="{{ $package->name }}" style="object-fit: cover;">
                                    @else
                                        <div class="pkg-no-img">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="text-center mb-2">
                                    <small>{{ $category->name }}</small>
                                    <h3 class="mt-1" style="font-size: 21px;">{{ $package->name }}</h3>
                                </div>

                                <p class="text-center px-1" style="font-size: 13.5px; line-height: 1.6; color: #6f625c; min-height: 44px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $package->description }}</p>

                                <div class="pkg-divider"><i class="bi bi-suit-diamond-fill"></i></div>

                                <div class="pkg-price-block">
                                    <div class="price">Rp{{ number_format($package->price, 0, ',', '.') }}</div>
                                    <div class="dp">DP Rp{{ number_format($package->dp_amount, 0, ',', '.') }}</div>
                                </div>

                                <div class="pkg-divider"><i class="bi bi-suit-diamond-fill"></i></div>

                                @if($package->items->count())
                                    <ul class="mini-list mb-4 px-1">
                                        @foreach($package->items as $item)
                                            <li>{{ $item->name }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <a href="{{ route('paket.show', $package->code) }}" class="btn-dark-custom w-100 text-center mt-auto">Pesan Sekarang</a>
                            </div>
                        </div>
                    @endforeach
                @endif

            @else
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
                                @if($category->slug !== 'prewedding')
                                    <small>{{ $category->name }}</small>
                                @endif
                                <h3 class="mt-1" style="font-size: 21px;">
                                    @if($category->slug === 'prewedding')
                                        {{ trim(preg_replace('/\(\d+jt\)/i', '', $package->name)) }}
                                    @else
                                        {{ $package->name }}
                                    @endif
                                </h3>
                            </div>

                            {{-- Description --}}
                            @if(!empty($package->description) && $category->slug !== 'prewedding')
                                <p class="text-center px-1" style="font-size: 13.5px; line-height: 1.6; color: #6f625c; min-height: 44px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $package->description }}</p>
                            @endif

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
                                        <li>{{ $item->name }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <a href="{{ route('paket.show', $package->code) }}" class="btn-dark-custom w-100 text-center mt-auto">Pesan Sekarang</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
