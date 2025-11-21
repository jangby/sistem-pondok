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

    <div class="min-h-screen bg-gray-50 pb-24">
        
        
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden sticky top-0 z-30">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-400 opacity-10 rounded-full -ml-10 -mb-10 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-4 text-white">
                <a href="<?php echo e(route('ustadz.absensi.menu', $jadwal->id)); ?>" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition backdrop-blur-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold leading-tight">Riwayat Absensi</h1>
                    <p class="text-xs text-emerald-100 opacity-90 mt-1"><?php echo e($jadwal->mapel->nama_mapel); ?> • <?php echo e($jadwal->mustawa->nama); ?></p>
                </div>
            </div>
        </div>

        
        <div class="px-6 -mt-12 relative z-20 space-y-4">
            
            <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('ustadz.absensi.history.detail', ['jadwal' => $jadwal->id, 'tanggal' => $log->tanggal])); ?>" 
                   class="block bg-white p-4 rounded-2xl shadow-sm border border-gray-50 hover:shadow-md transition active:scale-[0.98] group">
                    
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            
                            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex flex-col items-center justify-center text-emerald-700 border border-emerald-100">
                                <span class="text-[10px] font-bold uppercase"><?php echo e(\Carbon\Carbon::parse($log->tanggal)->isoFormat('MMM')); ?></span>
                                <span class="text-lg font-bold leading-none"><?php echo e(\Carbon\Carbon::parse($log->tanggal)->format('d')); ?></span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm"><?php echo e(\Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd, D MMMM Y')); ?></h4>
                                <p class="text-[10px] text-gray-400 mt-0.5">Total Santri: <?php echo e($log->total_santri); ?></p>
                            </div>
                        </div>
                        <div class="text-gray-300 group-hover:text-emerald-500 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>

                    
                    <div class="flex gap-2">
                        
                        <div class="flex-1 bg-emerald-50 rounded-lg p-2 text-center border border-emerald-100">
                            <span class="block text-[10px] text-emerald-600 font-bold">Hadir</span>
                            <span class="block text-sm font-extrabold text-emerald-700"><?php echo e($log->hadir); ?></span>
                        </div>
                        
                        <div class="flex-1 bg-blue-50 rounded-lg p-2 text-center border border-blue-100">
                            <span class="block text-[10px] text-blue-600 font-bold">Sakit/Izin</span>
                            <span class="block text-sm font-extrabold text-blue-700"><?php echo e($log->sakit + $log->izin); ?></span>
                        </div>
                        
                        <div class="flex-1 bg-red-50 rounded-lg p-2 text-center border border-red-100">
                            <span class="block text-[10px] text-red-600 font-bold">Alpha</span>
                            <span class="block text-sm font-extrabold text-red-700"><?php echo e($log->alpa); ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Belum ada riwayat absensi.</p>
                </div>
            <?php endif; ?>

            <div class="mt-4">
                <?php echo e($riwayat->links()); ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/ustadz/absensi/history.blade.php ENDPATH**/ ?>