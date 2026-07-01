@extends('layouts.app', ['title' => 'Profil Usaha - Lisa Yuli Belti'])

@section('content')
<section class="page-hero">
    <div class="container text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="page-logo scroll-reveal scale-up">
        <p class="hero-label scroll-reveal delay-100">PROFIL USAHA</p>
        <h1 class="scroll-reveal delay-200">Wedding Gallery dan Makeup Artist</h1>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-5 align-items-center">
            <!-- Studio Image Panel with nice layout and animations -->
            <div class="col-lg-5 scroll-reveal fade-left">
                <div class="image-panel overflow-hidden position-relative border-0" 
                     style="min-height: 480px; border-radius: 24px; background: url('{{ asset('images/studio_front.png') }}') no-repeat center center; background-size: cover; box-shadow: 0 20px 50px rgba(33, 19, 19, 0.12);">
                    <div class="position-absolute bottom-0 start-0 end-0 p-4" 
                         style="background: linear-gradient(transparent, rgba(33, 19, 19, 0.9)); border-radius: 0 0 24px 24px;">
                        <span class="badge text-uppercase px-3 py-2 mb-2" 
                              style="background-color: var(--lyb-gold, #b08a42); color: #fff; font-size: 10px; letter-spacing: 1px; font-weight: 700; border-radius: 6px;">
                            Official Studio
                        </span>
                        <h4 class="text-white mb-1" style="font-family: Georgia, serif; font-weight: bold; letter-spacing: 0.5px;">LYB Studio</h4>
                        <p class="text-white-50 mb-0 small"><i class="bi bi-geo-alt-fill me-1"></i> Lubuk Alung, Sumatera Barat</p>
                    </div>
                </div>
            </div>

            <!-- Brand Story & Details -->
            <div class="col-lg-7 scroll-reveal fade-right">
                <div class="section-heading mb-4">
                    <span style="color: var(--lyb-gold, #b08a42); font-size: 12px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase;">Brand Story</span>
                    <h2 class="mt-2 mb-3" style="font-family: Georgia, serif; color: var(--lyb-dark, #211313); font-weight: 700;">Karya Lahir dari Hati</h2>
                    <p class="text-muted" style="font-size: 15px; line-height: 1.8;">Berdiri sejak 2018 oleh Lisa Yuli Belti, LYB telah mempercantik ratusan pengantin dengan sentuhan personal yang hangat. Setiap riasan kami rancang untuk menonjolkan kecantikan alami Anda.</p>
                    <p class="text-muted mb-0" style="font-size: 15px; line-height: 1.8;">LYB adalah studio makeup dan wedding gallery yang menghadirkan riasan elegan, glamor lembut, dan koleksi baju pengantin premium.</p>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-6 scroll-reveal delay-100">
                        <div class="info-card h-100">
                            <i class="bi bi-geo-alt" style="color: var(--lyb-gold, #b08a42); font-size: 28px;"></i>
                            <strong>Alamat</strong>
                            <span class="small text-muted">Jl. Lintas Raya Padang-Bukittinggi, Pasar Lubuk Alung, Sumatera Barat</span>
                        </div>
                    </div>
                    <div class="col-md-6 scroll-reveal delay-200">
                        <div class="info-card h-100">
                            <i class="bi bi-whatsapp" style="color: var(--lyb-gold, #b08a42); font-size: 28px;"></i>
                            <strong>WhatsApp</strong>
                            <span class="small text-muted">+62 812-2754-5591<br>+62 831-1226-9289</span>
                        </div>
                    </div>
                    <div class="col-md-6 scroll-reveal delay-300">
                        <div class="info-card h-100">
                            <i class="bi bi-clock" style="color: var(--lyb-gold, #b08a42); font-size: 28px;"></i>
                            <strong>Jam Operasional</strong>
                            <span class="small text-muted">Senin – Minggu, 10.00 – 21.00 WIB</span>
                        </div>
                    </div>
                    <div class="col-md-6 scroll-reveal delay-400">
                        <div class="info-card h-100">
                            <i class="bi bi-instagram" style="color: var(--lyb-gold, #b08a42); font-size: 28px;"></i>
                            <strong>Instagram</strong>
                            <span class="small text-muted">@lisayulibelti</span>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Stats Section -->
        <div class="row g-4 mt-5 pt-5 border-top justify-content-center text-center">
            <div class="col-md-4 scroll-reveal delay-100">
                <div class="p-2">
                    <h2 class="display-4 mb-2" style="font-family: Georgia, serif; color: var(--lyb-gold, #b08a42); font-weight: 700;">5+</h2>
                    <p class="text-uppercase mb-0 small fw-bold" style="letter-spacing: 2px; color: var(--lyb-dark, #211313);">Tahun Pengalaman</p>
                    <span class="text-muted small">Menghias sejak 2018</span>
                </div>
            </div>
            <div class="col-md-4 scroll-reveal delay-200">
                <div class="p-2">
                    <h2 class="display-4 mb-2" style="font-family: Georgia, serif; color: var(--lyb-gold, #b08a42); font-weight: 700;">500+</h2>
                    <p class="text-uppercase mb-0 small fw-bold" style="letter-spacing: 2px; color: var(--lyb-dark, #211313);">Pengantin Bahagia</p>
                    <span class="text-muted small">Momen spesial tak terlupakan</span>
                </div>
            </div>
            <div class="col-md-4 scroll-reveal delay-300">
                <div class="p-2">
                    <h2 class="display-4 mb-2" style="font-family: Georgia, serif; color: var(--lyb-gold, #b08a42); font-weight: 700;">100%</h2>
                    <p class="text-uppercase mb-0 small fw-bold" style="letter-spacing: 2px; color: var(--lyb-dark, #211313);">Kepuasan Pelanggan</p>
                    <span class="text-muted small">Hasil riasan yang flawless</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Credentials/Certification Section -->
