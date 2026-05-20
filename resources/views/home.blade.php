@extends('layouts.app')

@section('content')
<section class="hero-section">
    <div class="hero-content">
        <p class="hero-label">WEDDING GALLERY & MAKEUP ARTIST</p>
        <h1>Riasan Elegan untuk Hari Spesial Anda</h1>
        <p>
            Lisa Yuli Belti menghadirkan layanan makeup, wedding gallery,
            dan baju pengantin dengan sentuhan premium dan terpercaya.
        </p>

        <div class="hero-actions">
            <a href="#" class="btn-dark-custom">Lihat Layanan -></a>
            <a href="{{ route('login') }}" class="btn-outline-custom">Booking Sekarang -></a>
        </div>
    </div>
</section>
@endsection
