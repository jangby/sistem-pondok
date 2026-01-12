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

    <div class="min-h-screen bg-gray-50 pb-20">
        
        <div class="bg-white px-6 py-4 border-b border-gray-100 sticky top-0 z-30 flex items-center gap-4 shadow-sm">
            <a href="<?php echo e(route('orangtua.monitoring.index', $santri->id)); ?>" class="text-gray-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            <h1 class="font-bold text-lg text-gray-800">Riwayat Absensi</h1>
        </div>

        
        <div class="p-4 overflow-x-auto whitespace-nowrap no-scrollbar">
            <div class="flex gap-2">
                <a href="?kategori=jamaah" class="px-4 py-2 rounded-full text-xs font-bold transition <?php echo e($kategori == 'jamaah' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-gray-500 border border-gray-200'); ?>">Sholat</a>
                <a href="?kategori=asrama" class="px-4 py-2 rounded-full text-xs font-bold transition <?php echo e($kategori == 'asrama' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-gray-500 border border-gray-200'); ?>">Asrama</a>
                <a href="?kategori=kegiatan" class="px-4 py-2 rounded-full text-xs font-bold transition <?php echo e($kategori == 'kegiatan' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-gray-500 border border-gray-200'); ?>">Kegiatan</a>
            </div>
        </div>

        
        <div class="px-5 space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white p-4 rounded-2xl border border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm"><?php echo e($h->nama_kegiatan); ?></h3>
                        <p class="text-[10px] text-gray-500"><?php echo e(\Carbon\Carbon::parse($h->waktu_catat)->format('d M Y, H:i')); ?></p>
                    </div>
                    <span class="bg-green-100 text-green-700 text-[10px] px-2 py-1 rounded font-bold uppercase"><?php echo e($h->status); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-10 text-gray-400 text-sm bg-white rounded-2xl border-dashed border border-gray-200 mt-2">
                    Belum ada data absensi di kategori ini.
                </div>
            <?php endif; ?>

            <div class="mt-4"><?php echo e($history->withQueryString()->links('pagination::tailwind')); ?></div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/orangtua/monitoring/absensi.blade.php ENDPATH**/ ?>