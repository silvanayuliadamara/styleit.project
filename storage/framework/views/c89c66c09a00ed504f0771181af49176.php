<?php $__env->startSection('content'); ?>
<section class="page-hero">
    <div class="container text-center">
        <p class="hero-label">LAYANAN</p>
        <h1>Kategori Layanan</h1>
        <p>Pilih kategori untuk melihat paket lengkap dan memilih jadwal booking.</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6">
                    <a href="<?php echo e(route('layanan.kategori', $category->slug)); ?>" class="service-card service-card-lg h-100">
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Layanan - Lisa Yuli Belti'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenovo\Downloads\PHP\styleit.project\resources\views/layanan/index.blade.php ENDPATH**/ ?>