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
        
        
        <div class="bg-emerald-600 px-5 pt-8 pb-6 shadow-md rounded-b-[25px] relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
                <div class="w-32 h-32 bg-white opacity-10 rounded-full absolute -top-10 -left-10 blur-2xl"></div>
                <div class="w-40 h-40 bg-emerald-400 opacity-10 rounded-full absolute bottom-0 right-0 blur-xl"></div>
            </div>
            
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('orangtua.dashboard')); ?>" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h1 class="text-lg font-bold text-white">Daftar Tagihan</h1>
                </div>
                
                
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="text-white p-1.5 bg-emerald-700/30 rounded-lg backdrop-blur-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </button>
                    
                    <div x-show="open" @click.outside="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-xl py-1 z-50 border border-gray-100 text-sm" style="display: none;">
                        <a href="<?php echo e(request()->fullUrlWithQuery(['status' => ''])); ?>" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Semua</a>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['status' => 'pending'])); ?>" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Belum Lunas</a>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['status' => 'paid'])); ?>" class="block px-4 py-2 text-gray-700 hover:bg-emerald-50">Lunas</a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="px-4 mt-5 space-y-3">
            
            <?php $__empty_1 = true; $__currentLoopData = $tagihans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tagihan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                
                <a href="<?php echo e(route('orangtua.tagihan.show', $tagihan->id)); ?>" class="block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative transition-transform active:scale-[0.98] hover:shadow-md group">
                    
                    
                    <div class="absolute left-0 top-0 bottom-0 w-1 
                        <?php if($tagihan->status == 'paid'): ?> bg-emerald-500 
                        <?php elseif($tagihan->status == 'partial'): ?> bg-yellow-500 
                        <?php else: ?> bg-red-500 <?php endif; ?>">
                    </div>

                    <div class="py-3 pr-4 pl-5">
                        <div class="flex justify-between items-start gap-2">
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                        <?php echo e($tagihan->santri->full_name); ?>

                                    </span>
                                    <span class="text-[10px] text-gray-300">•</span>
                                    <span class="text-[10px] text-gray-400">
                                        <?php echo e(\Carbon\Carbon::parse($tagihan->due_date)->format('d M Y')); ?>

                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-gray-800 leading-tight truncate">
                                    <?php echo e($tagihan->jenisPembayaran->name); ?>

                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5 font-mono">
                                    #<?php echo e($tagihan->invoice_number); ?>

                                </p>
                            </div>

                            
                            <div class="text-right shrink-0">
                                <?php if($tagihan->status == 'paid'): ?>
                                    <div class="flex flex-col items-end">
                                        <span class="inline-block px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold mb-1">
                                            LUNAS
                                        </span>
                                        
                                        <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                <?php elseif($tagihan->status == 'partial'): ?>
                                    <span class="inline-block px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-[10px] font-bold mb-1">
                                        DICICIL
                                    </span>
                                    <p class="text-sm font-bold text-gray-900">
                                        Rp <?php echo e(number_format($tagihan->nominal_tagihan, 0, ',', '.')); ?>

                                    </p>
                                <?php else: ?>
                                    <span class="inline-block px-2 py-0.5 bg-red-50 text-red-600 rounded text-[10px] font-bold mb-1">
                                        BELUM LUNAS
                                    </span>
                                    <p class="text-sm font-bold text-gray-900">
                                        Rp <?php echo e(number_format($tagihan->nominal_tagihan, 0, ',', '.')); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                        <?php if($tagihan->status != 'paid'): ?>
                            <div class="mt-3 pt-2 border-t border-gray-50 flex justify-end">
                                <span class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-[11px] font-bold uppercase tracking-wider rounded-md shadow-sm group-hover:bg-emerald-700 transition w-full justify-center sm:w-auto">
                                    Bayar Sekarang
                                    <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-gray-500 text-xs">Tidak ada tagihan saat ini.</p>
                </div>
            <?php endif; ?>

            
            <div class="pt-4 pb-8 px-2">
                <?php echo e($tagihans->onEachSide(1)->links()); ?> 
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/orangtua/tagihan/index.blade.php ENDPATH**/ ?>