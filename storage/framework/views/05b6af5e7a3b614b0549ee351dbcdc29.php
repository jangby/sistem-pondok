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
            <h2 class="font-bold text-lg text-gray-800">Progres Hafalan</h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6 px-4 min-h-screen bg-gray-50">
        
        
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl p-5 text-white shadow-lg mb-6 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-amber-100 text-xs uppercase tracking-wider font-medium">Total Setoran</p>
                <h3 class="text-3xl font-bold mt-1"><?php echo e($totalSetoran ?? 0); ?> <span class="text-sm font-normal text-amber-100">Kali</span></h3>
                <p class="text-xs mt-2 opacity-90">Terus semangati ananda untuk menghafal!</p>
            </div>
            <svg class="absolute -bottom-4 -right-4 w-24 h-24 text-white opacity-10" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.963 7.963 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
        </div>

        
        <div class="space-y-4 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">
            
            <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    
                    
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-slate-100 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    
                    
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex justify-between items-start mb-1">
                            <time class="font-caveat font-bold text-emerald-600 text-sm">
                                <?php echo e($item->created_at->isoFormat('D MMM Y')); ?>

                            </time>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold <?php echo e($item->jenis_setoran == 'ziyadah' ? 'bg-blue-100 text-blue-600' : 'bg-amber-100 text-amber-600'); ?>">
                                <?php echo e(strtoupper($item->jenis_setoran)); ?>

                            </span>
                        </div>
                        
                        <div class="text-gray-800 font-bold text-base mb-1">
                            <?php echo e($item->materi); ?> 
                            <?php if($item->rentang != '-'): ?>
                                <span class="text-gray-500 font-normal text-sm block"><?php echo e($item->rentang); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mt-2 flex justify-between items-end">
                            <div>
                                <p class="text-[10px] text-gray-400">Penyimak:</p>
                                <p class="text-xs text-gray-600"><?php echo e($item->ustadz->nama_lengkap ?? '-'); ?></p>
                            </div>
                            <div class="text-right">
                                <span class="block text-xl font-bold <?php echo e(in_array($item->predikat, ['A', 'Mumtaz']) ? 'text-green-600' : (in_array($item->predikat, ['B', 'Jayyid']) ? 'text-blue-600' : 'text-red-600')); ?>">
                                    <?php echo e($item->predikat); ?>

                                </span>
                            </div>
                        </div>
                        
                        <?php if($item->catatan): ?>
                            <div class="mt-2 pt-2 border-t border-dashed border-gray-100 text-xs text-gray-500 italic">
                                "<?php echo e($item->catatan); ?>"
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-10 ml-8">
                    <p class="text-gray-400 text-sm">Belum ada riwayat hafalan.</p>
                </div>
            <?php endif; ?>

        </div>

        <div class="mt-6 ml-8">
            <?php echo e($history->links()); ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/orangtua/monitoring/hafalan.blade.php ENDPATH**/ ?>