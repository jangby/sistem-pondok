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
        
        
        <div class="bg-emerald-600 pt-6 pb-12 px-5 rounded-b-[25px] shadow-md relative overflow-hidden sticky top-0 z-30">
            
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-8 -mt-8 blur-2xl"></div>
            
            <div class="relative z-10 flex items-center gap-3 text-white">
                <a href="<?php echo e(route('ustadz.dashboard')); ?>" class="bg-white/20 p-2 rounded-lg hover:bg-white/30 transition backdrop-blur-md flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div class="flex-grow">
                    <h1 class="text-lg font-bold leading-tight">Jadwal Mengawas</h1>
                    <p class="text-[11px] text-emerald-100 opacity-90">Tahun Ajaran Aktif</p>
                </div>
            </div>
        </div>

        
        
        <div class="px-5 -mt-0 relative z-20 space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('ustadz.ujian.show', $jadwal->id)); ?>" class="block bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition active:scale-[0.98] group relative overflow-hidden">
                    
                    
                    <div class="absolute left-0 top-0 bottom-0 w-1 <?php echo e($jadwal->jenis_ujian == 'uts' ? 'bg-blue-500' : 'bg-purple-500'); ?>"></div>

                    <div class="ml-2">
                        
                        <div class="flex justify-between items-start mb-2 border-b border-gray-50 pb-2">
                            <div class="flex items-center gap-2">
                                <div class="text-xs font-bold text-gray-700 bg-gray-100 px-2 py-1 rounded">
                                    <?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->isoFormat('dddd, D MMM')); ?>

                                </div>
                            </div>
                            <div class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <?php echo e(\Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')); ?>

                            </div>
                        </div>
                        
                        
                        <div class="mb-2">
                            <h3 class="text-base font-bold text-gray-800 leading-snug group-hover:text-emerald-700 transition">
                                <?php echo e($jadwal->mapel->nama_mapel); ?>

                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5"><?php echo e($jadwal->mustawa->nama); ?></p>
                        </div>

                        
                        <div class="flex gap-2 mt-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border 
                                <?php echo e($jadwal->jenis_ujian == 'uts' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-purple-50 text-purple-600 border-purple-100'); ?>">
                                <?php echo e($jadwal->jenis_ujian); ?>

                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border bg-emerald-50 text-emerald-600 border-emerald-100">
                                <?php echo e(ucfirst($jadwal->kategori_tes)); ?>

                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-dashed border-gray-300">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-sm text-gray-500 font-medium">Belum ada jadwal ujian.</p>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/ustadz/ujian/index.blade.php ENDPATH**/ ?>