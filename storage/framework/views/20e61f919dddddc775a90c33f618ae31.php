<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Lisa Yuli Belti'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
</head>
<body>
    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main>
        <div class="container pt-3">
            <?php $__currentLoopData = ['success' => 'success', 'warning' => 'warning', 'error' => 'danger']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(session($key)): ?>
                    <div class="alert alert-<?php echo e($type); ?> alert-dismissible fade show rounded-4" role="alert">
                        <?php echo e(session($key)); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger rounded-4">
                    <strong>Ada data yang perlu diperbaiki:</strong>
                    <ul class="mb-0 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('components.auth-footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\Users\lenovo\Downloads\PHP\styleit.project\resources\views/layouts/app.blade.php ENDPATH**/ ?>