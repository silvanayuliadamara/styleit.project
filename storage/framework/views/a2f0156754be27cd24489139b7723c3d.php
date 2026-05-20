<div class="form-group <?php echo e($class ?? ''); ?>">
    <label for="<?php echo e($id ?? 'password'); ?>">
        <?php echo e($label ?? 'Kata Sandi'); ?>

    </label>

    <div class="password-wrapper">
        <input
            type="password"
            id="<?php echo e($id ?? 'password'); ?>"
            name="<?php echo e($name ?? 'password'); ?>"
            class="<?php $__errorArgs = [$name ?? 'password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        >

        <button
            type="button"
            class="toggle-password"
            onclick="togglePassword('<?php echo e($id ?? 'password'); ?>', this)"
        >
            <i class="bi bi-eye"></i>
        </button>
    </div>

    <?php $__errorArgs = [$name ?? 'password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="error">
            <?php echo e($message); ?>

        </div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php /**PATH D:\styleit.project\resources\views/components/password-input.blade.php ENDPATH**/ ?>