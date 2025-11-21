<?php
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

    <div class="min-h-screen bg-gray-50 pb-10">
        
        
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden sticky top-0 z-30">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-400 opacity-10 rounded-full -ml-10 -mb-10 blur-xl"></div>
            
            <div class="relative z-10 flex items-center gap-4">
                
                <a href="<?php echo e(route('ustadz.dashboard')); ?>" class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 border border-white/20 text-emerald-50 hover:bg-white/20 transition-all duration-300 backdrop-blur-md shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                
                <div>
                    <h1 class="text-2xl font-bold text-white">Jadwal Mengajar</h1>
                    <p class="text-xs text-emerald-100 opacity-80 mt-1">Pilih jadwal untuk mulai kelas</p>
                </div>
            </div>
        </div>

        
        <div class="px-6 -mt-12 relative z-20 space-y-8">

            <?php
                $hariIni = \Carbon\Carbon::now()->isoFormat('dddd'); 
            ?>

            <?php $__empty_1 = true; $__currentLoopData = $urutanHari; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if(isset($jadwalPerHari[$hari])): ?>
                    
                    
                    <div>
                        
                        <div class="flex items-center gap-3 mb-4 ml-1">
                            <?php if($hari == $hariIni): ?>
                                <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-[10px] font-bold shadow-sm animate-pulse uppercase tracking-wider">
                                    Hari Ini
                                </span>
                            <?php endif; ?>
                            <h3 class="text-gray-700 font-bold text-lg <?php echo e($hari == $hariIni ? 'text-emerald-700' : ''); ?>">
                                <?php echo e($hari); ?>

                            </h3>
                            <div class="h-px bg-gray-200 flex-grow rounded-full"></div>
                        </div>

                        
                        <div class="space-y-4">
                            <?php $__currentLoopData = $jadwalPerHari[$hari]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $isToday = ($hari == $hariIni);
                                    $waktuMulai = Carbon::parse($jadwal->jam_mulai);
                                    $waktuSelesai = Carbon::parse($jadwal->jam_selesai);
                                    $sekarang = Carbon::now();
                                    $sudahLewat = $isToday && $sekarang->gt($waktuSelesai);
                                    
                                    // Logika warna garis samping
                                    $lineColor = $isToday ? ($sudahLewat ? 'bg-gray-300' : 'bg-emerald-500') : 'bg-blue-100';
                                ?>

                                
                                <a href="<?php echo e(route('ustadz.absensi.menu', $jadwal->id)); ?>" 
                                   class="block bg-white p-5 rounded-2xl border border-gray-50 shadow-sm relative overflow-hidden group active:scale-[0.98] transition-all duration-200 hover:shadow-md">
                                    
                                    
                                    <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($lineColor); ?>"></div>

                                    <div class="flex justify-between items-center pl-3">
                                        
                                        
                                        <div class="flex-grow">
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                                    <?php echo e($waktuMulai->format('H:i')); ?> - <?php echo e($waktuSelesai->format('H:i')); ?>

                                                </span>
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                                    <?php echo e($jadwal->mustawa->nama); ?>

                                                </span>
                                            </div>
                                            
                                            <h4 class="font-bold text-gray-800 text-lg leading-tight group-hover:text-emerald-700 transition-colors">
                                                <?php echo e($jadwal->mapel->nama_mapel); ?>

                                            </h4>
                                            <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                <?php echo e($jadwal->mapel->nama_kitab); ?>

                                            </p>
                                        </div>

                                        
                                        <div class="flex-shrink-0 ml-4">
                                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </div>
                                        </div>

                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-50 p-8 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-gray-800 font-bold">Belum Ada Jadwal</h3>
                    <p class="text-gray-500 text-xs mt-1 max-w-xs mx-auto">Jadwal mengajar belum ditambahkan oleh Admin.</p>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/ustadz/jadwal/index.blade.php ENDPATH**/ ?>