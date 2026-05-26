<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', "Assa'adah Smart Digital"); ?> | Yayasan Pendidikan Modern</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .glass-dark { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="bg-slate-50 antialiased min-h-screen text-slate-800 flex flex-col selection:bg-emerald-500 selection:text-white">

    
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-500 bg-white/90 backdrop-blur-xl border-b border-slate-200/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24 items-center">
                <a href="<?php echo e(route('landing.home')); ?>" class="flex-shrink-0 flex items-center gap-3 group">
                    <img src="<?php echo e(asset('logo-pondok.jpg')); ?>" alt="Logo" class="w-12 h-12 rounded-xl object-cover shadow-md group-hover:scale-105 transition-transform" onerror="this.src='https://ui-avatars.com/api/?name=A&background=10b981&color=fff&rounded=true&bold=true'">
                    <div class="flex flex-col">
                        <span class="text-xl font-extrabold text-slate-900 tracking-tight leading-none">Assa'adah</span>
                        <span class="text-xs font-semibold text-emerald-600 tracking-widest uppercase mt-1">Smart Digital</span>
                    </div>
                </a>

                <div class="hidden md:flex space-x-10 items-center">
                    <a href="<?php echo e(route('landing.home')); ?>" class="text-sm font-bold <?php echo e(request()->routeIs('landing.home') ? 'text-emerald-600' : 'text-slate-600 hover:text-emerald-600'); ?> transition-colors">Beranda</a>
                    <a href="<?php echo e(route('landing.tentang')); ?>" class="text-sm font-bold <?php echo e(request()->routeIs('landing.tentang') ? 'text-emerald-600' : 'text-slate-600 hover:text-emerald-600'); ?> transition-colors">Tentang Kami</a>
                    <a href="<?php echo e(route('landing.program')); ?>" class="text-sm font-bold <?php echo e(request()->routeIs('landing.program') ? 'text-emerald-600' : 'text-slate-600 hover:text-emerald-600'); ?> transition-colors">Program</a>
                    <a href="<?php echo e(route('landing.ppdb')); ?>" class="text-sm font-bold <?php echo e(request()->routeIs('landing.ppdb') ? 'text-emerald-600' : 'text-slate-600 hover:text-emerald-600'); ?> transition-colors">Info PPDB</a>
                </div>

                <div class="hidden md:flex items-center gap-5">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(url('/dashboard')); ?>" class="px-6 py-2.5 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-full transition">Dashboard</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="text-sm font-bold text-slate-600 hover:text-slate-900 transition">Portal Login</a>
                        <a href="<?php echo e(route('ppdb.register')); ?>" class="px-6 py-3 text-sm font-bold text-white bg-emerald-600 rounded-full hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">
                            Pendaftaran Santri
                        </a>
                    <?php endif; ?>
                </div>

                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-slate-600 focus:outline-none p-2 bg-slate-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-slate-100 absolute w-full shadow-2xl">
            <div class="px-4 pt-4 pb-6 space-y-2">
                <a href="<?php echo e(route('landing.home')); ?>" class="block px-4 py-3 text-base font-bold rounded-xl hover:bg-emerald-50 text-slate-800">Beranda</a>
                <a href="<?php echo e(route('landing.tentang')); ?>" class="block px-4 py-3 text-base font-bold rounded-xl hover:bg-emerald-50 text-slate-800">Tentang Kami</a>
                <a href="<?php echo e(route('ppdb.register')); ?>" class="block mt-4 px-4 py-3 text-center text-base font-bold text-white bg-emerald-600 rounded-xl">Pendaftaran PPDB</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <footer class="bg-slate-950 pt-20 pb-10 border-t border-slate-900 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-600/10 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
                <div class="md:col-span-5">
                    <span class="text-2xl font-extrabold text-white tracking-tight">Assa'adah<span class="text-emerald-500">.</span></span>
                    <p class="text-slate-400 text-base leading-relaxed mt-6 max-w-sm">
                        Membangun peradaban Islam dengan standar pendidikan global. Kami memadukan nilai pesantren salafiyah dengan kecanggihan teknologi masa depan.
                    </p>
                </div>
                <div class="md:col-span-3">
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Navigasi Utama</h4>
                    <ul class="space-y-4 text-slate-400 font-medium">
                        <li><a href="#" class="hover:text-emerald-400 hover:translate-x-1 inline-block transition-transform">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-emerald-400 hover:translate-x-1 inline-block transition-transform">Program Unggulan</a></li>
                        <li><a href="#" class="hover:text-emerald-400 hover:translate-x-1 inline-block transition-transform">Informasi PPDB</a></li>
                    </ul>
                </div>
                <div class="md:col-span-4">
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Hubungi Kami</h4>
                    <ul class="space-y-4 text-slate-400 font-medium">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            Garut, Jawa Barat, Indonesia
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            adm.assaadah@gmail.com
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800/80 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-sm font-medium">&copy; <?php echo e(date('Y')); ?> Yayasan Assa'adah. All rights reserved.</p>
                <div class="text-slate-500 text-sm font-medium">
                    Powered by <span class="text-white">Sistem Pondok</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/layouts/landing.blade.php ENDPATH**/ ?>