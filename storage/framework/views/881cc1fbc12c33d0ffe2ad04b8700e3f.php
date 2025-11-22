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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Jurnal Hafalan')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <style>
        nav.bg-white.border-b { display: none !important; }
        .min-h-screen { background-color: #f3f4f6; }
    </style>

    <div class="py-6 px-4 max-w-md mx-auto">
        
        
        <div class="bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl p-5 text-white shadow-lg mb-6 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-emerald-100 text-sm font-medium">Setoran Hari Ini</p>
                <div class="flex items-baseline mt-1">
                    <span class="text-4xl font-bold"><?php echo e($totalHariIni ?? 0); ?></span>
                    <span class="ml-2 text-sm opacity-80">Santri</span>
                </div>
                <p class="text-xs mt-2 text-emerald-100 opacity-90"><?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?></p>
            </div>
            <svg class="absolute -bottom-4 -right-4 w-24 h-24 text-white opacity-10" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.963 7.963 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
        </div>

        
        <a href="<?php echo e(route('ustadz.jurnal.create')); ?>" class="block w-full bg-white border-2 border-dashed border-emerald-300 rounded-xl p-4 text-center hover:bg-emerald-50 hover:border-emerald-500 transition mb-6 group shadow-sm">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <span class="font-bold text-emerald-700">Input Setoran Baru</span>
        </a>

        
        <h3 class="font-bold text-gray-700 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Riwayat Hari Ini
        </h3>

        <?php if($hariIni->isEmpty()): ?>
            <div class="text-center py-8 text-gray-400 bg-white rounded-xl border border-gray-200 shadow-sm">
                <p class="text-sm">Belum ada data setoran hari ini.</p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php $__currentLoopData = $hariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-gray-800"><?php echo e($item->santri->full_name ?? 'Santri Dihapus'); ?></span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full <?php echo e($item->jenis_setoran == 'ziyadah' ? 'bg-blue-100 text-blue-600' : 'bg-amber-100 text-amber-600'); ?>">
                                <?php echo e(ucfirst($item->jenis_setoran)); ?>

                            </span>
                        </div>
                        <p class="text-sm text-gray-600">
                            <?php echo e($item->materi); ?> 
                            <?php if($item->start_at): ?> 
                                <span class="text-gray-400 mx-1">|</span> <?php echo e($item->start_at); ?> - <?php echo e($item->end_at); ?>

                            <?php endif; ?>
                        </p>
                        <?php if($item->catatan): ?>
                            <p class="text-xs text-gray-400 mt-1 italic">"<?php echo e($item->catatan); ?>"</p>
                        <?php endif; ?>
                    </div>
                    <div class="text-right">
                        <?php
                            $badgeColor = 'bg-gray-100 text-gray-600';
                            if(in_array($item->predikat, ['A', 'Lancar'])) $badgeColor = 'bg-green-100 text-green-700';
                            elseif($item->predikat == 'B') $badgeColor = 'bg-blue-100 text-blue-700';
                            elseif(in_array($item->predikat, ['C', 'Ulang'])) $badgeColor = 'bg-red-100 text-red-700';
                        ?>
                        <span class="inline-block px-2 py-1 rounded-lg text-xs font-bold <?php echo e($badgeColor); ?>">
                            <?php echo e($item->predikat); ?>

                        </span>
                        <p class="text-[10px] text-gray-400 mt-1"><?php echo e($item->created_at->format('H:i')); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/ustadz/jurnal/index.blade.php ENDPATH**/ ?>