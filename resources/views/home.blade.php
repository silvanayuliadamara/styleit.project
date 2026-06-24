@extends('layouts.app')

@section('content')
    <section class="hero-section premium-hero overflow-hidden">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7 scroll-reveal fade-left">
                    <p class="hero-label">Wedding Gallery And Makeup Artist</p>
                    <h1 class="gold-gradient-text">LISA YULI<br>BELTI</h1>
                    <p>Setiap riasan adalah karya. Setiap pengantin adalah cerita. Wujudkan momen sakral Anda dengan
                        sentuhan elegan dan glamor lembut LYB.</p>
                    <div class="hero-actions">
                        <a href="{{ route('layanan.index') }}" class="btn-dark-custom">Lihat Layanan <i
                                class="bi bi-arrow-right"></i></a>
                        <a href="{{ route('pricelist') }}" class="btn-outline-custom">Pesan Sekarang <i
                                class="bi bi-arrow-right"></i></a>
                    </div>
                    <div class="hero-stats">
                        <span>
                            <i class="bi bi-star-fill"></i>
                            <strong>4.9</strong> · 200+ pengantin
                        </span>

                        <span>
                            <i class="bi bi-award"></i>
                            <strong>Sejak</strong> 2018
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
                                            <p>— Sasha, Wedding Gold Package</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="hero-slide-card" style="background-image: url('{{ asset('images/categories/wedding/wedding2.jpg') }}');">
                                        <div class="slide-overlay"></div>
                                        <div class="slide-content">
                                            <blockquote>“Riasannya sangat halus & awet seharian.”</blockquote>
                                            <p>— Rina, Prewedding Outdoor</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="hero-slide-card" style="background-image: url('{{ asset('images/categories/wedding/wedding_couple2.jpg') }}');">
                                        <div class="slide-overlay"></div>
                                        <div class="slide-content">
                                            <blockquote>“Sangat profesional & gaunnya mewah.”</blockquote>
                                            <p>— Dinda, Attire & Makeup Package</p>
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

    <section class="section-padding">
        <div class="container">
            <div class="section-heading text-center scroll-reveal">
                <span>Kategori Layanan</span>
                <h2>Pilih Momen Spesial Anda</h2>
                <p>Empat kategori riasan dan koleksi baju untuk setiap perayaan.</p>
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
                        <a href="{{ route('layanan.kategori', $category->slug) }}" class="category-photo-card h-100"
                             style="background-image: url('{{ asset($imagePath) }}');">

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

    <!-- Seksi Profil MUA -->
    <section class="section-padding bg-white border-top border-bottom" style="border-color: #eadfd6 !important;">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5 scroll-reveal fade-left">
                    <div class="profile-image-wrap">
                        <img src="{{ asset('images/categories/wedding/wedding_couple.jpg') }}" alt="Lisa Yuli Belti" class="profile-img-main">
                        <div class="profile-badge" data-bs-toggle="modal" data-bs-target="#certificateModal" title="Klik untuk melihat sertifikat akreditasi">
                            <i class="bi bi-award-fill"></i>
                            <span>Certified MUA</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 scroll-reveal fade-right">
                    <div class="profile-content">
                        <span class="section-kicker">Di Balik Layar</span>
                        <h2 class="profile-title">Mengenal Lisa Yuli Belti</h2>
                        <div class="profile-divider"></div>
                        <p class="lead-text">Sejak tahun 2018, kami telah mendampingi ratusan pengantin dalam mewujudkan look impian mereka di hari paling bersejarah dalam hidupnya.</p>
                        <p>Filosofi riasan kami berpusat pada penonjolan kecantikan alami Anda secara elegan. Kami percaya bahwa makeup yang baik tidak mengubah wajah Anda menjadi orang lain, melainkan memancarkan versi tercantik dari diri Anda dengan sentuhan akhir yang flawless dan tahan lama sepanjang acara.</p>
                        
                        <div class="artist-quote mt-4">
                            <p class="quote-text">“Setiap pengantin berhak merasa menjadi wanita tercantik di dunia pada hari pernikahannya, dan itu adalah misi utama saya.”</p>
                            <p class="artist-signature">— Lisa Yuli Belti</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding bg-soft">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4 scroll-reveal">
                <div class="section-heading mb-0">
                    <span>Portofolio</span>
                    <h2>Karya Terbaru</h2>
                </div>
                <a href="{{ route('portofolio') }}" class="link-gold">Lihat semua →</a>
            </div>
            <div class="row g-4">
                @foreach ($portfolioItems as $item)
                    <div class="col-md-6 col-lg-3 scroll-reveal delay-{{ ($loop->index + 1) * 100 }}">
                        <div class="portfolio-card">
                            @if($item->image)
                                <img src="{{ str_starts_with($item->image, 'images/') ? asset($item->image) : asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="portfolio-img portfolio-img-{{ $item->category }}">
                            @else
                                <div class="portfolio-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                            <h4>{{ $item->title }}</h4>
                            <span>{{ ['prewedding' => 'Prewedding', 'wedding' => 'Wedding', 'regular' => 'Regular', 'baju' => 'Khusus Baju'][$item->category] ?? ucfirst($item->category) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Seksi Alur Pemesanan Baru -->
    <section class="section-padding bg-white">
        <div class="container">
            <div class="section-heading text-center scroll-reveal">
                <span>Cara Kerja</span>
                <h2>Alur Pemesanan Layanan</h2>
                <p>Langkah mudah dan transparan untuk memesan jasa riasan dan gaun impian Anda.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3 scroll-reveal delay-100">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h4>Pilih Layanan & Tanggal</h4>
                        <p>Pilih paket riasan/gaun pengantin yang Anda butuhkan, lalu tentukan slot tanggal dan jam acara pada kalender.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 scroll-reveal delay-200">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h4>Bayar DP Instan</h4>
                        <p>Konfirmasi pemesanan Anda dengan membayar Down Payment (DP) secara aman via Midtrans Snap (Virtual Account, QRIS, E-Wallet).</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 scroll-reveal delay-300">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h4>Fitting & Konsultasi</h4>
                        <p>Datang ke galeri kami untuk melakukan fitting busana pengantin dan konsultasi look riasan gratis dengan tim kami.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 scroll-reveal delay-400">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h4>Hari Bahagia Anda</h4>
                        <p>Tim MUA dan asisten kami siap melayani Anda di lokasi acara untuk memastikan penampilan terbaik Anda di hari istimewa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Seksi FAQ Akordeon -->
    <section class="section-padding bg-white border-top" style="border-color: #eadfd6 !important;">
        <div class="container">
            <div class="section-heading text-center scroll-reveal">
                <span>Tanya Jawab</span>
                <h2>Frequently Asked Questions</h2>
                <p>Temukan jawaban cepat untuk pertanyaan-pertanyaan yang sering diajukan mengenai layanan kami.</p>
            </div>
            
            <div class="row justify-content-center scroll-reveal">
                <div class="col-lg-8">
                    <div class="accordion accordion-custom" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true">
                                    Apakah bisa melakukan reschedule (ubah tanggal) setelah DP?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ya, reschedule diperbolehkan maksimal 1 kali dengan pemberitahuan paling lambat 30 hari sebelum acara, bergantung pada ketersediaan slot kosong pada tanggal baru yang diajukan.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    Kapan fitting busana pengantin sebaiknya dilakukan?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Fitting busana/gaun pengantin sangat disarankan dilakukan 2 hingga 4 minggu sebelum hari pernikahan untuk memastikan pakaian pas sempurna dengan postur tubuh Anda saat hari-H.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                    Apakah harga paket sudah termasuk biaya transportasi MUA?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Untuk area galeri lokal, biaya transport sudah gratis. Untuk lokasi di luar area jangkauan standar atau luar kota, akan ada biaya transportasi tambahan yang disesuaikan dengan jarak lokasi acara.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                                    Bagaimana jika saya ingin menambah add-on layanan baru setelah checkout?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Tentu bisa! Anda dapat melakukan pemesanan add-on tambahan secara langsung melalui dashboard customer atau berkonsultasi dengan admin kami sebelum jadwal fitting baju berlangsung.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding testimoni-section bg-dark text-white" style="background-color: #211313;">
        <div class="container">
            <div class="section-heading text-center mb-5 scroll-reveal">
                <span class="text-gold" style="color: #b08a42; letter-spacing: 2px; text-transform: uppercase; font-size: 0.85rem; font-weight: 700;">Ulasan Pelanggan</span>
                <h2 class="text-white" style="color: #efe2d5; font-family: Georgia, serif; font-size: 2.25rem; font-weight: 500; margin-top: 0.5rem;">Apa Kata Mereka?</h2>
                <p class="text-muted" style="color: #a39b8f !important;">Kisah nyata kebahagiaan dari para pelanggan setia Lisa Yuli Belti.</p>
            </div>

            @if($reviews->isEmpty())
                <div class="text-center py-5 scroll-reveal">
                    <p class="text-muted" style="font-family: Arial, sans-serif; font-size: 15px;">Belum ada ulasan untuk ditampilkan.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($reviews as $review)
                        <div class="col-md-6 col-lg-4 scroll-reveal scale-up delay-{{ ($loop->index + 1) * 100 }}">
                            <div class="testimoni-card p-4 h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="star-display mb-3" style="color: #b08a42;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}" style="font-size: 14px; margin-right: 2px;"></i>
                                        @endfor
                                    </div>
                                    <p class="review-comment" style="font-family: Georgia, serif; font-style: italic; color: #efe2d5; line-height: 1.6; font-size: 0.95rem; margin-bottom: 1.5rem;">
                                        "{{ Str::limit($review->komentar, 250, '...') }}"
                                    </p>
                                </div>
                                <div class="border-top pt-3" style="border-color: rgba(234, 223, 214, 0.1) !important;">
                                    <h5 class="customer-name mb-1" style="font-family: Arial, sans-serif; font-weight: 700; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; color: #b08a42;">
                                        {{ $review->user->name ?? 'Pelanggan LYB' }}
                                    </h5>
                                    <p class="package-name mb-0 text-muted small" style="font-family: Arial, sans-serif; font-size: 11px;">
                                        Paket: {{ $review->package->name ?? 'Layanan' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Seksi Instagram Grid Mockup -->
    <section class="section-padding bg-soft border-top" style="border-color: #eadfd6 !important;">
        <div class="container">
            <div class="section-heading text-center scroll-reveal">
                <span>Galeri Sosial</span>
                <h2>Ikuti Perjalanan Kami di Instagram</h2>
                <p>Temukan inspirasi look harian, cuplikan di balik layar, dan karya riasan terbaru di <a href="https://instagram.com" target="_blank" class="link-gold">@lisayulibelti</a>.</p>
            </div>
            
            <div class="row g-3 scroll-reveal">
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="https://instagram.com" target="_blank" class="instagram-grid-card">
                        <img src="{{ asset('images/categories/wedding/wedding_suntiang.jpg') }}" alt="Instagram Post" class="instagram-img">
                        <div class="instagram-overlay">
                            <i class="bi bi-instagram"></i>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="https://instagram.com" target="_blank" class="instagram-grid-card">
                        <img src="{{ asset('images/categories/wedding/wedding_nude.jpg') }}" alt="Instagram Post" class="instagram-img">
                        <div class="instagram-overlay">
                            <i class="bi bi-instagram"></i>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="https://instagram.com" target="_blank" class="instagram-grid-card">
                        <img src="{{ asset('images/categories/wedding/wedding_couple.jpg') }}" alt="Instagram Post" class="instagram-img">
                        <div class="instagram-overlay">
                            <i class="bi bi-instagram"></i>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="https://instagram.com" target="_blank" class="instagram-grid-card">
                        <img src="{{ asset('images/categories/wedding/wedding_hijab.jpg') }}" alt="Instagram Post" class="instagram-img">
                        <div class="instagram-overlay">
                            <i class="bi bi-instagram"></i>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="https://instagram.com" target="_blank" class="instagram-grid-card">
                        <img src="{{ asset('images/categories/wedding/wedding1.jpg') }}" alt="Instagram Post" class="instagram-img">
                        <div class="instagram-overlay">
                            <i class="bi bi-instagram"></i>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="https://instagram.com" target="_blank" class="instagram-grid-card">
                        <img src="{{ asset('images/categories/wedding/wedding2.jpg') }}" alt="Instagram Post" class="instagram-img">
                        <div class="instagram-overlay">
                            <i class="bi bi-instagram"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Seksi Banner CTA Mewah -->
    <section class="cta-banner-section py-5 bg-white border-top border-bottom" style="border-color: #eadfd6 !important;">
        <div class="container">
            <div class="cta-glass-card p-5 scroll-reveal">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8 text-center text-lg-start">
                        <h2 class="cta-title">Wujudkan Riasan & Gaun Pengantin Impian Anda</h2>
                        <p class="cta-desc mb-0">Diskusikan tema pernikahan Anda, konsultasikan detail fitting baju, dan amankan slot tanggal keberangkatan MUA kami sebelum kehabisan.</p>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end">
                        <a href="https://wa.me/628123456789" target="_blank" class="btn-cta-gold py-3 px-4">
                            <i class="bi bi-whatsapp me-2"></i> Konsultasi via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 scroll-reveal delay-100">
                    <div class="feature-card"><i class="bi bi-shield-check"></i>
                        <h3>Riasan Tahan Lama</h3>
                        <p>Produk premium yang aman dan hasil flawless seharian.</p>
                    </div>
                </div>
                <div class="col-md-4 scroll-reveal delay-200">
                    <div class="feature-card"><i class="bi bi-chat-heart"></i>
                        <h3>Sentuhan Personal</h3>
                        <p>Konsultasi gratis untuk look yang sesuai karakter Anda.</p>
                    </div>
                </div>
                <div class="col-md-4 scroll-reveal delay-300">
                    <div class="feature-card"><i class="bi bi-people"></i>
                        <h3>Tim Profesional</h3>
                        <p>Berpengalaman menangani ratusan pernikahan dan event.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Sertifikat -->
    <div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background: #fbf8f5; border: 1px solid rgba(176, 138, 66, 0.3); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 50px rgba(33, 19, 19, 0.25);">
                <div class="modal-header border-0 pb-0 px-4 pt-4 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="certificateModalLabel" style="font-family: Georgia, serif; color: var(--lyb-dark); font-weight: bold;">Sertifikat Akreditasi MUA</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="position-relative" style="border: 1px solid rgba(176, 138, 66, 0.15); border-radius: 12px; overflow: hidden; background: #fff; padding: 10px; box-shadow: inset 0 0 20px rgba(0,0,0,0.02);">
                        <img src="{{ asset('images/sertifikat_mua.png') }}" alt="Sertifikat Akreditasi Lisa Yuli Belti" class="img-fluid rounded shadow-sm">
                    </div>
                    <p class="mt-3 mb-0 text-muted" style="font-size: 14px; font-style: italic;">Akreditasi resmi dari Global Makeup Academy untuk program Tata Rias Pengantin & Pengantin Bridal.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
