<?php $__env->startSection('content'); ?>
<section class="auth-container">
    <?php echo $__env->make('components.auth-welcome-card', [
        'title' => 'Selamat datang kembali Cantik.',
        'subtitle' => 'Masuk untuk melanjutkan booking, melihat invoice, atau mengelola usaha Anda. Setiap detail kami rancang untuk pengalaman premium Anda.',
        'quote' => 'Kecantikan terbaik dimulai dari rasa percaya diri dan pelayanan yang tepat.',
        'author' => 'LISA YULI BELTI'
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="form-card">
        <h2>Masuk ke Akun</h2>

        
        <?php if(session('login_error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('login_error')); ?>

            </div>
        <?php endif; ?>

        
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('login.process')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <div class="form-group">
        <label for="email">Email</label>

        <input
            type="email"
            id="email"
            name="email"
            value="<?php echo e(old('email')); ?>"
            class="<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        >

        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <?php echo $__env->make('components.password-input', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php echo $__env->make('components.forgot-password-link', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <button type="submit" class="btn-primary">Masuk</button>
</form>

        <div class="auth-link">
            Belum punya akun? <a href="<?php echo e(route('register')); ?>">Daftar</a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\styleit.project\resources\views/auth/login.blade.php ENDPATH**/ ?>