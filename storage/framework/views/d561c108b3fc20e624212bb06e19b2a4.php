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

    
    <div class="min-h-screen bg-gray-100 pb-24">
        
        
        <div class="bg-white px-6 py-4 flex items-center gap-4 border-b border-gray-100 sticky top-0 z-30 shadow-sm">
            <a href="<?php echo e(route('pengurus.uks.index')); ?>" class="text-gray-500 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-800">Riwayat Lengkap</h1>
        </div>

        
        <div class="p-4 bg-white border-b border-gray-100">
            <form method="GET" action="<?php echo e(route('pengurus.uks.history')); ?>" class="flex gap-2">
                <input type="date" name="date" value="<?php echo e(request('date')); ?>" class="w-1/3 rounded-xl border-gray-200 text-xs focus:ring-red-500 focus:border-red-500">
                <div class="relative w-2/3">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Nama..." class="w-full pl-9 pr-3 py-2.5 rounded-xl border-gray-200 text-xs focus:ring-red-500 focus:border-red-500">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                
                
                <button type="submit" class="bg-red-600 text-white p-2.5 rounded-xl transition active:scale-95 shadow-sm hover:bg-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>

        
        <div class="px-5 py-6 space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                
                <?php
                    $statusInfo = [
                        'sembuh' => ['color' => 'green', 'label' => 'Sembuh'],
                        'rujuk_rs' => ['color' => 'red', 'label' => 'Rujuk RS'],
                        'dirawat_di_asrama' => ['color' => 'orange', 'label' => 'Rawat Asrama'],
                        'sakit_ringan' => ['color' => 'yellow', 'label' => 'Sakit Ringan'],
                    ];
                    $info = $statusInfo[$data->status] ?? ['color' => 'gray', 'label' => ucfirst(str_replace('_', ' ', $data->status))];
                ?>

                
                <a href="<?php echo e(route('pengurus.uks.show', $data->id)); ?>" 
                   class="block bg-white p-4 rounded-2xl shadow-md shadow-gray-900/5 border-l-4 border-<?php echo e($info['color']); ?>-500 border-y border-r border-gray-100/80 active:scale-[0.98] transition duration-150 group hover:border-<?php echo e($info['color']); ?>-500/30">
                    
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-gray-800 text-sm group-hover:text-red-600 transition"><?php echo e($data->santri->full_name); ?></h3>
                        
                        
                        <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase bg-<?php echo e($info['color']); ?>-100 text-<?php echo e($info['color']); ?>-700 border border-<?php echo e($info['color']); ?>-200/50">
                            <?php echo e($info['label']); ?>

                        </span>
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-1 truncate"><?php echo e($data->keluhan); ?></p>
                    
                    <div class="mt-2 pt-2 border-t border-gray-50 flex justify-between text-[10px] text-gray-400">
                        <span><?php echo e($data->created_at->format('d M Y')); ?></span>
                        <span><?php echo e($data->created_at->format('H:i')); ?> WIB</span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
                    <div class="inline-flex bg-gray-50 p-3 rounded-full mb-3 border border-gray-100">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Data Tidak Ditemukan</p>
                    <p class="text-xs text-gray-400 mt-1">Coba ubah filter pencarian Anda.</p>
                </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/uks/history.blade.php ENDPATH**/ ?>