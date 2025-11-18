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
        
        
        <div class="bg-emerald-600 pt-6 pb-10 px-6 rounded-b-[30px] shadow-lg sticky top-0 z-30">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold text-white">Data Santri</h1>
                <a href="<?php echo e(route('pengurus.dashboard')); ?>" class="bg-white/20 p-2 rounded-xl backdrop-blur-sm text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </a>
            </div>

            
            <form method="GET" action="<?php echo e(route('pengurus.santri.index')); ?>">
                <div class="relative">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama atau NIS..." 
                        class="w-full pl-10 pr-4 py-3 rounded-2xl border-0 focus:ring-2 focus:ring-emerald-300 text-sm shadow-md placeholder-gray-400">
                    <div class="absolute left-3 top-3 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </form>
        </div>

        
        <div class="px-4 -mt-6 space-y-3 relative z-20">
            <?php $__empty_1 = true; $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('pengurus.santri.show', $santri->id)); ?>" class="block bg-white p-4 rounded-2xl shadow-sm border border-gray-100 active:scale-98 transition duration-150">
                    <div class="flex items-center gap-4">
                        
                        <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-700 font-bold text-lg shadow-inner">
                            <?php echo e(substr($santri->full_name, 0, 1)); ?>

                        </div>
                        
                        
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 truncate"><?php echo e($santri->full_name); ?></h3>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <span class="bg-gray-100 px-1.5 py-0.5 rounded"><?php echo e($santri->nis); ?></span>
                                <span>• <?php echo e($santri->kelas->nama_kelas ?? 'Belum ada kelas'); ?></span>
                            </p>
                        </div>

                        
                        <?php if($santri->status == 'active'): ?>
                            <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-lg shadow-emerald-200"></div>
                        <?php else: ?>
                            <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-10">
                    <div class="inline-block p-4 rounded-full bg-gray-100 mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm">Data santri tidak ditemukan.</p>
                </div>
            <?php endif; ?>

            
            <div class="mt-4">
                <?php echo e($santris->links()); ?>

            </div>
        </div>

        
        <a href="<?php echo e(route('pengurus.santri.create')); ?>" class="fixed bottom-24 right-6 bg-emerald-600 text-white w-14 h-14 rounded-full shadow-xl shadow-emerald-300 flex items-center justify-center hover:bg-emerald-700 active:scale-90 transition z-50">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </a>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/santri/index.blade.php ENDPATH**/ ?>