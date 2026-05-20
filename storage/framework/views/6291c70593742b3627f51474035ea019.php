<div class="welcome-card">
    <div>
        <div class="welcome-brand">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo" class="welcome-logo">

            <div>
                <div class="welcome-brand-title">LISA YULI BELTI</div>
                <div class="welcome-brand-subtitle">WEDDING GALLERY DAN MAKEUP ARTIST</div>
            </div>
        </div>

        <div class="welcome-content">
            <p class="welcome-label">
                <?php echo e($label ?? 'WEDDING GALLERY & MAKEUP ARTIST'); ?>

            </p>

            <h1><?php echo e($title ?? 'Selamat datang kembali, Cantik.'); ?></h1>

            <p class="welcome-description">
                <?php echo e($subtitle ?? 'Masuk untuk melanjutkan booking, melihat invoice, atau mengelola akun Anda. Setiap detail kami rancang untuk pengalaman premium Anda.'); ?>

            </p>
        </div>
    </div>

    <div class="quote">
        <em>"<?php echo e($quote ?? 'Setiap pengantin berhak merasa istimewa di hari spesialnya.'); ?>"</em>

        <div class="quote-author">
            — <?php echo e($author ?? 'LISA YULI BELTI'); ?>

        </div>
    </div>
</div>
<?php /**PATH D:\styleit.project\resources\views/components/auth-welcome-card.blade.php ENDPATH**/ ?>