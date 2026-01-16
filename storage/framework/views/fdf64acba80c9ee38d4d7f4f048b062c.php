<?php
// Pastikan Carbon sudah di-use di layout atau file utama jika menggunakan Blade di luar Laravel
use Carbon\Carbon;
// Set locale ke Indonesia (jika belum diatur global)
Carbon::setLocale('id'); 
?>

<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hide-nav' => true]); ?>
     <?php $__env->slot('header', null, []); ?>  <?php $__env->endSlot(); ?>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-400 opacity-10 rounded-full -ml-10 -mb-10 blur-xl"></div>

            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <p class="text-emerald-100 text-sm font-medium mb-1">Selamat Datang,</p>
                    <h1 class="text-2xl font-bold text-white truncate max-w-[200px]">
                        <?php echo e(Auth::user()->name); ?>

                    </h1>
                    <p class="text-xs text-emerald-100 opacity-80 mt-1">Guru / Pengajar</p>
                </div>
                
                <div class="flex items-center gap-3">
                    
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 border border-white/20 text-emerald-50 hover:bg-red-500/80 hover:text-white hover:border-red-500/50 transition-all duration-300 backdrop-blur-md shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>

                    
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full border-2 border-emerald-200/50 p-0.5 shadow-lg">
                        <div class="w-full h-full bg-white rounded-full flex items-center justify-center text-emerald-600 font-bold text-lg">
                            <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="px-6 -mt-12 relative z-20">
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-50">
                <h3 class="text-gray-800 font-bold text-sm mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 bg-emerald-500 rounded-full"></span>
                    Menu Guru
                </h3>
                
                <div class="grid grid-cols-4 gap-4">
                    
                    <a href="<?php echo e(route('sekolah.guru.absensi.kehadiran.index')); ?>" class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl border border-blue-100 flex items-center justify-center text-blue-600 group-active:scale-95 transition-all duration-200 shadow-sm group-hover:bg-blue-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[10px] text-gray-600 font-bold text-center leading-tight">Absen<br>Hadir</span>
                    </a>

                    
                    <a href="<?php echo e(route('sekolah.guru.jadwal.index')); ?>" class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 bg-indigo-50 rounded-2xl border border-indigo-100 flex items-center justify-center text-indigo-600 group-active:scale-95 transition-all duration-200 shadow-sm group-hover:bg-indigo-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="text-[10px] text-gray-600 font-bold text-center leading-tight">Jadwal<br>Mengajar</span>
                    </a>

                    
                    <a href="<?php echo e(route('sekolah.guru.nilai.index')); ?>" class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center justify-center text-emerald-600 group-active:scale-95 transition-all duration-200 shadow-sm group-hover:bg-emerald-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="text-[10px] text-gray-600 font-bold text-center leading-tight">Input<br>Nilai</span>
                    </a>

                    
                    <a href="<?php echo e(route('sekolah.guru.izin.index')); ?>" class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 bg-orange-50 rounded-2xl border border-orange-100 flex items-center justify-center text-orange-600 group-active:scale-95 transition-all duration-200 shadow-sm group-hover:bg-orange-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-[10px] text-gray-600 font-bold text-center leading-tight">Izin<br>Sakit</span>
                    </a>
                </div>
            </div>
        </div>

        
        <div class="px-6 mt-8">
            <div class="flex justify-between items-end mb-4">
                <h3 class="text-gray-800 font-bold text-lg flex items-center gap-2">
                    <span class="w-1 h-5 bg-emerald-500 rounded-full"></span>
                    Jadwal Hari Ini
                </h3>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider bg-white px-2 py-1 rounded shadow-sm">
                    <?php echo e(Carbon::now()->isoFormat('dddd, D MMM')); ?>

                </p>
            </div>

            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $jadwalHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center relative overflow-hidden group active:scale-[0.99] transition-transform">
                        
                        <?php
                            // Hitung waktu mulai (dikurangi 15 menit)
                            $waktuMulai = Carbon::parse($jadwal->jam_mulai);
                            $waktuBuka = $waktuMulai->copy()->subMinutes(15);
                            $waktuSelesai = Carbon::parse($jadwal->jam_selesai);
                            $sekarang = Carbon::now();

                            // Cek apakah tombol boleh aktif: Sekarang >= 15 menit sebelum mulai
                            $isButtonActive = $sekarang->greaterThanOrEqualTo($waktuBuka) && $sekarang->lessThanOrEqualTo($waktuSelesai);
                            
                            // Tentukan warna garis dekorasi
                            $garisWarna = $isButtonActive ? 'bg-indigo-500' : 'bg-gray-300';
                            
                            // Tentukan apakah jadwal sudah lewat
                            $isJadwalLewat = $sekarang->greaterThan($waktuSelesai);
                        ?>

                        
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($isJadwalLewat ? 'bg-red-500' : $garisWarna); ?>"></div>

                        <div class="pl-3">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">
                                    <?php echo e($jadwal->jam_mulai); ?> - <?php echo e($jadwal->jam_selesai); ?>

                                </span>
                                <span class="text-[10px] text-gray-400 uppercase font-bold"><?php echo e($jadwal->kelas->nama_kelas); ?></span>
                            </div>
                            <h4 class="text-gray-800 font-bold text-sm"><?php echo e($jadwal->mataPelajaran->nama_mapel); ?></h4>
                        </div>

                        
                        <div>
                            <?php if($isJadwalLewat): ?>
                                <span class="inline-flex items-center px-3 py-2 bg-red-100 text-red-600 border border-red-200 rounded-md font-bold text-xs uppercase tracking-widest cursor-not-allowed">
                                    Lewat
                                </span>
                            <?php elseif($isButtonActive): ?>
                                <a href="<?php echo e(route('sekolah.guru.jadwal.show', $jadwal->id)); ?>" class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 shadow-md">
                                    Mulai
                                </a>
                            <?php else: ?>
                                <button disabled class="inline-flex items-center px-3 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-600 uppercase tracking-widest cursor-not-allowed" title="Bisa dibuka mulai <?php echo e($waktuBuka->format('H:i')); ?>">
                                    Belum Waktunya
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-10 bg-white rounded-2xl border border-dashed border-gray-200">
                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <p class="text-gray-500 text-xs font-medium">Tidak ada jadwal mengajar hari ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/guru/dashboard.blade.php ENDPATH**/ ?>