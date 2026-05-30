<?php $__env->startSection('title', 'Pricelist'); ?>

<?php $__env->startSection('content'); ?>
<section class="price-page py-5">
    <div class="container price-container">

        <?php
            $priceGroups = [
                [
                    'kicker' => '',
                    'title' => 'Prewedding',
                    'packages' => [
                        [
                            'code' => 'PKG-PREWED',
                            'name' => 'Paket Prewedding',
                            'description' => 'Makeup prewedding + opsi baju pasangan',
                            'includes' => 'Henna 1x',
                            'price' => 2500000,
                            'dp' => 500000,
                        ],
                    ],
                ],
                [
                    'kicker' => 'HARI SAKRAL ANDA, SEMPURNA',
                    'title' => 'Wedding',
                    'packages' => [
                        [
                            'code' => 'PKG-WED-GOLD',
                            'name' => 'Paket Wedding Gold',
                            'description' => 'Makeup pengantin lengkap + henna 2x + melati 1x',
                            'includes' => 'Henna 2x, Melati 1x',
                            'price' => 5000000,
                            'dp' => 1000000,
                        ],
                    ],
                ],
                [
                    'kicker' => 'WISUDA & ACARA SPESIAL',
                    'title' => 'Regular',
                    'packages' => [
                        [
                            'code' => 'PKG-REG-WIS',
                            'name' => 'Paket Regular Wisuda',
                            'description' => 'Makeup wisuda glowing, maksimal 3 customer per hari',
                            'includes' => '',
                            'price' => 500000,
                            'dp' => 200000,
                        ],
                    ],
                ],
                [
                    'kicker' => 'KOLEKSI GAUN & KEBAYA',
                    'title' => 'Khusus Baju',
                    'packages' => [
                        [
                            'code' => 'PKG-BAJU-PASANGAN',
                            'name' => 'Paket Baju Pasangan',
                            'description' => 'Sewa baju pengantin pasangan, koleksi premium',
                            'includes' => '',
                            'price' => 750000,
                            'dp' => 250000,
                        ],
                    ],
                ],
            ];
        ?>

        <?php $__currentLoopData = $priceGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="price-category mb-5">
                <?php if($group['kicker']): ?>
                    <p class="section-kicker mb-1"><?php echo e($group['kicker']); ?></p>
                <?php endif; ?>

                <h2 class="price-category-title"><?php echo e($group['title']); ?></h2>
                <div class="price-line mb-4"></div>

                <?php $__currentLoopData = $group['packages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="price-card mb-4">
                        <div class="price-card-left">
                            <h5><?php echo e($package['name']); ?></h5>
                            <p><?php echo e($package['description']); ?></p>

                            <?php if($package['includes']): ?>
                                <small>Termasuk: <?php echo e($package['includes']); ?></small>
                            <?php endif; ?>

                            <div class="mt-3">
                                <a href="<?php echo e(url('/paket/' . $package['code'])); ?>" class="btn btn-dark btn-sm rounded-pill px-3">
                                    Pesan Sekarang
                                </a>
                            </div>
                        </div>

                        <div class="price-card-right">
                            <h4>Rp<?php echo e(number_format($package['price'], 0, ',', '.')); ?></h4>
                            <small>DP Rp<?php echo e(number_format($package['dp'], 0, ',', '.')); ?></small>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenovo\Downloads\PHP\styleit.project\resources\views/pricelist.blade.php ENDPATH**/ ?>