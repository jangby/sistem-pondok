

<?php $__env->startSection('title', 'Beranda Utama'); ?>

<?php $__env->startSection('content'); ?>
    
    <section class="relative h-[95vh] min-h-[700px] flex items-center justify-center overflow-hidden">
        
        <div class="absolute inset-0 w-full h-full">
            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop" 
                 alt="Pendidikan Modern" class="w-full h-full object-cover object-center" />
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/70 to-transparent"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full mt-20">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-dark mb-8 border-emerald-500/30">
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-sm font-bold text-emerald-300 tracking-wide uppercase">Pendaftaran Tahun Ajaran 2026 Dibuka</span>
                </div>

                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-6 text-white leading-[1.1]">
                    Pendidikan Islam <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">Bertaraf Global.</span>
                </h1>
                
                <p class="text-lg md:text-xl text-slate-300 mb-10 leading-relaxed max-w-2xl font-medium">
                    Assa'adah menghadirkan ekosistem pendidikan modern. Menggabungkan kedalaman ilmu agama (Tafaqquh Fiddin) dengan sistem digital terintegrasi untuk mencetak pemimpin masa depan.
                </p>

                <div class="flex flex-col sm:flex-row gap-5">
                    <a href="<?php echo e(route('ppdb.register')); ?>" class="px-8 py-4 text-base font-bold text-slate-900 bg-white rounded-xl hover:bg-slate-100 shadow-[0_0_40px_rgba(255,255,255,0.3)] transition transform hover:-translate-y-1 text-center flex items-center justify-center gap-2">
                        Daftar PPDB Sekarang
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="#tentang" class="px-8 py-4 text-base font-bold text-white glass-dark hover:bg-white/20 rounded-xl transition text-center border border-white/20">
                        Jelajahi Program
                    </a>
                </div>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce flex flex-col items-center opacity-70">
            <span class="text-white text-xs font-bold uppercase tracking-widest mb-2">Scroll</span>
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
        </div>
    </section>

    
    <section class="relative z-20 -mt-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12 border border-slate-100 flex flex-wrap justify-between items-center gap-8 lg:gap-12">
            <div class="flex-1 min-w-[200px]">
                <h3 class="text-5xl font-extrabold text-slate-900 mb-2">50+</h3>
                <p class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Tahun Pengalaman</p>
            </div>
            <div class="hidden md:block w-px h-16 bg-slate-200"></div>
            <div class="flex-1 min-w-[200px]">
                <h3 class="text-5xl font-extrabold text-slate-900 mb-2">500</h3>
                <p class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Santri Aktif</p>
            </div>
            <div class="hidden md:block w-px h-16 bg-slate-200"></div>
            <div class="flex-1 min-w-[200px]">
                <h3 class="text-5xl font-extrabold text-slate-900 mb-2">70%</h3>
                <p class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Sistem Digital</p>
            </div>
        </div>
    </section>

    
    <section id="tentang" class="py-24 md:py-32 bg-slate-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                <div>
                    <span class="text-emerald-600 font-extrabold tracking-widest uppercase text-sm mb-4 block">Tentang Yayasan</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-[1.2] mb-6">
                        Membentuk Karakter Melalui <span class="text-emerald-600 relative whitespace-nowrap">Disiplin & Ilmu.<svg class="absolute w-full h-3 -bottom-1 left-0 text-emerald-200 -z-10" fill="currentColor" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 10 100 5 L 100 10 L 0 10 Z"></path></svg></span>
                    </h2>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                        Kami tidak hanya mengajarkan teori. Melalui <strong>Sistem Pondok Pintar (Smart System)</strong>, perkembangan adab, nilai akademik, hingga administrasi keuangan santri dapat dipantau secara transparan dan terintegrasi penuh dari *smartphone* orang tua.
                    </p>
                    
                    <ul class="space-y-5 mb-10">
                        <li class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white shadow-sm rounded-xl flex items-center justify-center shrink-0 border border-slate-100">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-slate-900">Kurikulum Terpadu</h4>
                                <p class="text-slate-600 mt-1 leading-relaxed">Integrasi kurikulum Dinas Pendidikan Nasional dengan kurikulum Pesantren Salaf/Modern.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white shadow-sm rounded-xl flex items-center justify-center shrink-0 border border-slate-100">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold text-slate-900">E-Monitoring Santri</h4>
                                <p class="text-slate-600 mt-1 leading-relaxed">Pantau absensi gerbang, jurnal hafalan Al-Qur'an, dan transaksi uang jajan (e-money) secara *real-time*.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 bg-emerald-600 rounded-[3rem] transform rotate-3 scale-105 opacity-10"></div>
                    <div class="grid grid-cols-2 gap-4 relative z-10">
                        <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1000&auto=format&fit=crop" alt="Kegiatan Belajar" class="rounded-3xl w-full h-64 object-cover shadow-xl mt-12">
                        <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=1000&auto=format&fit=crop" alt="Fasilitas" class="rounded-3xl w-full h-80 object-cover shadow-xl">
                    </div>
                </div>

            </div>
        </div>
    </section>

    
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6">Fasilitas & Program</h2>
                <p class="text-lg text-slate-600">Ekosistem lengkap yang dirancang khusus untuk mendukung potensi akademik, spiritual, dan minat bakat setiap individu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[250px]">
                
                <div class="md:col-span-2 md:row-span-2 relative rounded-3xl overflow-hidden group shadow-lg">
                    <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=2073&auto=format&fit=crop" class="absolute w-full h-full object-cover transition duration-700 group-hover:scale-105" alt="Perpustakaan">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8">
                        <span class="px-3 py-1 bg-emerald-500 text-white text-xs font-bold uppercase rounded-md mb-3 inline-block">Fasilitas</span>
                        <h3 class="text-3xl font-bold text-white mb-2">Smart Digital Library</h3>
                        <p class="text-slate-300 max-w-md">Perpustakaan berbasis teknologi dengan ribuan literasi fisik dan digital (e-book) untuk riset santri.</p>
                    </div>
                </div>

                <div class="relative rounded-3xl overflow-hidden group shadow-lg">
                    <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=1000&auto=format&fit=crop" class="absolute w-full h-full object-cover transition duration-700 group-hover:scale-105" alt="Tahfidz">
                    <div class="absolute inset-0 bg-slate-900/60 group-hover:bg-slate-900/40 transition duration-500"></div>
                    <div class="absolute inset-0 p-8 flex flex-col justify-end">
                        <h3 class="text-xl font-bold text-white mb-2">Program Tahfidz Intensif</h3>
                        <p class="text-slate-300 text-sm">Metode hafalan mutqin bersanad dengan target yang terstruktur.</p>
                    </div>
                </div>

                <div class="relative rounded-3xl overflow-hidden group shadow-lg bg-emerald-600 p-8 flex flex-col justify-between border border-emerald-500">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-white backdrop-blur-sm">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white mb-2">Lab. Komputer & Coding</h3>
                        <p class="text-emerald-100 text-sm">Santri dibekali *skill* teknologi dasar untuk menghadapi Revolusi Industri 4.0.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    
    <section class="py-24 relative overflow-hidden bg-slate-900 mt-10">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-emerald-500/20 rounded-full blur-[120px] pointer-events-none"></div>
        
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight">Siap Menjadi Bagian Dari <span class="text-emerald-400">Generasi Cerdas?</span></h2>
            <p class="text-xl text-slate-300 mb-10 leading-relaxed font-medium">
                Pendaftaran santri baru terbatas. Segera daftarkan putra/putri Anda dan bergabunglah dengan ekosistem pendidikan paling modern saat ini.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('ppdb.register')); ?>" class="px-10 py-5 bg-emerald-500 text-slate-900 text-lg font-bold rounded-2xl shadow-[0_0_30px_rgba(16,185,129,0.3)] hover:bg-emerald-400 hover:-translate-y-1 transition-all">
                    Mulai Pendaftaran Online
                </a>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.landing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/landing/home.blade.php ENDPATH**/ ?>