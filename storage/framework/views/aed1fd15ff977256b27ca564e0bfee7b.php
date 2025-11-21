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
        
        
        <div class="bg-emerald-600 pt-8 pb-20 px-6 rounded-b-[35px] shadow-lg">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Manajemen Asrama</h1>
                <div class="flex gap-2">
                    
                    <a href="<?php echo e(route('pengurus.asrama.pdf.data')); ?>" target="_blank" class="bg-white/20 p-2 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition" title="Cetak Data Asrama">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </a>
                    <a href="<?php echo e(route('pengurus.dashboard')); ?>" class="bg-white/20 p-2 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                </div>
            </div>
            <p class="text-emerald-100 text-xs mt-1 font-medium">Kelola gedung, penghuni, & absensi ketua.</p>
        </div>

        <div class="px-5 -mt-12 space-y-4 relative z-10">
            
            
            <a href="<?php echo e(route('pengurus.asrama.list', 'Putra')); ?>" class="block bg-white p-6 rounded-3xl shadow-lg border border-gray-100 active:scale-95 transition group">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition">Asrama Putra</h3>
                        <p class="text-sm text-gray-500">Kelola asrama santri laki-laki</p>
                    </div>
                </div>
            </a>

            
            <a href="<?php echo e(route('pengurus.asrama.list', 'Putri')); ?>" class="block bg-white p-6 rounded-3xl shadow-lg border border-gray-100 active:scale-95 transition group">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-pink-50 text-pink-600 rounded-2xl flex items-center justify-center group-hover:bg-pink-600 group-hover:text-white transition shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 group-hover:text-pink-600 transition">Asrama Putri</h3>
                        <p class="text-sm text-gray-500">Kelola asrama santri perempuan</p>
                    </div>
                </div>
            </a>

            
            <a href="<?php echo e(route('pengurus.asrama.ketua.index')); ?>" class="block bg-white p-6 rounded-3xl shadow-lg border border-gray-100 active:scale-95 transition group">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 group-hover:text-purple-600 transition">Absensi Ketua</h3>
                        <p class="text-sm text-gray-500">Scan kehadiran ketua asrama</p>
                    </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/asrama/index.blade.php ENDPATH**/ ?>