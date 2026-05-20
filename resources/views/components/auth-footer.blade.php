<footer class="footer">
    <div class="footer-grid">
        <div>
            <div class="brand footer-brand">
                <img src="{{ asset('images/logo.png') }}" class="brand-logo" alt="Logo">
                <div>
                    <div class="brand-title">LISA YULI BELTI</div>
                    <div class="brand-subtitle">WEDDING GALLERY DAN MAKEUP ARTIST</div>
                </div>
            </div>

            <p>
                LVB adalah wedding gallery dan makeup artist yang menghadirkan
                layanan elegan, premium, dan terpercaya untuk hari spesial Anda.
            </p>

            <p class="footer-social">
                <i class="bi bi-instagram"></i>
                <a href="https://www.instagram.com/lisayulibelti?igsh=MThndnE2OXNueXRhaw==" target="_blank" rel="noopener noreferrer">
                    @lisayulibelti
                </a>
            </p>
        </div>

        <div>
            <h4>Navigasi</h4>
            <p><a href="{{ route('home') }}">Home</a></p>
            <p><a href="#">Profil Usaha</a></p>
            <p><a href="#">Layanan</a></p>
            <p><a href="#">Portofolio</a></p>
            <p><a href="#">Pricelist</a></p>
        </div>

        <div class="footer-contact">
            <h4>Kontak</h4>

            <p class="footer-contact-item">
                <i class="bi bi-geo-alt"></i>
                <span>
                    Lisa Yuli Belti Wedding Gallery & Makeup Artist,<br>
                    Jl. Lintas Raya Padang-Bukittinggi,<br>
                    Pasar Lubuk Alung, Kec. Lubuk Alung,<br>
                    Kab. Padang Pariaman, Sumatera Barat, ID 25581
                </span>
            </p>

            <p class="footer-contact-item">
                <i class="bi bi-telephone"></i>

                <span>
                    <a href="https://wa.me/6281227545591" target="_blank">
                        Wa admin makeup: +62 812-2754-5591
                    </a><br>

                    <a href="https://wa.me/6283112269289" target="_blank">
                        Wa admin gallery: +62 831-1226-9289
                    </a>
                </span>

                {{-- <span>Wa admin makeup: +62 812-2754-5591<br></br>
                      Wa admin gallery: +62 831-1226-9289
                </span> --}}
            </p>

            <p class="footer-contact-item">
                <i class="bi bi-clock"></i>
                <span>Senin – Minggu, 10.00 – 21.00 WIB</span>
            </p>
        </div>
    </div>

    <div class="copyright">
        © 2026 LISA YULI BELTI — Wedding Gallery dan Makeup Artist. Semua hak dilindungi.
    </div>
</footer>
