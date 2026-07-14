@extends('layouts.app', ['title' => 'Ulasan ' . $package->name . ' - Lisa Yuli Belti'])

@push('styles')
    <style>
        .shopee-reviews-container {
            background-color: #f5f5f5;
            min-height: 100vh;
            padding-bottom: 50px;
        }

        /* Shopee-style Breadcrumb / Header */
        .shopee-product-header {
            background: #fff;
            padding: 20px 0;
            border-bottom: 1px solid #e8e8e8;
            margin-bottom: 20px;
        }
        
        .shopee-back-link {
            color: #b08a42;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .shopee-back-link:hover {
            color: #211313;
        }

        .shopee-title {
            font-size: 22px;
            font-weight: 600;
            color: #222;
            margin-top: 10px;
            margin-bottom: 2px;
        }
        
        .shopee-subtitle {
            font-size: 13px;
            color: #757575;
        }

        /* Shopee Overall Rating Box */
        .shopee-rating-box {
            background-color: #fffbf8;
            border: 1px solid #f9ede5;
            border-radius: 4px;
            padding: 30px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 30px;
        }

        .shopee-rating-left {
            text-align: center;
            min-width: 140px;
        }

        .shopee-rating-score {
            font-size: 30px;
            font-weight: 600;
            color: #b08a42; /* Shopee uses orange, we use gold */
        }
        .shopee-rating-out-of {
            font-size: 16px;
            color: #b08a42;
        }

        .shopee-rating-stars {
            color: #b08a42;
            font-size: 20px;
            margin: 6px 0;
        }

        .shopee-rating-right {
            flex-grow: 1;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .shopee-filter-tab {
            background: #fff;
            border: 1px solid rgba(0,0,0,.09);
            color: rgba(0,0,0,.8);
            font-size: 14px;
            height: 32px;
            line-height: 30px;
            padding: 0 15px;
            border-radius: 2px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
            box-shadow: 0 1px 1px rgba(0,0,0,.02);
        }

        .shopee-filter-tab:hover {
            border-color: #b08a42;
            color: #b08a42;
        }

        .shopee-filter-tab.active {
            border-color: #b08a42;
            color: #b08a42;
            background: #fff;
        }

        /* Shopee Review List */
        .shopee-reviews-list {
            background: #white;
            border-radius: 4px;
            box-shadow: 0 1px 1px rgba(0,0,0,.05);
        }

        .shopee-review-item {
            display: flex;
            gap: 15px;
            padding: 20px 30px;
            border-bottom: 1px solid #f1f1f1;
            background: #fff;
        }
        
        .shopee-review-item:last-child {
            border-bottom: none;
        }

        .shopee-avatar-column {
            flex-shrink: 0;
        }

        .shopee-avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f5f5f5;
            color: #666;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e0e0e0;
        }

        .shopee-content-column {
            flex-grow: 1;
        }

        .shopee-username {
            font-size: 12px;
            color: rgba(0,0,0,.87);
            font-weight: 500;
        }

        .shopee-review-stars {
            color: #ffca0d; /* Standard star gold */
            font-size: 11px;
            margin: 4px 0;
        }

        .shopee-meta-row {
            font-size: 12px;
            color: rgba(0,0,0,.54);
            margin-bottom: 8px;
        }

        .shopee-comment-text {
            font-size: 14px;
            color: rgba(0,0,0,.87);
            line-height: 20px;
            margin-bottom: 10px;
            white-space: pre-wrap;
        }

        /* Photo attachment thumbnail */
        .shopee-photo-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .shopee-photo-thumb {
            width: 72px;
            height: 72px;
            border: 1px solid #e8e8e8;
            object-fit: cover;
            cursor: zoom-in;
            border-radius: 2px;
            transition: opacity 0.2s ease;
        }
        .shopee-photo-thumb:hover {
            opacity: 0.85;
            border-color: #b08a42;
        }

        /* Empty state */
        .shopee-empty-reviews {
            background: #fff;
            padding: 80px 20px;
            text-align: center;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
<div class="shopee-reviews-container">
    
    {{-- Product header and back link --}}
    <div class="shopee-product-header">
        <div class="container">
            <a href="{{ route('paket.show', $package->code) }}" class="shopee-back-link">
                <i class="bi bi-chevron-left"></i> Kembali ke Detail Paket
            </a>
            <h1 class="shopee-title">Ulasan {{ $package->name }}</h1>
            <div class="shopee-subtitle">{{ $package->category->name }} &bull; Lisa Yuli Belti</div>
        </div>
    </div>

    <div class="container">
        
        {{-- Shopee Overall Rating Box --}}
        <div class="shopee-rating-box">
            
            {{-- Left Side: Rating Scores --}}
            <div class="shopee-rating-left">
                <div>
                    <span class="shopee-rating-score">{{ number_format($avgRating, 1) }}</span>
                    <span class="shopee-rating-out-of"> dari 5</span>
                </div>
                <div class="shopee-rating-stars">
                    @for($s = 1; $s <= 5; $s++)
                        <i class="bi {{ $s <= round($avgRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                    @endfor
                </div>
            </div>

            {{-- Right Side: Shopee Rating Filter Tabs --}}
            <div class="shopee-rating-right">
                <a href="{{ route('paket.ulasan', [$package->code]) }}" class="shopee-filter-tab @if(!request()->has('rating') && !request()->has('filter_type')) active @endif">
                    Semua ({{ $countAll }})
                </a>
                <a href="{{ route('paket.ulasan', [$package->code, 'rating' => 5]) }}" class="shopee-filter-tab @if(request()->rating == 5) active @endif">
                    5 Bintang ({{ $count5 }})
                </a>
                <a href="{{ route('paket.ulasan', [$package->code, 'rating' => 4]) }}" class="shopee-filter-tab @if(request()->rating == 4) active @endif">
                    4 Bintang ({{ $count4 }})
                </a>
                <a href="{{ route('paket.ulasan', [$package->code, 'rating' => 3]) }}" class="shopee-filter-tab @if(request()->rating == 3) active @endif">
                    3 Bintang ({{ $count3 }})
                </a>
                <a href="{{ route('paket.ulasan', [$package->code, 'rating' => 2]) }}" class="shopee-filter-tab @if(request()->rating == 2) active @endif">
                    2 Bintang ({{ $count2 }})
                </a>
                <a href="{{ route('paket.ulasan', [$package->code, 'rating' => 1]) }}" class="shopee-filter-tab @if(request()->rating == 1) active @endif">
                    1 Bintang ({{ $count1 }})
                </a>
                <a href="{{ route('paket.ulasan', [$package->code, 'filter_type' => 'comment']) }}" class="shopee-filter-tab @if(request()->filter_type == 'comment') active @endif">
                    Dengan Komentar ({{ $countComment }})
                </a>
                <a href="{{ route('paket.ulasan', [$package->code, 'filter_type' => 'photo']) }}" class="shopee-filter-tab @if(request()->filter_type == 'photo') active @endif">
                    Dengan Foto ({{ $countPhoto }})
                </a>
            </div>
        </div>

        {{-- Shopee Reviews Feed --}}
        <div class="shopee-reviews-list">
            @forelse ($reviews as $rev)
                @php
                    $maskedName = $rev->user->name ?? 'Pelanggan';
                    if (strlen($maskedName) > 2) {
                        $maskedName = substr($maskedName, 0, 1) . str_repeat('*', strlen($maskedName) - 2) . substr($maskedName, -1);
                    }
                    $initial = strtoupper(substr($rev->user->name ?? 'P', 0, 1));
                @endphp
                <div class="shopee-review-item">
                    {{-- User Avatar --}}
                    <div class="shopee-avatar-column">
                        <div class="shopee-avatar-circle">
                            {{ $initial }}
                        </div>
                    </div>

                    {{-- Review Content --}}
                    <div class="shopee-content-column">
                        <div class="shopee-username">{{ $maskedName }}</div>
                        
                        <div class="shopee-review-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $rev->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>

                        <div class="shopee-meta-row">
                            {{ $rev->created_at->format('Y-m-d H:i') }} | Kategori: {{ $package->category->name }}
                        </div>

                        @if($rev->komentar)
                            <div class="shopee-comment-text">{{ $rev->komentar }}</div>
                        @endif

                        {{-- Photo attachment --}}
                        @if($rev->foto)
                            <div class="shopee-photo-gallery">
                                <img src="{{ str_starts_with($rev->foto, 'http') ? $rev->foto : asset('storage/' . $rev->foto) }}" alt="Bukti Foto Review" class="shopee-photo-thumb" onclick="expandShopeeImage(this)">
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="shopee-empty-reviews">
                    <i class="bi bi-chat-left-dots text-muted" style="font-size: 40px; color: #ccc !important;"></i>
                    <p class="text-secondary mt-3 mb-0" style="font-size: 14px;">Belum ada ulasan untuk filter ini.</p>
                </div>
            @endforelse
        </div>

        {{-- Shopee Pagination Links --}}
        @if($reviews->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $reviews->links() }}
            </div>
        @endif

    </div>
</div>

{{-- Dynamic modal wrapper for zooming image --}}
<div class="modal fade" id="shopeeImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 4px; border: none; background: transparent;">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <img id="shopeeModalImg" src="" alt="Zoom Ulasan" style="max-width: 100%; max-height: 80vh; border-radius: 4px; border: 4px solid #fff; box-shadow: 0 10px 40px rgba(0,0,0,0.5);">
            </div>
        </div>
    </div>
</div>

<script>
    function expandShopeeImage(el) {
        const modalImg = document.getElementById('shopeeModalImg');
        modalImg.src = el.src;
        const myModal = new bootstrap.Modal(document.getElementById('shopeeImageModal'));
        myModal.show();
    }
</script>
@endsection
