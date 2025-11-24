<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <title><?php echo e(config('app.name', 'Laravel')); ?> - Kios Absensi</title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased bg-gray-900 text-white">
        <div class="min-h-screen flex items-center justify-center">
            
            <?php echo $__env->yieldContent('content'); ?> 
        </div>
        <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/layouts/kios.blade.php ENDPATH**/ ?>