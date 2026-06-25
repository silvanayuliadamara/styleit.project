<section class="section-padding testimoni-section bg-dark text-white" style="background-color: #211313;">
    <div class="container">
        <div class="section-heading text-center mb-5 scroll-reveal">
            <span class="text-gold" style="color: #b08a42; letter-spacing: 2px; text-transform: uppercase; font-size: 0.85rem; font-weight: 700;">Ulasan Pelanggan</span>
            <h2 class="text-white" style="color: #efe2d5; font-family: Georgia, serif; font-size: 2.25rem; font-weight: 500; margin-top: 0.5rem;">Apa Kata Mereka?</h2>
            <div class="heading-divider"></div>
            <p class="text-muted mt-3" style="color: #a39b8f !important;">Kisah nyata kebahagiaan dari para pelanggan setia Lisa Yuli Belti.</p>
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
