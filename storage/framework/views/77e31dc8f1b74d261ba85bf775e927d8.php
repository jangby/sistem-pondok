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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Monitoring Input Nilai Ujian')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <div class="mb-6 bg-white p-4 rounded-lg shadow-sm flex items-center justify-between">
                <span class="font-bold text-gray-700">T.A: <?php echo e($tahunAjaran); ?> | Semester: <?php echo e(ucfirst($semester)); ?></span>
                <span class="text-xs text-gray-500">*Gunakan parameter URL ?semester=genap&tahun_ajaran=2024/2025 untuk ganti</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php $__currentLoopData = $mustawas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mustawa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('pendidikan.admin.monitoring.ujian.mapel', ['mustawa' => $mustawa->id, 'semester' => $semester, 'tahun_ajaran' => $tahunAjaran])); ?>" 
                   class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800"><?php echo e($mustawa->nama_mustawa ?? $mustawa->nama); ?></h3>
                            <span class="text-xs font-semibold px-2 py-1 bg-gray-100 rounded text-gray-600">
                                Tingkat <?php echo e($mustawa->tingkat); ?>

                            </span>
                        </div>
                        
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                            <div class="bg-emerald-600 h-2.5 rounded-full" style="width: <?php echo e($mustawa->progress); ?>%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Progress</span>
                            <span class="font-bold"><?php echo e($mustawa->progress); ?>%</span>
                        </div>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/monitoring/ujian/index.blade.php ENDPATH**/ ?>