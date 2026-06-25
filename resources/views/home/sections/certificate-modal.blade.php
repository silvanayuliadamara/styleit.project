<!-- Modal Sertifikat -->
<div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: #fbf8f5; border: 1px solid rgba(176, 138, 66, 0.3); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 50px rgba(33, 19, 19, 0.25);">
            <div class="modal-header border-0 pb-0 px-4 pt-4 d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="certificateModalLabel" style="font-family: Georgia, serif; color: var(--lyb-dark); font-weight: bold;">Sertifikat Akreditasi MUA</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <!-- Carousel for 2 Certificates -->
                <div id="certCarousel" class="carousel slide" data-bs-ride="false">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#certCarousel" data-bs-slide-to="0" class="active" style="background-color: var(--lyb-gold, #b08a42);" aria-current="true" aria-label="Sertifikat 1"></button>
                        <button type="button" data-bs-target="#certCarousel" data-bs-slide-to="1" style="background-color: var(--lyb-gold, #b08a42);" aria-label="Sertifikat 2"></button>
                    </div>
                    <div class="carousel-inner">
                        <!-- Slide 1: Modern Bridal MUA -->
                        <div class="carousel-item active">
                            <div class="position-relative" style="border: 1px solid rgba(176, 138, 66, 0.15); border-radius: 12px; overflow: hidden; background: #fff; padding: 10px; box-shadow: inset 0 0 20px rgba(0,0,0,0.02);">
                                <img src="{{ asset('images/sertifikat_mua.png') }}" alt="Sertifikat Bridal & Wedding MUA" class="img-fluid rounded shadow-sm">
                            </div>
                            <p class="mt-3 mb-0 text-muted" style="font-size: 14px; font-style: italic;">Akreditasi resmi dari Global Makeup Academy untuk program Tata Rias Pengantin & Bridal.</p>
                        </div>
                        <!-- Slide 2: Traditional Adat Minang MUA -->
                        <div class="carousel-item">
                            <div class="position-relative" style="border: 1px solid rgba(176, 138, 66, 0.15); border-radius: 12px; overflow: hidden; background: #fff; padding: 10px; box-shadow: inset 0 0 20px rgba(0,0,0,0.02);">
                                <img src="{{ asset('images/sertifikat_adat.png') }}" alt="Sertifikat MUA Tradisional Adat Minang" class="img-fluid rounded shadow-sm">
                            </div>
                            <p class="mt-3 mb-0 text-muted" style="font-size: 14px; font-style: italic;">Sertifikat Kompetensi Tata Rias Pengantin Tradisional & Adat Minang dari Traditional Makeup Association.</p>
                        </div>
                    </div>
                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#certCarousel" data-bs-slide="prev" style="width: 40px; height: 40px; top: 50%; transform: translateY(-50%); left: -14px; background: var(--lyb-dark, #211313); border-radius: 50%; opacity: 0.8;">
                        <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 16px; height: 16px;"></span>
                        <span class="visually-hidden">Sebelumnya</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#certCarousel" data-bs-slide="next" style="width: 40px; height: 40px; top: 50%; transform: translateY(-50%); right: -14px; background: var(--lyb-dark, #211313); border-radius: 50%; opacity: 0.8;">
                        <span class="carousel-control-next-icon" aria-hidden="true" style="width: 16px; height: 16px;"></span>
                        <span class="visually-hidden">Selanjutnya</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
