@extends('layouts.app', ['title' => 'Layanan - Lisa Yuli Belti'])

@section('content')
<section class="page-hero">
    <div class="container text-center">
        <p class="hero-label">LAYANAN</p>
        <h1>Kategori Layanan</h1>
        <p>Pilih kategori untuk melihat paket lengkap dan memilih jadwal booking.</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            @foreach($categories as $category)
                <div class="col-md-6">
                    <a href="{{ route('layanan.kategori', $category->slug) }}" class="service-card service-card-lg h-100">
                        <i class="bi {{ $category->icon }}"></i>
                        <small>{{ $category->headline }}</small>
                        <h3>{{ $category->name }}</h3>
                        <p>{{ $category->description }}</p>
                        <span>Lihat Detail <i class="bi bi-arrow-right"></i></span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
