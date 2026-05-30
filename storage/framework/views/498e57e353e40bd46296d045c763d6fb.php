<?php $__env->startSection('content'); ?>
<section class="page-hero compact"><div class="container"><p class="hero-label">CHECKOUT</p><h1>Checkout Booking</h1><p>Upload bukti DP jika sudah transfer. Bisa juga checkout dulu dan upload nanti lewat admin.</p></div></section>
<section class="section-padding">
    <div class="container">
        <form action="<?php echo e(route('customer.checkout.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="glass-card">
                        <h3>Data Booking</h3>
                        <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="booking-list-item">
                                <div><strong><?php echo e($item['package_name']); ?></strong><p><?php echo e(\Illuminate\Support\Carbon::parse($item['booking_date'])->format('d M Y')); ?> · DP Rp<?php echo e(number_format($item['dp_amount'], 0, ',', '.')); ?></p></div>
                                <strong>Rp<?php echo e(number_format($item['total_price'], 0, ',', '.')); ?></strong>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="mt-4">
                            <label class="form-label fw-bold">Catatan untuk admin</label>
                            <textarea name="notes" class="form-control rounded-4" rows="4" placeholder="Contoh: request look natural, alamat acara, jam acara, dll."><?php echo e(old('notes')); ?></textarea>
                        </div>
                        <div class="mt-4">
                            <label class="form-label fw-bold">Upload Bukti DP</label>
                            <input type="file" name="proof_image" class="form-control rounded-4" accept="image/*">
                            <small class="muted">Format jpg/png/webp, maksimal 2 MB.</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="booking-panel">
                        <h3>Pembayaran DP</h3>
                        <p class="muted">Transfer DP ke rekening/nomor yang ditentukan admin, lalu upload bukti.</p>
                        <div class="total-box">
                            <div><span>Total layanan</span><strong>Rp<?php echo e(number_format(collect($cart)->sum('total_price'), 0, ',', '.')); ?></strong></div>
                            <div><span>Total DP</span><strong>Rp<?php echo e(number_format(collect($cart)->sum('dp_amount'), 0, ',', '.')); ?></strong></div>
                            <div><span>Sisa bayar</span><strong>Rp<?php echo e(number_format(collect($cart)->sum('remaining_payment'), 0, ',', '.')); ?></strong></div>
                        </div>
                        <button type="submit" class="btn-dark-custom w-100 mt-3 border-0">Buat Booking</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Checkout Booking'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenovo\Downloads\PHP\styleit.project\resources\views/customer/checkout.blade.php ENDPATH**/ ?>