<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> 
        
        <title><?php echo e(config('app.name', 'Aplikasi Pondok')); ?></title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,800,900" rel="stylesheet" />

        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <style>
            /* Custom styles for Deep Navy Background */
            .bg-deep-navy {
                background-color: #0c0f1d; 
            }
            .glow-ring {
                box-shadow: 0 0 25px rgba(16, 185, 129, 0.4); 
            }
            .glow-effect-center {
                background-image: radial-gradient(circle at center, rgba(16, 185, 129, 0.08) 0%, rgba(12, 15, 29, 0) 60%);
            }
            /* Media Query untuk ukuran Mobile (meniru ponsel) */
            @media (max-width: 639px) {
                .text-mobile-h1 { font-size: 2.2rem; line-height: 1.1; }
                .text-mobile-lead { font-size: 1rem; }
            }
            /* Styling untuk Desktop (lebar lebih kecil dari sebelumnya, fokus ke konten) */
            @media (min-width: 1024px) {
                .container-desktop { max-width: 58rem; } /* Max-width sedikit lebih kecil */
            }
        </style>
    </head>

    <body class="bg-deep-navy antialiased min-h-screen text-white">
        
        
        <header class="absolute top-0 left-0 right-0 p-4 lg:px-8 z-50">
            <div class="flex items-center justify-between mx-auto container-desktop">
                
                
                <div class="text-xl font-extrabold text-emerald-400">
                    <?php echo e(config('app.name', 'Pondok Digital')); ?>

                </div>

                
                <?php if(Route::has('login')): ?>
                    <nav class="flex items-center justify-end gap-3">
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(url('/dashboard')); ?>" class="text-sm font-semibold text-gray-300 hover:text-emerald-400 transition-colors px-3 py-1 border border-emerald-500 rounded-lg">
                                Dashboard
                            </a>
                        <?php else: ?>
                            <?php if(Route::has('register')): ?>
                                <a href="<?php echo e(route('register')); ?>" class="text-xs font-medium text-gray-400 hover:text-white transition-colors py-1 hidden sm:inline-block">
                                    Daftar
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('login')); ?>" class="inline-block px-4 py-2 bg-emerald-600 text-gray-900 font-bold rounded-lg shadow-md hover:bg-emerald-500 transition-colors text-sm">
                                Masuk
                            </a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            </div>
        </header>

        
        <main class="min-h-screen flex items-center justify-center relative overflow-hidden glow-effect-center">
            <div class="container-desktop mx-auto px-6 lg:px-8 py-24 max-w-lg lg:max-w-7xl flex flex-col items-center justify-center text-center">
                
                
                <p class="text-emerald-400 font-extrabold uppercase mb-3 text-sm tracking-widest px-3 py-1 bg-gray-800/50 rounded-full border border-emerald-500/50">
                    #DigitalisasiPondok
                </p>

                
                <h1 class="text-mobile-h1 md:text-5xl lg:text-7xl font-extrabold text-white leading-tight mb-4 tracking-tight">
                    Kelola <span class="text-emerald-400">Santri & Sekolah</span> dengan Teknologi
                </h1>
                
                
                <p class="text-mobile-lead md:text-lg text-gray-400 mb-10 max-w-md">
                    Platform efisien untuk absensi *real-time*, manajemen perizinan, dan laporan keuangan terintegrasi.
                </p>

                
                <div class="flex flex-col sm:flex-row gap-4 justify-center w-full max-w-sm">
                    <a href="<?php echo e(route('login')); ?>" class="flex-1 inline-flex items-center justify-center gap-2 px-8 py-3 bg-emerald-600 text-gray-900 font-extrabold rounded-xl shadow-lg glow-ring hover:bg-emerald-500 transition-all text-base active:scale-[0.98]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Masuk ke Sistem
                    </a>
                </div>

                
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-20 w-full max-w-4xl">
                    <?php
                        $mini_benefits = [
                            ['icon' => 'clock', 'text' => 'Absensi Cepat'],
                            ['icon' => 'user-check', 'text' => 'Izin Terpantau'],
                            ['icon' => 'credit-card', 'text' => 'Uang Jajan Digital'],
                            ['icon' => 'report', 'text' => 'Laporan Wali'],
                        ];
                    ?>
                    <?php $__currentLoopData = $mini_benefits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-3 bg-gray-900 rounded-lg border border-gray-700/50 flex items-center justify-center lg:justify-start gap-2 shadow-xl">
                            <div class="text-emerald-500 shrink-0">
                                <?php if($b['icon'] == 'clock'): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <?php elseif($b['icon'] == 'user-check'): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.275a1.879 1.879 0 011.666 0C21.75 6.136 21.75 7.962 21.75 12c0 4.038 0 5.864-.466 7.275a1.879 1.879 0 01-1.666 0C19.75 18.064 19.75 16.238 19.75 12s0-5.864.466-7.275z"></path></svg>
                                <?php elseif($b['icon'] == 'credit-card'): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                <?php elseif($b['icon'] == 'report'): ?>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <?php endif; ?>
                            </div>
                            <span class="text-xs text-gray-300 font-medium"><?php echo e($b['text']); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </main>

        
        <footer class="text-center py-4 text-xs text-gray-600 absolute bottom-0 w-full z-10">
            &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name', 'Pondok Digital')); ?>. Dibuat dengan kecintaan pada pendidikan Islami.
        </footer>
    </body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/welcome.blade.php ENDPATH**/ ?>