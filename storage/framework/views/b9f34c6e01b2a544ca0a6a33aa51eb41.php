<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1423313253267364"
     crossorigin="anonymous"></script>

    </head>
    <body class="font-sans antialiased <?php echo e(Auth::check() && Auth::user()->hasRole('pengurus_pondok') ? 'pengurus-app' : ''); ?>">
        <div class="min-h-screen bg-gray-100">
            
            <?php if(Auth::check() && Auth::user()->hasRole('pengurus_pondok')): ?>
                <?php echo $__env->make('layouts.pengurus-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php if(!isset($attributes) || !$attributes->has('hide-nav')): ?>
                    <?php echo $__env->make('layouts.pengurus-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>
            <?php elseif(!isset($attributes) || !$attributes->has('hide-nav')): ?>
                <?php if(Auth::user()->hasRole('super-admin')): ?>
                    <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                
                <?php elseif(Auth::user()->hasRole('admin-pondok')): ?>
                    <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                
                <?php elseif(Auth::user()->hasRole('bendahara')): ?>
                    <?php echo $__env->make('layouts.bendahara-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                <?php elseif(Auth::user()->hasRole('pos_warung')): ?>
                    <?php echo $__env->make('layouts.pos-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                <?php elseif(Auth::user()->hasRole('orang-tua')): ?>
                    
                    <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 

                <?php elseif(Auth::user()->hasRole('admin-pendidikan')): ?>
                    <?php echo $__env->make('layouts.pendidikan-admin-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                
                <?php else: ?>
                    <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>

            <?php endif; ?>

            <?php if(Auth::check() && Auth::user()->hasRole('pengurus_pondok')): ?>
                <header class="pengurus-topbar">
                    <div class="pengurus-topbar-context">
                        <span class="pengurus-topbar-eyebrow">SISTEM PONDOK</span>
                        <h1>Panel Pengurus</h1>
                    </div>
                    <div class="pengurus-topbar-actions">
                        <span class="pengurus-topbar-date"><?php echo e(now()->format('d/m/Y')); ?></span>
                        <a href="<?php echo e(route('profile.edit')); ?>" class="pengurus-topbar-profile">
                            <span class="pengurus-topbar-avatar"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></span>
                            <span class="pengurus-topbar-user">
                                <strong><?php echo e(Auth::user()->name); ?></strong>
                                <small>Pengurus Pondok</small>
                            </span>
                        </a>
                    </div>
                </header>
            <?php endif; ?>

            
            <?php if(isset($header) && $header->isNotEmpty()): ?>
                <header class="<?php echo e(Auth::check() && Auth::user()->hasRole('pengurus_pondok') ? 'pengurus-page-heading' : 'bg-white shadow'); ?>">
                    <div class="<?php echo e(Auth::check() && Auth::user()->hasRole('pengurus_pondok') ? 'pengurus-page-heading-inner' : 'max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8'); ?>">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            
            <main class="<?php echo e(Auth::check() && Auth::user()->hasRole('pengurus_pondok') ? 'pengurus-main' : ''); ?>">
                <?php echo e($slot); ?>

            </main>
        </div>

        
        <script>
            // Notifikasi Sukses
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "<?php echo session('success'); ?>", // Menggunakan tanda kutip ganda & unescaped agar aman
                    showConfirmButton: true, // KITA AKTIFKAN TOMBOL OK
                    confirmButtonText: 'Oke',
                    timer: 3000, // Tetap pakai timer 3 detik
                    timerProgressBar: true // Tampilkan progress bar timer
                });
            <?php endif; ?>

            // Notifikasi Error
            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: <?php echo json_encode(session('error')); ?>,
                    showConfirmButton: true,
                    confirmButtonText: 'Tutup'
                });
            <?php endif; ?>
        </script>

        <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html><?php /**PATH C:\laragon\www\sistem-pondok\resources\views/layouts/app.blade.php ENDPATH**/ ?>