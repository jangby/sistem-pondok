<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Monitoring: <?php echo e($mustawa->nama_mustawa ?? $mustawa->nama); ?>

            </h2>
            <a href="<?php echo e(route('pendidikan.admin.monitoring.ujian.index')); ?>" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php $__currentLoopData = $mapels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('pendidikan.admin.monitoring.ujian.detail', ['mustawa' => $mustawa->id, 'mapel' => $mapel->id, 'semester' => $semester, 'tahun_ajaran' => $tahunAjaran])); ?>" 
                           class="border rounded-lg p-4 hover:bg-emerald-50 transition group cursor-pointer">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-gray-800 group-hover:text-emerald-700"><?php echo e($mapel->nama_mapel); ?></h4>
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded"><?php echo e($mapel->kode_mapel); ?></span>
                            </div>
                            
                            
                            <div class="flex gap-1 mb-3 text-[10px] uppercase text-gray-500">
                                <?php if($mapel->uji_tulis): ?> <span class="border px-1 rounded">Tulis</span> <?php endif; ?>
                                <?php if($mapel->uji_lisan): ?> <span class="border px-1 rounded">Lisan</span> <?php endif; ?>
                                <?php if($mapel->uji_praktek): ?> <span class="border px-1 rounded">Praktek</span> <?php endif; ?>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: <?php echo e($mapel->progress); ?>%"></div>
                            </div>
                            <div class="text-right text-xs font-bold text-blue-600"><?php echo e($mapel->progress); ?>% Terisi</div>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/monitoring/ujian/mapel.blade.php ENDPATH**/ ?>