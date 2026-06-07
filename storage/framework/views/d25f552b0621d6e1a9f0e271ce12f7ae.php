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
                Input Nilai <?php echo e(ucfirst($jenis)); ?> - <?php echo e($mapel->nama_mapel); ?>

            </h2>
            <a href="<?php echo e(route('pendidikan.admin.monitoring.ujian.detail', ['mustawa' => $mustawa->id, 'mapel' => $mapel->id])); ?>" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <?php if(session('success')): ?>
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="<?php echo e(route('pendidikan.admin.monitoring.ujian.update', ['mustawa' => $mustawa->id, 'mapel' => $mapel->id, 'jenis' => $jenis])); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="semester" value="<?php echo e($semester); ?>">
                    <input type="hidden" name="tahun_ajaran" value="<?php echo e($tahunAjaran); ?>">

                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Santri</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nilai <?php echo e(ucfirst($jenis)); ?> (0-100)
                                        </th>
                                        <?php if($jenis == 'tulis'): ?>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nilai Kehadiran (Opsional)
                                        </th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $nilai = $existingNilai[$santri->id] ?? null;
                                        $val = null;
                                        if($nilai) {
                                            if($jenis == 'tulis') $val = $nilai->nilai_tulis;
                                            elseif($jenis == 'lisan') $val = $nilai->nilai_lisan;
                                            elseif($jenis == 'praktek') $val = $nilai->nilai_praktek;
                                            elseif($jenis == 'hafalan') $val = $nilai->nilai_hafalan;
                                        }
                                    ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($index + 1); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?php echo e($santri->full_name); ?>

                                            <div class="text-xs text-gray-400"><?php echo e($santri->nis); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" step="0.01" min="0" max="100" 
                                                   name="nilai[<?php echo e($santri->id); ?>]" 
                                                   value="<?php echo e($val); ?>"
                                                   class="border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm w-32">
                                        </td>
                                        <?php if($jenis == 'tulis'): ?>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" step="0.01" min="0" max="100" 
                                                   name="kehadiran[<?php echo e($santri->id); ?>]" 
                                                   value="<?php echo e($nilai->nilai_kehadiran ?? ''); ?>"
                                                   placeholder="0-100"
                                                   class="border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm w-32 bg-gray-50">
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow">
                            Simpan Data Nilai
                        </button>
                    </div>
                </form>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/monitoring/ujian/input.blade.php ENDPATH**/ ?>