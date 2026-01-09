<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'Pondok Pesantren')); ?></title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,800,900" rel="stylesheet" />
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        .bg-deep-navy { background-color: #0c0f1d; }
        .glow-text { text-shadow: 0 0 20px rgba(16, 185, 129, 0.5); }
    </style>

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1423313253267364"
     crossorigin="anonymous"></script>
     
</head>
<body class="bg-deep-navy antialiased min-h-screen text-gray-100 font-sans selection:bg-emerald-500 selection:text-white">

    
    <nav class="fixed w-full z-50 bg-deep-navy/80 backdrop-blur-md border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center gap-2">
                    
                    <span class="text-2xl font-black text-emerald-500 tracking-tighter">SISTEM PONDOK</span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#" class="hover:text-emerald-400 transition">Beranda</a>
                    <a href="#program" class="hover:text-emerald-400 transition">Program</a>
                    <a href="#berita" class="hover:text-emerald-400 transition">Berita</a>
                </div>
                <div class="flex items-center gap-4">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(url('/dashboard')); ?>" class="px-4 py-2 text-sm font-semibold text-white bg-gray-800 rounded-full hover:bg-gray-700 border border-gray-700 transition">Dashboard</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="text-gray-300 hover:text-white transition font-medium">Masuk</a>
                        
                        <?php if(isset($ppdbActive) && $ppdbActive): ?>
                            <a href="<?php echo e(route('ppdb.register')); ?>" class="px-5 py-2 text-sm font-bold text-gray-900 bg-emerald-500 rounded-full hover:bg-emerald-400 shadow-lg shadow-emerald-500/20 transition transform hover:-translate-y-0.5">
                                Daftar PPDB
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    
    <section class="relative pt-32 pb-20 overflow-hidden">
        
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-emerald-500/10 rounded-full blur-3xl -z-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <?php if(isset($ppdbActive) && $ppdbActive): ?>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 mb-8 animate-pulse">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-sm font-bold tracking-wide uppercase">Pendaftaran Dibuka: <?php echo e($ppdbActive->nama_gelombang); ?></span>
                </div>
            <?php endif; ?>

            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 leading-tight">
                Membangun Generasi <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400 glow-text">Qur'ani & Berteknologi</span>
            </h1>
            
            <p class="text-lg text-gray-400 mb-10 max-w-2xl mx-auto">
                Platform digital terintegrasi untuk manajemen pendidikan pesantren modern. Memudahkan pendaftaran, pemantauan hafalan, hingga laporan akademik.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <?php if(isset($ppdbActive) && $ppdbActive): ?>
                    <a href="<?php echo e(route('ppdb.register')); ?>" class="px-8 py-4 text-lg font-bold text-gray-900 bg-emerald-500 rounded-xl hover:bg-emerald-400 shadow-xl shadow-emerald-500/20 transition hover:scale-105">
                        Daftar Santri Baru Sekarang
                    </a>
                <?php else: ?>
                    <button disabled class="px-8 py-4 text-lg font-bold text-gray-500 bg-gray-800 rounded-xl cursor-not-allowed">
                        Pendaftaran Belum Dibuka
                    </button>
                <?php endif; ?>
                <a href="#info-ppdb" class="px-8 py-4 text-lg font-bold text-white bg-gray-800 border border-gray-700 rounded-xl hover:bg-gray-700 transition">
                    Lihat Alur & Biaya
                </a>
            </div>
        </div>
    </section>

    
    <?php if(isset($ppdbActive) && $ppdbActive): ?>
    <section id="info-ppdb" class="py-20 bg-gray-900 border-y border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold mb-6 text-white">Informasi Pendaftaran <span class="text-emerald-500"><?php echo e($ppdbActive->tahun_ajaran); ?></span></h2>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center text-emerald-500 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Waktu Pendaftaran</h3>
                                <p class="text-gray-400">
                                    <?php echo e($ppdbActive->tanggal_mulai->format('d M Y')); ?> s/d <?php echo e($ppdbActive->tanggal_akhir->format('d M Y')); ?>

                                </p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center text-emerald-500 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Biaya Pendaftaran</h3>
                                <p class="text-emerald-400 font-mono text-lg">Rp <?php echo e(number_format($ppdbActive->biaya_pendaftaran, 0, ',', '.')); ?></p>
                                <p class="text-xs text-gray-500">Pembayaran via Transfer Otomatis (Virtual Account)</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 p-4 bg-gray-800/50 rounded-xl border border-gray-700 text-sm text-gray-300">
                        <p class="font-semibold text-white mb-2">Deskripsi Gelombang:</p>
                        <?php echo e($ppdbActive->deskripsi ?? 'Segera daftarkan putra-putri Anda sebelum kuota terpenuhi.'); ?>

                    </div>
                </div>

                
                <div class="relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-8 border border-gray-700 shadow-2xl">
                    <h3 class="text-xl font-bold text-white mb-4 text-center">Alur Pendaftaran</h3>
                    <ol class="relative border-l border-gray-700 ml-3 space-y-6">                  
                        <li class="mb-2 ml-6">
                            <span class="absolute flex items-center justify-center w-8 h-8 bg-emerald-900 rounded-full -left-4 ring-4 ring-gray-900">
                                <span class="text-emerald-400 font-bold text-sm">1</span>
                            </span>
                            <h3 class="font-medium leading-tight text-white">Buat Akun</h3>
                            <p class="text-sm text-gray-400">Isi Nama, Email & Password.</p>
                        </li>
                        <li class="mb-2 ml-6">
                            <span class="absolute flex items-center justify-center w-8 h-8 bg-gray-800 rounded-full -left-4 ring-4 ring-gray-900 border border-gray-700">
                                <span class="text-gray-400 font-bold text-sm">2</span>
                            </span>
                            <h3 class="font-medium leading-tight text-white">Bayar Pendaftaran</h3>
                            <p class="text-sm text-gray-400">Melalui Virtual Account / QRIS.</p>
                        </li>
                        <li class="mb-2 ml-6">
                            <span class="absolute flex items-center justify-center w-8 h-8 bg-gray-800 rounded-full -left-4 ring-4 ring-gray-900 border border-gray-700">
                                <span class="text-gray-400 font-bold text-sm">3</span>
                            </span>
                            <h3 class="font-medium leading-tight text-white">Lengkapi Biodata</h3>
                            <p class="text-sm text-gray-400">Isi data santri & upload berkas.</p>
                        </li>
                    </ol>
                    <div class="mt-8">
                        <a href="<?php echo e(route('ppdb.register')); ?>" class="block w-full py-3 text-center bg-emerald-600 hover:bg-emerald-500 rounded-lg font-bold text-white transition">
                            Mulai Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    
    <footer class="bg-black py-10 border-t border-gray-800 text-center">
        <p class="text-gray-500 text-sm">
            &copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.
        </p>
    </footer>

</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/welcome.blade.php ENDPATH**/ ?>