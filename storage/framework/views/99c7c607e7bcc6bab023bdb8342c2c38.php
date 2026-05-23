<?php $__env->startSection('content'); ?>
<section class="page-hero compact"><div class="container"><p class="hero-label">BOOKING SAYA</p><h1>Riwayat Booking</h1><p>Lihat status booking dan pembayaran kamu.</p></div></section>
<section class="section-padding">
    <div class="container">
        <div class="glass-card">
            <div class="table-responsive">
                <table class="table align-middle customer-table">
                    <thead><tr><th>Kode</th><th>Paket</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Pembayaran</th><th></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($booking->booking_code); ?></strong></td>
                                <td><?php echo e($booking->package->name); ?><br><small><?php echo e($booking->package->category->name); ?></small></td>
                                <td><?php echo e($booking->booking_date->format('d M Y')); ?></td>
                                <td>Rp<?php echo e(number_format($booking->total_price, 0, ',', '.')); ?></td>
                                <td><span class="status-badge <?php echo e($booking->status); ?>"><?php echo e(str_replace('_', ' ', $booking->status)); ?></span></td>
                                <td><span class="status-badge payment"><?php echo e(str_replace('_', ' ', $booking->payment_status)); ?></span></td>
                                <td><a href="<?php echo e(route('customer.bookings.show', $booking->booking_code)); ?>" class="btn btn-sm btn-outline-dark rounded-pill">Detail</a></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7" class="text-center py-5">Belum ada booking.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => 'Booking Saya'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenovo\Downloads\PHP\styleit.project\resources\views/customer/bookings/index.blade.php ENDPATH**/ ?>