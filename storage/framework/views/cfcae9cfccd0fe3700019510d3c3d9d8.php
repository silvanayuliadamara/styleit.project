<?php $__env->startSection('content'); ?>
<section class="hero-section premium-hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <p class="hero-label">PREMIUM BRIDAL STUDIO</p>
                <h1>LISA YULI<br>BELTI</h1>
                <p>Setiap riasan adalah karya. Setiap pengantin adalah cerita. Wujudkan momen sakral Anda dengan sentuhan elegan dan glamor lembut LYB.</p>
                <div class="hero-actions">
                    <a href="<?php echo e(route('layanan.index')); ?>" class="btn-dark-custom">Lihat Layanan <i class="bi bi-arrow-right"></i></a>
                    <a href="<?php echo e(route('pricelist')); ?>" class="btn-outline-custom">Pesan Sekarang <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="hero-stats">
                    <span><strong>4.9</strong> · 200+ pengantin</span>
                    <span><strong>Sejak</strong> 2018</span>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-card">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo LYB">
                    <blockquote>“Hari paling bahagia hidup saya.”</blockquote>
                    <p>— Sasha, Wedding Gold Package</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="section-heading text-center">
            <span>Kategori Layanan</span>
            <h2>Pilih Momen Spesial Anda</h2>
            <p>Empat kategori riasan dan koleksi baju untuk setiap perayaan.</p>
        </div>
        <div class="row g-4">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-3">
                    <a href="<?php echo e(route('layanan.kategori', $category->slug)); ?>" class="service-card h-100">
                        <i class="bi <?php echo e($category->icon); ?>"></i>
                        <small><?php echo e($category->headline); ?></small>
                        <h3><?php echo e($category->name); ?></h3>
                        <p><?php echo e($category->description); ?></p>
                        <span>Lihat Detail <i class="bi bi-arrow-right"></i></span>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<section class="section-padding bg-soft">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div class="section-heading mb-0">
                <span>Portofolio</span>
                <h2>Karya Terbaru</h2>
            </div>
            <a href="<?php echo e(route('portofolio')); ?>" class="link-gold">Lihat semua →</a>
        </div>
        <div class="row g-4">
            <?php $__currentLoopData = $portfolioItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-3">
                    <div class="portfolio-card">
                        <div class="portfolio-placeholder"><i class="bi bi-image"></i></div>
                        <h4><?php echo e($item->title); ?></h4>
                        <span><?php echo e($item->category); ?></span>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4"><div class="feature-card"><i class="bi bi-shield-check"></i><h3>Riasan Tahan Lama</h3><p>Produk premium yang aman dan hasil flawless seharian.</p></div></div>
            <div class="col-md-4"><div class="feature-card"><i class="bi bi-chat-heart"></i><h3>Sentuhan Personal</h3><p>Konsultasi gratis untuk look yang sesuai karakter Anda.</p></div></div>
            <div class="col-md-4"><div class="feature-card"><i class="bi bi-people"></i><h3>Tim Profesional</h3><p>Berpengalaman menangani ratusan pernikahan dan event.</p></div></div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenovo\Downloads\PHP\styleit.project\resources\views/home.blade.php ENDPATH**/ ?>