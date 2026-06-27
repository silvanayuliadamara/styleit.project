<section class="page-hero-compact">
    <div class="container">
        <a href="{{ route('layanan.index') }}" class="text-decoration-none d-inline-flex align-items-center gap-2 back-link mb-4" style="font-size: 14px; font-weight: 500; color: var(--lyb-muted);">
            <i class="bi bi-arrow-left"></i> Kembali ke Layanan
        </a>
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h1>Keranjang</h1>
                <p class="outfit-font">{{ count($cart) }} layanan premium dalam keranjang Anda</p>
            </div>
            <div class="col-md-6">
                <div class="checkout-progress">
                    <div class="progress-step active">
                        <div class="step-icon"><i class="bi bi-cart3"></i></div>
                        <div class="step-label">Keranjang</div>
                    </div>
                    <div class="progress-step">
                        <div class="step-icon">2</div>
                        <div class="step-label">Booking</div>
                    </div>
                    <div class="progress-step">
                        <div class="step-icon">3</div>
                        <div class="step-label">Selesai</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
