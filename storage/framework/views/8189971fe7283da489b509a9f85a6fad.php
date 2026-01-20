<?php
// Pastikan Carbon sudah di-use di layout atau file utama jika menggunakan Blade di luar Laravel
use Carbon\Carbon;
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
        
        
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="<?php echo e(route('sekolah.guru.dashboard')); ?>" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Jadwal Mengajar Saya</h1>
            </div>
        </div>

        
        <div class="px-5 -mt-16 relative z-20 space-y-8">
            
            <?php $__empty_1 = true; $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahunAjaran => $jadwalGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div>
                    
                    <div class="flex items-center justify-between mb-3 px-1">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">
                            <?php echo e($tahunAjaran); ?>

                        </h3>
                        <?php if($jadwalGroup->first()->tahunAjaran->is_active): ?>
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-200 shadow-sm">
                                AKTIF
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="space-y-3">
                        <?php $__currentLoopData = $jadwalGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            
                            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center relative overflow-hidden group active:scale-[0.99] transition-transform">
                                
                                <?php
                                    // Logika Waktu
                                    $hariSekarang = Carbon::now()->locale('id_ID')->isoFormat('dddd');
                                    // Karena data hari di DB mungkin "Senin" dan di Carbon "Senin", kita pastikan formatnya sama
                                    $isHariIni = strtolower($hariSekarang) == strtolower($jadwal->hari);
                                    
                                    $waktuMulai = Carbon::parse($jadwal->jam_mulai);
                                    $waktuBuka = $waktuMulai->copy()->subMinutes(15);
                                    $sekarang = Carbon::now();

                                    // Aktif jika: HARI INI dan SUDAH WAKTUNYA
                                    $isActive = $isHariIni && $sekarang->greaterThanOrEqualTo($waktuBuka);
                                    
                                    // Visual Styling
                                    $garisWarna = $isActive ? 'bg-indigo-500' : ($isHariIni ? 'bg-yellow-500' : 'bg-gray-300');
                                ?>

                                
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($garisWarna); ?>"></div>

                                <div class="pl-3 flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                                            <?php echo e($jadwal->hari); ?>

                                        </span>
                                        <span class="text-[10px] text-gray-400 uppercase font-bold"><?php echo e($jadwal->kelas->nama_kelas); ?></span>
                                    </div>
                                    <h4 class="text-gray-800 font-bold text-sm truncate"><?php echo e($jadwal->mataPelajaran->nama_mapel); ?></h4>
                                    <p class="text-[10px] text-gray-500 mt-0.5"><?php echo e($jadwal->jam_mulai); ?> - <?php echo e($jadwal->jam_selesai); ?></p>
                                </div>

                                
                                <div>
                                    <?php if($isActive): ?>
                                        <a href="<?php echo e(route('sekolah.guru.jadwal.show', $jadwal->id)); ?>" class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 shadow-md">
                                            Mulai
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-xs font-semibold cursor-not-allowed px-3 py-2" 
                                              title="<?php echo e($isHariIni ? 'Dibuka jam ' . $waktuBuka->format('H:i') : 'Bukan jadwal hari ini'); ?>">
                                            <?php if($isHariIni): ?>
                                                Dibuka <?php echo e($waktuBuka->format('H:i')); ?>

                                            <?php else: ?>
                                                <span class="text-red-500">Bukan Hari Ini</span>
                                            <?php endif; ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-300 p-10 text-center">
                    <p class="text-gray-500 text-sm font-medium">Anda belum memiliki jadwal mengajar.</p>
                </div>
            <?php endif; ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/guru/jadwal/index.blade.php ENDPATH**/ ?>