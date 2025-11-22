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

    <div class="min-h-screen bg-gray-50 pb-28">
        <div class="bg-orange-500 pt-8 pb-20 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Absensi Kegiatan</h1>
                <a href="<?php echo e(route('pengurus.absensi.index')); ?>" class="bg-white/20 p-2 rounded-xl text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            </div>
        </div>

        <div class="px-5 -mt-12 relative z-20 space-y-4">
            
            <div class="bg-white rounded-3xl shadow-lg p-6 text-center border border-gray-100">
                <h2 class="text-3xl font-black text-orange-500"><?php echo e($kegiatans->count()); ?></h2>
                <p class="text-xs text-gray-400 font-bold uppercase">Kegiatan Terdaftar</p>
            </div>

            
            <a href="<?php echo e(route('pengurus.absensi.kegiatan.settings')); ?>" class="flex items-center gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100 group active:scale-95 transition">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Buat / Kelola Kegiatan</h3>
                    <p class="text-xs text-gray-500">Setting jadwal & peserta</p>
                </div>
            </a>

            
            <a href="<?php echo e(route('pengurus.absensi.kegiatan.scan')); ?>" class="flex items-center gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100 group active:scale-95 transition">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M19 19h-5m-9-6h5a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zM6 21v-2a1 1 0 011-1h10V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Mulai Absensi</h3>
                    <p class="text-xs text-gray-500">Scan kartu peserta</p>
                </div>
            </a>

            
            <a href="<?php echo e(route('pengurus.absensi.kegiatan.rekap')); ?>" class="flex items-center gap-4 bg-white p-5 rounded-2xl shadow-sm border border-gray-100 group active:scale-95 transition">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Laporan Kehadiran</h3>
                    <p class="text-xs text-gray-500">Lihat grafik & detail</p>
                </div>
            </a>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/absensi/kegiatan/index.blade.php ENDPATH**/ ?>