@extends('layouts.app', ['title' => 'Profil Usaha - Lisa Yuli Belti'])

@section('content')
<section class="page-hero">
    <div class="container text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="page-logo">
        <p class="hero-label">PROFIL USAHA</p>
        <h1>Wedding Gallery dan Makeup Artist</h1>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5"><div class="image-panel"><i class="bi bi-shop"></i><span>LYB Studio</span></div></div>
            <div class="col-lg-7">
                <div class="section-heading">
                    <span>Brand Story</span>
                    <h2>Karya Lahir dari Hati</h2>
                    <p>Berdiri sejak 2018 oleh Lisa Yuli Belti, LYB telah mempercantik ratusan pengantin dengan sentuhan personal yang hangat. Setiap riasan kami rancang untuk menonjolkan kecantikan alami Anda.</p>
                    <p>LYB adalah studio makeup dan wedding gallery yang menghadirkan riasan elegan, glamor lembut, dan koleksi baju pengantin premium.</p>
                </div>
                <div class="row g-3">
                    <div class="col-md-6"><div class="info-card"><i class="bi bi-geo-alt"></i><strong>Alamat</strong><span>Jl. Lintas Raya Padang-Bukittinggi, Pasar Lubuk Alung, Sumatera Barat</span></div></div>
                    <div class="col-md-6"><div class="info-card"><i class="bi bi-whatsapp"></i><strong>WhatsApp</strong><span>+62 812-2754-5591<br>+62 831-1226-9289</span></div></div>
                    <div class="col-md-6"><div class="info-card"><i class="bi bi-clock"></i><strong>Jam Operasional</strong><span>Senin – Minggu, 10.00 – 21.00 WIB</span></div></div>
                    <div class="col-md-6"><div class="info-card"><i class="bi bi-instagram"></i><strong>Instagram</strong><span>@lisayulibelti</span></div></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
