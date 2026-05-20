<?php $__env->startSection('content'); ?>
<section class="auth-container register-container">
    <?php echo $__env->make('components.auth-welcome-card', [
        'title' => 'Halo, Tamu Istimewa.',
        'subtitle' => 'Daftarkan akun Anda untuk mulai merencanakan layanan makeup, wedding gallery, dan baju pengantin dengan lebih mudah.',
        'quote' => 'Setiap langkah menuju hari spesial layak dimulai dengan pengalaman yang indah.',
        'author' => 'LISA YULI BELTI'
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="form-card register-card">
        <h2>Daftar Sekarang</h2>

        <form action="<?php echo e(route('register.process')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="form-grid">
                <?php echo $__env->make('components.form-input', [
                    'label' => 'Nama Lengkap',
                    'id' => 'name',
                    'name' => 'name'
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <?php echo $__env->make('components.form-input', [
                    'label' => 'No. HP',
                    'id' => 'phone',
                    'name' => 'phone'
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <?php echo $__env->make('components.form-input', [
                    'label' => 'Username Instagram',
                    'id' => 'instagram',
                    'name' => 'instagram',
                    'placeholder' => '@username',
                    'class' => 'full'
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <?php echo $__env->make('components.form-input', [
                    'label' => 'Email',
                    'id' => 'email',
                    'name' => 'email',
                    'type' => 'email',
                    'class' => 'full'
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <?php echo $__env->make('components.password-input', [
                    'label' => 'Kata Sandi',
                    'id' => 'password',
                    'name' => 'password',
                    'class' => 'full'
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <?php echo $__env->make('components.password-input', [
                    'label' => 'Konfirmasi Kata Sandi',
                    'id' => 'password_confirmation',
                    'name' => 'password_confirmation',
                    'class' => 'full'
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <button type="submit" class="btn-primary">Daftar</button>
        </form>

        <div class="auth-link">
            Sudah punya akun? <a href="<?php echo e(route('login')); ?>">Masuk</a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\styleit.project\resources\views/auth/register.blade.php ENDPATH**/ ?>