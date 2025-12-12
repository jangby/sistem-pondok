<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?> - Petugas Lab</title>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen pb-20">

    <div class="bg-blue-600 text-white p-6 rounded-b-3xl shadow-lg relative z-10">
        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="text-blue-200 text-sm">Selamat Datang,</p>
                <h1 class="text-2xl font-bold"><?php echo e(Auth::user()->name); ?></h1>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="bg-blue-700 hover:bg-blue-800 p-2 rounded-full transition">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
        
        <?php if(isset($headerStats)): ?>
            <?php echo e($headerStats); ?>

        <?php endif; ?>
    </div>

    <main class="max-w-md mx-auto px-4 -mt-6 relative z-20">
        <?php echo e($slot); ?>

    </main>

</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/components/petugas-lab-layout.blade.php ENDPATH**/ ?>