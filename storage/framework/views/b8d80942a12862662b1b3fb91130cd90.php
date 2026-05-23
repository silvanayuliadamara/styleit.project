<?php $__env->startSection('content'); ?>
<section class="page-hero compact"><div class="container"><a href="<?php echo e(route('customer.bookings.index')); ?>" class="link-gold"><i class="bi bi-arrow-left"></i> Kembali</a><p class="hero-label mt-3">DETAIL BOOKING</p><h1><?php echo e($booking->booking_code); ?></h1><p><?php echo e($booking->package->name); ?> · <?php echo e($booking->booking_date->format('d M Y')); ?></p></div></section>
<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-card mb-4">
                    <h3>Informasi Paket</h3>
                    <div class="booking-list-item"><div><strong><?php echo e($booking->package->name); ?></strong><p><?php echo e($booking->package->category->name); ?> · Softlens: <?php echo e($booking->softlens ? 'Ya' : 'Tidak'); ?></p></div><strong>Rp<?php echo e(number_format($booking->subtotal, 0, ',', '.')); ?></strong></div>
                    <?php if($booking->addons->count()): ?>
                        <h4 class="mt-4">Add-on</h4>
                        <?php $__currentLoopData = $booking->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="booking-list-item"><span><?php echo e($addon->name); ?></span><strong>Rp<?php echo e(number_format($addon->pivot->price, 0, ',', '.')); ?></strong></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php if($booking->notes): ?><h4 class="mt-4">Catatan</h4><p><?php echo e($booking->notes); ?></p><?php endif; ?>
                </div>

                <div class="glass-card">
                    <h3>Bukti Pembayaran</h3>
                    <?php $__empty_1 = true; $__currentLoopData = $booking->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="booking-list-item">
                            <div><strong>DP Rp<?php echo e(number_format($payment->amount, 0, ',', '.')); ?></strong><p>Status: <?php echo e($payment->status); ?> · <?php echo e(optional($payment->paid_at)->format('d M Y H:i')); ?></p></div>
                            <?php if($payment->proof_image): ?><a href="<?php echo e(asset('storage/'.$payment->proof_image)); ?>" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill">Lihat Bukti</a><?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="muted">Belum ada bukti pembayaran. Hubungi admin jika ingin upload bukti manual.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="booking-panel">
                    <h3>Ringkasan</h3>
                    <div class="total-box">
                        <div><span>Status booking</span><strong><?php echo e(str_replace('_', ' ', $booking->status)); ?></strong></div>
                        <div><span>Status bayar</span><strong><?php echo e(str_replace('_', ' ', $booking->payment_status)); ?></strong></div>
                        <div><span>Total layanan</span><strong>Rp<?php echo e(number_format($booking->total_price, 0, ',', '.')); ?></strong></div>
                        <div><span>DP</span><strong>Rp<?php echo e(number_format($booking->dp_amount, 0, ',', '.')); ?></strong></div>
                        <div><span>Sisa</span><strong>Rp<?php echo e(number_format($booking->remaining_payment, 0, ',', '.')); ?></strong></div>
                    </div>
                    <a href="https://wa.me/6281227545591?text=Halo%20admin%20LYB,%20saya%20mau%20tanya%20booking%20<?php echo e($booking->booking_code); ?>" target="_blank" class="btn-whatsapp w-100 mt-3"><i class="bi bi-whatsapp"></i> Tanya Admin</a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Detail Booking ' . $booking->booking_code], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenovo\Downloads\PHP\styleit.project\resources\views/customer/bookings/show.blade.php ENDPATH**/ ?>