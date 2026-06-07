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
            
            
<div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
    <form method="GET" action="<?php echo e(route('pendidikan.admin.monitoring.ujian.index')); ?>" class="flex flex-col md:flex-row md:items-end gap-4">
        
        <div>
            <label class="block text-sm font-medium text-gray-700">Semester</label>
            <select name="semester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="ganjil" <?php echo e(request('semester', 'ganjil') == 'ganjil' ? 'selected' : ''); ?>>Ganjil</option>
                <option value="genap" <?php echo e(request('semester') == 'genap' ? 'selected' : ''); ?>>Genap</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Jadwal Ujian</label>
            <select name="jenis_ujian" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">-- Semua --</option>
                <option value="uts" <?php echo e(request('jenis_ujian') == 'uts' ? 'selected' : ''); ?>>UTS</option>
                <option value="uas" <?php echo e(request('jenis_ujian') == 'uas' ? 'selected' : ''); ?>>UAS</option>
            </select>
        </div>

        <div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Terapkan Filter
            </button>
        </div>
    </form>
    <div class="mt-2 text-xs text-gray-500">
        T.A: <?php echo e($tahunAjaran); ?> | Semester: <?php echo e(ucfirst($semester)); ?>

    </div>
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