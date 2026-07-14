<section class="section-padding testimoni-section bg-dark text-white" style="background-color: #211313;">
    <div class="container">
        <div class="section-heading text-center mb-5 scroll-reveal">
            <span class="text-gold" style="color: #b08a42; letter-spacing: 2px; text-transform: uppercase; font-size: 0.85rem; font-weight: 700;">Ulasan Pelanggan</span>
            <h2 class="text-white" style="color: #efe2d5; font-family: Georgia, serif; font-size: 2.25rem; font-weight: 500; margin-top: 0.5rem;">Apa Kata Mereka?</h2>
            <div class="heading-divider"></div>
            @if(isset($reviewStats) && $reviewStats['total_count'] > 0)
                <div class="mt-3 d-flex align-items-center justify-content-center gap-2">
                    <div style="display: flex; gap: 2px; color: #b08a42; font-size: 16px;">
                        @for($s = 1; $s <= 5; $s++)
                            <i class="bi {{ $s <= round($reviewStats['avg_rating']) ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </div>
                    <span style="color: #efe2d5; font-size: 14px; font-weight: 600;">{{ number_format($reviewStats['avg_rating'], 1) }}</span>
                    <span style="color: #a39b8f; font-size: 13px;">dari {{ $reviewStats['total_count'] }}+ ulasan</span>
                </div>
            @else
                <p class="text-muted mt-3" style="color: #a39b8f !important;">Kisah nyata kebahagiaan dari para pelanggan setia Lisa Yuli Belti.</p>
            @endif
        </div>

        @if($reviews->isEmpty())
            <div class="text-center py-5 scroll-reveal">
                <p class="text-muted" style="font-family: Arial, sans-serif; font-size: 15px;">Belum ada ulasan untuk ditampilkan.</p>
            </div>
        @else
            {{-- Carousel Container --}}
            <div class="testimoni-carousel-wrapper scroll-reveal" style="position: relative; overflow: hidden;">
                <div class="testimoni-carousel-track" id="testimoniTrack" style="display: flex; transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);">
                    @php
                        $slides = $reviews->chunk(3);
                    @endphp
                    @foreach($slides as $slideIndex => $slideReviews)
                        <div class="testimoni-slide" style="min-width: 100%; flex-shrink: 0;">
                            <div class="row g-4 justify-content-center">
                                @foreach($slideReviews as $review)
                                    <div class="col-md-6 col-lg-4">
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
                        </div>
                    @endforeach
                </div>

                {{-- Navigation Arrows --}}
                @if($slides->count() > 1)
                    <button class="testimoni-nav-btn prev" id="testimoniPrev" aria-label="Previous">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="testimoni-nav-btn next" id="testimoniNext" aria-label="Next">
                        <i class="bi bi-chevron-right"></i>
                    </button>

                    {{-- Dots Indicator --}}
                    <div class="testimoni-dots" id="testimoniDots">
                        @foreach($slides as $dotIndex => $s)
                            <button class="testimoni-dot {{ $dotIndex === 0 ? 'active' : '' }}" data-slide="{{ $dotIndex }}" aria-label="Slide {{ $dotIndex + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

    <style>
        .testimoni-carousel-wrapper {
            position: relative;
        }
        .testimoni-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(176, 138, 66, 0.15);
            border: 1px solid rgba(176, 138, 66, 0.3);
            color: #b08a42;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 10;
            backdrop-filter: blur(8px);
        }
        .testimoni-nav-btn:hover {
            background: rgba(176, 138, 66, 0.35);
            transform: translateY(-50%) scale(1.08);
        }
        .testimoni-nav-btn.prev { left: -10px; }
        .testimoni-nav-btn.next { right: -10px; }
        .testimoni-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 28px;
        }
        .testimoni-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(176, 138, 66, 0.25);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 0;
        }
        .testimoni-dot.active {
            background: #b08a42;
            width: 28px;
            border-radius: 10px;
        }

        @media (max-width: 767.98px) {
            .testimoni-nav-btn { display: none; }
            .testimoni-slide .row > div {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('testimoniTrack');
            const prevBtn = document.getElementById('testimoniPrev');
            const nextBtn = document.getElementById('testimoniNext');
            const dotsContainer = document.getElementById('testimoniDots');

            if (!track) return;

            const slides = track.querySelectorAll('.testimoni-slide');
            const totalSlides = slides.length;
            let currentSlide = 0;
            let autoSlideInterval;

            function goToSlide(index) {
                if (index < 0) index = totalSlides - 1;
                if (index >= totalSlides) index = 0;
                currentSlide = index;
                track.style.transform = `translateX(-${currentSlide * 100}%)`;

                // Update dots
                if (dotsContainer) {
                    dotsContainer.querySelectorAll('.testimoni-dot').forEach((dot, i) => {
                        dot.classList.toggle('active', i === currentSlide);
                    });
                }
            }

            function startAutoSlide() {
                autoSlideInterval = setInterval(() => {
                    goToSlide(currentSlide + 1);
                }, 6000);
            }

            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    stopAutoSlide();
                    goToSlide(currentSlide - 1);
                    startAutoSlide();
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    stopAutoSlide();
                    goToSlide(currentSlide + 1);
                    startAutoSlide();
                });
            }

            if (dotsContainer) {
                dotsContainer.querySelectorAll('.testimoni-dot').forEach(dot => {
                    dot.addEventListener('click', () => {
                        stopAutoSlide();
                        goToSlide(parseInt(dot.dataset.slide));
                        startAutoSlide();
                    });
                });
            }

            // Pause on hover
            const wrapper = track.closest('.testimoni-carousel-wrapper');
            if (wrapper) {
                wrapper.addEventListener('mouseenter', stopAutoSlide);
                wrapper.addEventListener('mouseleave', startAutoSlide);
            }

            // Touch/swipe support for mobile
            let touchStartX = 0;
            let touchEndX = 0;
            track.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
                stopAutoSlide();
            }, { passive: true });
            track.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                const diff = touchStartX - touchEndX;
                if (Math.abs(diff) > 50) {
                    if (diff > 0) goToSlide(currentSlide + 1);
                    else goToSlide(currentSlide - 1);
                }
                startAutoSlide();
            }, { passive: true });

            // Start auto-slide
            if (totalSlides > 1) {
                startAutoSlide();
            }
        });
    </script>
</section>
