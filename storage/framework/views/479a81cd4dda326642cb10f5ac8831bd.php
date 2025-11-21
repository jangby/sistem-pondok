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
        
        
        <div class="bg-white px-6 py-4 flex items-center gap-4 border-b border-gray-100 sticky top-0 z-30">
            <a href="<?php echo e(route('pengurus.perizinan.index')); ?>" class="text-gray-500 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-800">Riwayat Izin</h1>
        </div>

        
        <div class="p-4 bg-white border-b border-gray-100">
            <form method="GET" action="<?php echo e(route('pengurus.perizinan.history')); ?>" class="flex gap-2">
                <input type="date" name="date" value="<?php echo e(request('date')); ?>" class="w-1/3 rounded-xl border-gray-200 text-xs focus:ring-purple-500">
                <div class="relative w-2/3">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Santri..." class="w-full pl-9 pr-3 py-2 rounded-xl border-gray-200 text-xs focus:ring-purple-500">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <button type="submit" class="bg-gray-900 text-white p-2 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>

        
        <div class="px-4 py-4 space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $izin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('pengurus.perizinan.show', $izin->id)); ?>" class="block bg-white p-4 rounded-xl shadow-sm border border-gray-100 active:scale-98 transition">
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-gray-800 text-sm"><?php echo e($izin->santri->full_name); ?></h3>
                        <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase bg-green-100 text-green-700">
                            Sudah Kembali
                        </span>
                    </div>
                    
                    <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Keluar: <?php echo e($izin->tgl_mulai->format('d M Y')); ?></span>
                    </div>

                    <div class="mt-2 pt-2 border-t border-gray-50 flex justify-between text-[10px] text-gray-400 items-center">
                        <span class="bg-gray-100 px-1.5 py-0.5 rounded">
                             <?php echo e($izin->jenis_izin == 'keluar_sebentar' ? 'Keluar Sebentar' : 'Pulang Bermalam'); ?>

                        </span>
                        <span>Kembali: <strong><?php echo e($izin->tgl_kembali_realisasi ? $izin->tgl_kembali_realisasi->format('d M H:i') : '-'); ?></strong></span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-10 text-gray-400 text-sm">Belum ada data riwayat.</div>
            <?php endif; ?>

            <div class="mt-4">
                <?php echo e($riwayat->links('pagination::tailwind')); ?>

            </div>
        </div>
    </div>

    <?php echo $__env->make('layouts.pengurus-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/perizinan/history.blade.php ENDPATH**/ ?>