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
            <?php echo e(__('Proses Kenaikan Kelas (Diniyah)')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
                <form action="<?php echo e(route('pendidikan.admin.kenaikan-kelas.check')); ?>" method="GET" class="flex gap-4 items-end">
                    <div class="flex-grow">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas Asal (Sumber)</label>
                        <select name="source_mustawa_id" class="block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php $__currentLoopData = $mustawas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($m->id); ?>" <?php echo e(request('source_mustawa_id') == $m->id ? 'selected' : ''); ?>>
                                    <?php echo e($m->nama); ?> (Level <?php echo e($m->tingkat); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-md hover:bg-blue-700 transition">
                        Tampilkan Santri
                    </button>
                </form>
            </div>

            
            <?php if(isset($santris) && isset($sourceMustawa)): ?>
                <form action="<?php echo e(route('pendidikan.admin.kenaikan-kelas.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4 border-b pb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Daftar Santri di <?php echo e($sourceMustawa->nama); ?></h3>
                                    <p class="text-sm text-gray-500">Centang santri yang akan dinaikkan.</p>
                                </div>
                                <div class="w-1/3">
                                    <label class="block text-sm font-bold text-emerald-700 mb-1">Naikkan Ke Kelas (Tujuan):</label>
                                    <select name="target_mustawa_id" class="block w-full border-emerald-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                        <option value="">-- Pilih Kelas Tujuan --</option>
                                        <?php $__currentLoopData = $mustawas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                            <option value="<?php echo e($m->id); ?>" <?php echo e($m->tingkat <= $sourceMustawa->tingkat ? 'class=text-gray-400' : 'font-bold'); ?>>
                                                <?php echo e($m->nama); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-center w-10">
                                                <input type="checkbox" onclick="toggleAll(this)" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Santri</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php $__empty_1 = true; $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-3 text-center">
                                                    <input type="checkbox" name="santri_ids[]" value="<?php echo e($santri->id); ?>" class="santri-checkbox rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                                </td>
                                                <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($santri->full_name); ?></td>
                                                <td class="px-4 py-3 text-gray-500"><?php echo e($santri->nis); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada santri di kelas ini.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" onclick="return confirm('Yakin ingin memindahkan santri yang dipilih?')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition">
                                    Proses Kenaikan Kelas
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <script>
                    function toggleAll(source) {
                        checkboxes = document.getElementsByClassName('santri-checkbox');
                        for(var i=0, n=checkboxes.length;i<n;i++) {
                            checkboxes[i].checked = source.checked;
                        }
                    }
                </script>
            <?php endif; ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/anggota-kelas/promotion.blade.php ENDPATH**/ ?>