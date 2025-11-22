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
            <a href="<?php echo e(route('orangtua.monitoring.index', $santri->id)); ?>" class="text-gray-500 hover:text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="font-bold text-lg text-gray-800">Riwayat Perizinan</h1>
        </div>

        
        <div class="px-5 mt-5 space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    
                    
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($h->status == 'kembali' ? 'bg-green-500' : ($h->tgl_selesai_rencana < now() ? 'bg-red-500' : 'bg-blue-500')); ?>"></div>

                    <div class="pl-3">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="block text-xs font-bold text-gray-500 uppercase">
                                    <?php echo e($h->jenis_izin == 'keluar_sebentar' ? 'Keluar Sebentar' : 'Pulang Bermalam'); ?>

                                </span>
                                <span class="text-[10px] text-gray-400"><?php echo e($h->created_at->format('d M Y')); ?></span>
                            </div>
                            
                            <?php if($h->status == 'kembali'): ?>
                                <span class="text-[10px] bg-green-100 text-green-700 px-2 py-1 rounded font-bold uppercase">Sudah Kembali</span>
                            <?php elseif($h->tgl_selesai_rencana < now()): ?>
                                <span class="text-[10px] bg-red-100 text-red-700 px-2 py-1 rounded font-bold uppercase animate-pulse">Terlambat</span>
                            <?php else: ?>
                                <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-1 rounded font-bold uppercase">Sedang Izin</span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <p class="text-sm font-bold text-gray-800 line-clamp-2">"<?php echo e($h->alasan); ?>"</p>
                        </div>

                        <div class="flex items-center justify-between bg-gray-50 p-2 rounded-lg text-xs">
                            <div class="text-center w-1/2 border-r border-gray-200">
                                <span class="block text-[9px] text-gray-400 uppercase">Mulai</span>
                                <span class="font-bold text-gray-700"><?php echo e($h->tgl_mulai->format('d M H:i')); ?></span>
                            </div>
                            <div class="text-center w-1/2">
                                <span class="block text-[9px] text-gray-400 uppercase">Wajib Kembali</span>
                                <span class="font-bold text-gray-700"><?php echo e($h->tgl_selesai_rencana->format('d M H:i')); ?></span>
                            </div>
                        </div>

                        <?php if($h->tgl_kembali_realisasi): ?>
                            <div class="mt-2 text-center">
                                <span class="text-[10px] text-green-600 font-bold">
                                    Aktual Kembali: <?php echo e($h->tgl_kembali_realisasi->format('d M H:i')); ?>

                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-12 text-gray-400 text-sm bg-white rounded-2xl border-2 border-dashed border-gray-200">
                    <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-2 text-purple-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p>Belum ada riwayat perizinan.</p>
                </div>
            <?php endif; ?>

            <div class="mt-4 pb-8">
                <?php echo e($history->links('pagination::tailwind')); ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/orangtua/monitoring/izin.blade.php ENDPATH**/ ?>