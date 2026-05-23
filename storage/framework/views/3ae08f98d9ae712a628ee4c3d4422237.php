<?php $__env->startSection('content'); ?>
<section class="page-hero compact"><div class="container"><p class="hero-label">KERANJANG</p><h1>Keranjang Booking</h1><p>Periksa paket yang akan kamu checkout.</p></div></section>
<section class="section-padding">
    <div class="container">
        <?php if(empty($cart)): ?>
            <div class="glass-card text-center"><h3>Keranjang masih kosong</h3><p class="muted">Pilih layanan untuk mulai booking.</p><a href="<?php echo e(route('layanan.index')); ?>" class="btn-dark-custom">Lihat Layanan</a></div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="cart-item">
                            <div>
                                <small><?php echo e($item['category_name']); ?></small>
                                <h3><?php echo e($item['package_name']); ?></h3>
                                <p>Tanggal: <?php echo e(\Illuminate\Support\Carbon::parse($item['booking_date'])->format('d M Y')); ?> · Softlens: <?php echo e($item['softlens'] ? 'Ya' : 'Tidak'); ?></p>
                                <?php if(count($item['addons'])): ?>
                                    <p>Add-on: <?php echo e(collect($item['addons'])->pluck('name')->join(', ')); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="text-lg-end">
                                <strong>Rp<?php echo e(number_format($item['total_price'], 0, ',', '.')); ?></strong>
                                <form action="<?php echo e(route('customer.cart.destroy', $item['key'])); ?>" method="POST" class="mt-2"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-outline-danger rounded-pill">Hapus</button></form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="col-lg-4">
                    <div class="booking-panel">
                        <h3>Ringkasan</h3>
                        <div class="total-box">
                            <div><span>Total layanan</span><strong>Rp<?php echo e(number_format(collect($cart)->sum('total_price'), 0, ',', '.')); ?></strong></div>
                            <div><span>Total DP</span><strong>Rp<?php echo e(number_format(collect($cart)->sum('dp_amount'), 0, ',', '.')); ?></strong></div>
                        </div>
                        <a href="<?php echo e(route('customer.checkout.index')); ?>" class="btn-dark-custom w-100 text-center mt-3">Lanjut Checkout</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Keranjang Booking'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenovo\Downloads\PHP\styleit.project\resources\views/customer/cart.blade.php ENDPATH**/ ?>