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
    <div class="min-h-screen bg-gray-50 pb-20 font-sans">
        
        
        <div class="bg-blue-600 px-6 pt-8 pb-10 rounded-b-[30px] shadow-lg sticky top-0 z-30">
            <div class="flex items-center gap-4 mb-4">
                <a href="<?php echo e(route('sekolah.petugas.dashboard')); ?>" class="text-white hover:bg-white/20 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-xl font-bold text-white">Data Buku</h1>
            </div>
            
            <form>
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari judul atau penulis..." value="<?php echo e(request('search')); ?>" class="w-full pl-10 pr-4 py-3 rounded-xl border-none focus:ring-2 focus:ring-blue-300 shadow-sm bg-white/95 backdrop-blur">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
        </div>

        
        <div class="px-6 -mt-0 space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $bukus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $buku): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex gap-4 items-center">
                    
                    <div class="w-16 h-20 bg-gray-100 rounded-lg flex items-center justify-center text-gray-300 shrink-0 overflow-hidden">
                        <?php if($buku->cover): ?>
                            <img src="<?php echo e(asset('storage/'.$buku->cover)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-800 truncate"><?php echo e($buku->judul); ?></h3>
                        <p class="text-xs text-gray-500 mb-1"><?php echo e($buku->penulis); ?></p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[10px] font-bold">Stok: <?php echo e($buku->stok); ?></span>
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-md text-[10px] font-mono"><?php echo e($buku->kode_buku); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-10 text-gray-400">
                    <p class="text-sm">Buku tidak ditemukan.</p>
                </div>
            <?php endif; ?>

            
            <div class="py-4">
                <?php echo e($bukus->links()); ?> 
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/petugas/buku/index.blade.php ENDPATH**/ ?>