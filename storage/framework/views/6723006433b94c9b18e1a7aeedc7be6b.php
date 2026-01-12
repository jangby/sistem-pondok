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
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('orangtua.monitoring.index', $santri->id)); ?>" class="text-gray-500 hover:text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h2 class="font-bold text-lg text-gray-800">Absensi Mengaji</h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6 px-4 min-h-screen bg-gray-50">
        
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center text-teal-700 font-bold">
                <?php echo e(substr($santri->nama_lengkap, 0, 1)); ?>

            </div>
            <div>
                <h3 class="font-bold text-gray-800"><?php echo e($santri->nama_lengkap); ?></h3>
                <p class="text-xs text-gray-500">Kelas: <?php echo e($santri->mustawa->nama ?? '-'); ?></p>
            </div>
        </div>

        
        <div class="space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 <?php echo e($absen->status == 'H' ? 'border-teal-500' : ($absen->status == 'S' || $absen->status == 'I' ? 'border-amber-500' : 'border-red-500')); ?>">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-gray-800"><?php echo e($absen->jadwal->mapel->nama_mapel ?? 'Pelajaran'); ?></p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                <span class="font-medium"><?php echo e(\Carbon\Carbon::parse($absen->tanggal)->isoFormat('dddd, D MMM Y')); ?></span>
                                • <?php echo e($absen->jadwal->jam_mulai ?? '-'); ?>

                            </p>
                            <p class="text-[10px] text-gray-400 mt-1">Ustadz: <?php echo e($absen->jadwal->ustadz->nama_lengkap ?? '-'); ?></p>
                        </div>
                        <div class="text-center">
                            <?php
                                $statusText = match($absen->status) {
                                    'H' => 'Hadir', 'I' => 'Izin', 'S' => 'Sakit', 'A' => 'Alpha', default => '-'
                                };
                                $bgStatus = match($absen->status) {
                                    'H' => 'bg-teal-100 text-teal-700',
                                    'I', 'S' => 'bg-amber-100 text-amber-700',
                                    'A' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100'
                                };
                            ?>
                            <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo e($bgStatus); ?>">
                                <?php echo e($statusText); ?>

                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-10 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-sm">Belum ada data absensi mengaji.</p>
                </div>
            <?php endif; ?>

            <div class="mt-4">
                <?php echo e($history->links()); ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/orangtua/monitoring/diniyah.blade.php ENDPATH**/ ?>