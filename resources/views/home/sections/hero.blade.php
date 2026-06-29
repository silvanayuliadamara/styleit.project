<section class="hero-section premium-hero overflow-hidden">
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 scroll-reveal fade-left">
                <p class="hero-label">Wedding Gallery And Makeup Artist</p>
                <h1 class="gold-gradient-text">LISA YULI<br>BELTI</h1>
                <p class="hero-welcome-text">Selamat datang di rumah kecantikan kami. <strong>Kami hadir</strong> untuk mewujudkan momen sakral Anda dengan sentuhan elegan, glamor lembut, dan kehangatan yang tulus dari hati.</p>
                <div class="hero-actions">
                    <a href="{{ route('layanan.index') }}" class="btn-dark-custom">Lihat Layanan <i
                            class="bi bi-arrow-right"></i></a>
                    <a href="{{ route('pricelist') }}" class="btn-outline-custom">Pesan Sekarang <i
                            class="bi bi-arrow-right"></i></a>
                </div>
                <div class="glass-stats-card hero-stats">
                    <span class="hero-stat-item">
                        <i class="bi bi-star-fill"></i>
                        <strong class="count-up" data-target="4.9" data-decimal="true">0</strong>
                        <span class="hero-stat-label">Rating</span>
                    </span>
                    <span class="hero-stat-divider"></span>
                    <span class="hero-stat-item">
                        <i class="bi bi-heart-fill"></i>
                        <strong class="count-up" data-target="500">0</strong><strong>+</strong>
                        <span class="hero-stat-label">Pengantin</span>
                    </span>
                    <span class="hero-stat-divider"></span>
                    <span class="hero-stat-item">
                        <i class="bi bi-award-fill"></i>
                        <strong class="count-up" data-target="7">0</strong><strong>+</strong>
                        <span class="hero-stat-label">Tahun</span>
                    </span>
                </div>
            </div>
            <div class="col-lg-5 scroll-reveal fade-right">
                <div class="hero-carousel-wrap">
                    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="hero-slide-card" style="background-image: url('{{ asset('images/categories/wedding/wedding_couple.jpg') }}');">
                                    <div class="slide-overlay"></div>
                                    <div class="slide-content">
                                        <blockquote>“Hari paling bahagia hidup saya.”</blockquote>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="hero-slide-card" style="background-image: url('{{ asset('images/categories/wedding/wedding2.jpg') }}');">
                                    <div class="slide-overlay"></div>
                                    <div class="slide-content">
                                        <blockquote>“Riasannya sangat halus & awet seharian.”</blockquote>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="hero-slide-card" style="background-image: url('{{ asset('images/categories/wedding/wedding_couple2.jpg') }}');">
                                    <div class="slide-overlay"></div>
                                    <div class="slide-content">
                                        <blockquote>“Sangat profesional & gaunnya mewah.”</blockquote>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-indicators-custom">
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
