<?php $__env->startSection('content'); ?>
<section class="page-hero compact">
    <div class="container">
        <a href="<?php echo e(route('layanan.kategori', $package->category->slug)); ?>" class="link-gold"><i class="bi bi-arrow-left"></i> Kembali ke <?php echo e($package->category->name); ?></a>
        <div class="row align-items-end g-4 mt-2">
            <div class="col-lg-8">
                <p class="hero-label"><?php echo e($package->category->headline); ?></p>
                <h1><?php echo e($package->name); ?></h1>
                <p><?php echo e($package->description); ?></p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="price mb-1">Rp<?php echo e(number_format($package->price, 0, ',', '.')); ?></div>
                <div class="dp">DP Rp<?php echo e(number_format($package->dp_amount, 0, ',', '.')); ?> · Sisa Rp<?php echo e(number_format($package->price - $package->dp_amount, 0, ',', '.')); ?></div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <form action="<?php echo e(route('customer.cart.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="package_id" value="<?php echo e($package->id); ?>">
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="glass-card mb-4">
                        <h3>Yang Termasuk</h3>
                        <div class="row g-3 mt-1">
                            <?php $__currentLoopData = $package->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6"><div class="check-item"><i class="bi bi-check-circle-fill"></i><?php echo e($item->name); ?> <?php echo e($item->quantity); ?><?php echo e($item->unit); ?></div></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="glass-card mb-4">
                        <div class="d-flex justify-content-between gap-3 flex-wrap">
                            <div>
                                <h3>Pilih Tanggal Booking</h3>
                                <p class="muted mb-0">Kalender 2 bulan ke depan. Regular maksimal <?php echo e($package->quota_per_day); ?> customer/hari.</p>
                            </div>
                            <div class="calendar-legend"><span class="available"></span>Tersedia <span class="full"></span>Penuh <span class="blocked"></span>Diblokir</div>
                        </div>
                        <div class="calendar-grid mt-4">
                            <?php $__currentLoopData = $calendar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="date-card <?php echo e($date['status']); ?>">
                                    <input type="radio" name="booking_date" value="<?php echo e($date['date']); ?>" <?php echo e($date['status'] !== 'available' ? 'disabled' : ''); ?> <?php echo e(old('booking_date') === $date['date'] ? 'checked' : ''); ?>>
                                    <strong><?php echo e($date['day']); ?></strong>
                                    <span><?php echo e($date['month']); ?></span>
                                    <small><?php echo e($date['status'] === 'available' ? $date['remaining'].' slot' : ($date['status'] === 'full' ? 'Penuh' : 'Diblokir')); ?></small>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="booking-panel sticky-lg-top">
                        <h3>Atur Booking</h3>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Softlens</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="softlens" id="softlensNo" value="0" checked>
                                <label class="btn btn-outline-dark rounded-pill" for="softlensNo">Tidak</label>
                                <input type="radio" class="btn-check" name="softlens" id="softlensYes" value="1">
                                <label class="btn btn-outline-dark rounded-pill" for="softlensYes">Ya</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Add-on Opsional</label>
                            <?php $__currentLoopData = $addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="addon-row">
                                    <span><input type="checkbox" name="addons[]" value="<?php echo e($addon->id); ?>" data-price="<?php echo e($addon->price); ?>"> <?php echo e($addon->name); ?><small><?php echo e($addon->description); ?></small></span>
                                    <strong>Rp<?php echo e(number_format($addon->price, 0, ',', '.')); ?></strong>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="total-box">
                            <div><span>Harga paket</span><strong data-base="<?php echo e($package->price); ?>">Rp<?php echo e(number_format($package->price, 0, ',', '.')); ?></strong></div>
                            <div><span>Add-on</span><strong id="addonTotal">Rp0</strong></div>
                            <div><span>DP checkout</span><strong>Rp<?php echo e(number_format($package->dp_amount, 0, ',', '.')); ?></strong></div>
                            <hr>
                            <div><span>Total layanan</span><strong id="grandTotal">Rp<?php echo e(number_format($package->price, 0, ',', '.')); ?></strong></div>
                        </div>

                        <button type="submit" name="action" value="cart" class="btn-outline-custom w-100 text-center mt-3">Tambah Keranjang</button>
                        <button type="submit" name="action" value="checkout" class="btn-dark-custom w-100 text-center mt-3 border-0">Booking Sekarang</button>
                        <p class="muted small mt-2 mb-0">Mode preview: booking hanya tersimpan sementara di session, belum masuk database.</p>
                        <a href="https://wa.me/6281227545591?text=Halo%20admin%20LYB,%20saya%20mau%20tanya%20paket%20<?php echo e(urlencode($package->name)); ?>" target="_blank" class="btn-whatsapp w-100 mt-3"><i class="bi bi-whatsapp"></i> Tanya via WhatsApp</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
document.querySelectorAll('input[name="addons[]"]').forEach((checkbox) => {
    checkbox.addEventListener('change', () => {
        const base = Number(document.querySelector('[data-base]').dataset.base);
        const addonTotal = [...document.querySelectorAll('input[name="addons[]"]:checked')].reduce((sum, item) => sum + Number(item.dataset.price), 0);
        const formatter = new Intl.NumberFormat('id-ID');
        document.getElementById('addonTotal').textContent = 'Rp' + formatter.format(addonTotal);
        document.getElementById('grandTotal').textContent = 'Rp' + formatter.format(base + addonTotal);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['title' => $package->name . ' - Lisa Yuli Belti'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\lenovo\Downloads\PHP\styleit.project\resources\views/paket/show.blade.php ENDPATH**/ ?>