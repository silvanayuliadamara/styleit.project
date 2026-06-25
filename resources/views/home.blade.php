@extends('layouts.app')

@section('content')
    @include('home.sections.hero')
    @include('home.sections.categories')
    @include('home.sections.profile')
    @include('home.sections.portfolio')
    @include('home.sections.timeline')
    @include('home.sections.faq')
    @include('home.sections.testimonials')
    @include('home.sections.instagram')
    @include('home.sections.cta')
    @include('home.sections.advantages')
    @include('home.sections.certificate-modal')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Count-Up Animation
    const countEls = document.querySelectorAll('.count-up');
    if (countEls.length === 0) return;

    let animated = false;

    const countObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !animated) {
                animated = true;
                countEls.forEach(el => {
                    const target = parseFloat(el.dataset.target);
                    const isDecimal = el.dataset.decimal === 'true';
                    const duration = 2000;
                    const startTime = performance.now();

                    function animate(currentTime) {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        // Ease out cubic
                        const eased = 1 - Math.pow(1 - progress, 3);
                        const current = eased * target;

                        if (isDecimal) {
                            el.textContent = current.toFixed(1);
                        } else {
                            el.textContent = Math.floor(current);
                        }

                        if (progress < 1) {
                            requestAnimationFrame(animate);
                        }
                    }
                    requestAnimationFrame(animate);
                });
            }
        });
    }, { threshold: 0.3 });

    const heroStats = document.querySelector('.hero-stats');
    if (heroStats) countObserver.observe(heroStats);
});
</script>
@endpush