<section class="section-padding pt-0">
    <div class="container">
        <div class="row g-5 align-items-center">
            <!-- Text Kredibilitas -->
            <div class="col-lg-4 scroll-reveal fade-left">
                <div class="section-heading mb-4">
                    <span style="color: var(--lyb-gold, #b08a42); font-size: 12px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase;">Kredibilitas & Lisensi</span>
                    <h2 class="mt-2 mb-3" style="font-family: Georgia, serif; color: var(--lyb-dark, #211313); font-weight: 700;">Makeup Artist Bersertifikasi Resmi</h2>
                    <p class="text-muted" style="font-size: 14px; line-height: 1.7;">Kecantikan Anda berada di tangan yang tepat. Lisa Yuli Belti adalah Makeup Artist profesional bersertifikat tingkat nasional maupun adat tradisional.</p>
                    <p class="text-muted mb-0" style="font-size: 14px; line-height: 1.7;">Dengan standar teknik riasan modern dan adat, higienitas alat yang terjamin, serta produk kosmetik premium original, kami menjamin hasil riasan yang flawless dan memukau.</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-white border rounded-circle shadow-sm" style="width: 50px; height: 50px; color: var(--lyb-gold, #b08a42); border-color: var(--lyb-line) !important; flex-shrink: 0;">
                        <i class="bi bi-patch-check-fill fs-4"></i>
                    </div>
                    <div>
                        <strong class="d-block small" style="color: var(--lyb-dark, #211313);">Double Certified MUA</strong>
                        <span class="small text-muted" style="font-size: 11px;">Tata Rias Pengantin Modern & Tradisional Adat</span>
                    </div>
                </div>
            </div>
            
            <!-- Gallery of 2 Certificates -->
            <div class="col-lg-8 scroll-reveal fade-right">
                <div class="row g-4">
                    <!-- Certificate 1: Modern Bridal -->
                    <div class="col-md-6">
                        <div class="certificate-wrapper p-3 bg-white border shadow-sm rounded-4 position-relative" style="border-radius: 20px; border: 1px solid var(--lyb-line); margin: 0 auto;">
                            <img src="{{ asset('images/sertifikat_mua.png') }}" alt="Sertifikat MUA Modern" class="img-fluid rounded-3" style="border-radius: 10px; display: block; width: 100%; aspect-ratio: 1.41/1; object-fit: cover;">
                            <div class="certificate-hover-overlay" style="border-radius: 20px;">
                                <a href="{{ asset('images/sertifikat_mua.png') }}" target="_blank" class="btn btn-light rounded-circle shadow" style="width: 46px; height: 46px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-fullscreen text-dark fs-6"></i>
                                </a>
                            </div>
                            <div class="text-center mt-3">
                                <strong class="small d-block text-dark">Bridal & Wedding Makeup</strong>
                                <span class="text-muted" style="font-size: 11px;">Global Makeup Academy</span>
                            </div>
                        </div>
                    </div>
                    <!-- Certificate 2: Traditional Minang Adat -->
                    <div class="col-md-6">
                        <div class="certificate-wrapper p-3 bg-white border shadow-sm rounded-4 position-relative" style="border-radius: 20px; border: 1px solid var(--lyb-line); margin: 0 auto;">
                            <img src="{{ asset('images/sertifikat_adat.png') }}" alt="Sertifikat MUA Tradisional Adat" class="img-fluid rounded-3" style="border-radius: 10px; display: block; width: 100%; aspect-ratio: 1.41/1; object-fit: cover;">
                            <div class="certificate-hover-overlay" style="border-radius: 20px;">
                                <a href="{{ asset('images/sertifikat_adat.png') }}" target="_blank" class="btn btn-light rounded-circle shadow" style="width: 46px; height: 46px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-fullscreen text-dark fs-6"></i>
                                </a>
                            </div>
                            <div class="text-center mt-3">
                                <strong class="small d-block text-dark">Traditional & Adat Minang</strong>
                                <span class="text-muted" style="font-size: 11px;">Traditional Makeup Association</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Google Maps Section -->
<section class="section-padding pt-0 scroll-reveal fade-up">
    <div class="container">
        <div class="map-card overflow-hidden shadow-sm rounded-4 border bg-white" style="border-radius: 24px; border: 1px solid var(--lyb-line); box-shadow: 0 10px 30px rgba(33, 19, 19, 0.05);">
            <div class="row g-0 align-items-center">
                <div class="col-lg-4 p-5">
                    <div class="section-heading mb-3">
                        <span style="color: var(--lyb-gold, #b08a42); font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">Lokasi Studio</span>
                        <h3 class="mt-2 mb-3" style="font-family: Georgia, serif; color: var(--lyb-dark, #211313); font-weight: 700; font-size: 28px;">Temukan Kami di Google Maps</h3>
                        <p class="text-muted small" style="line-height: 1.6;">Rencanakan kunjungan Anda untuk konsultasi makeup pengantin, fitting gaun, atau pemotretan. Kami siap menyambut Anda dengan pelayanan terbaik.</p>
                    </div>
                    <a href="https://maps.google.com/?q=Pasar%20Lubuk%20Alung,%20Padang%20Pariaman" target="_blank" class="btn btn-dark-custom d-inline-flex align-items-center gap-2 px-4 py-3" style="border-radius: 12px; font-family: Arial, sans-serif; font-size: 13px; font-weight: 700;">
                        <i class="bi bi-geo-alt"></i> Petunjuk Arah
                    </a>
                </div>
                <div class="col-lg-8">
                    <div class="map-embed-wrapper position-relative" style="min-height: 400px;">
                        <iframe 
                            src="https://maps.google.com/maps?q=-0.6780417,100.2902302&hl=id&z=16&output=embed" 
                            width="100%" 
                            height="400" 
                            style="border:0; display: block;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
