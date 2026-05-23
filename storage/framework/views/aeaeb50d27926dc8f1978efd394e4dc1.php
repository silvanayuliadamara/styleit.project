<?php $__env->startSection('content'); ?>
<section class="page-hero">
    <div class="container text-center">
        <p class="hero-label"><?php echo e(strtoupper($category->headline)); ?></p>
        <h1><?php echo e($category->name); ?></h1>
        <p><?php echo e($category->description); ?></p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <?php $__currentLoopData = $category->packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4">
                    <div class="price-card h-100 <?php echo e($package->is_popular ? 'popular' : ''); ?>">
                        <?php if($package->is_popular): ?><span class="popular-badge">Terfavorit</span><?php endif; ?>
                        <small><?php echo e($category->name); ?></small>
                        <h3><?php echo e($package->name); ?></h3>
                        <p><?php echo e($package->description); ?></p>
                        <div class="price">Rp<?php echo e(number_format($package->price, 0, ',', '.')); ?></div>
                        <div class="dp">DP Rp<?php echo e(number_format($package->dp_amount, 0, ',', '.')); ?></div>
                        <?php if($package->items->count()): ?>
                            <ul class="mini-list">
                                <?php $__currentLoopData = $package->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($item->name); ?> <?php echo e($item->quantity); ?><?php echo e($item->unit); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php endif; ?>
                        <a href="<?php echo e(route('paket.show', $package->code)); ?>" class="btn-dark-custom w-100 text-center mt-auto">Pesan Sekarang</a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => $category->name . ' - Lisa Yuli Belti'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenovo\Downloads\PHP\styleit.project\resources\views/layanan/kategori.blade.php ENDPATH**/ ?>