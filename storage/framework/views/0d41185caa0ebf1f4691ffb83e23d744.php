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
            <?php echo e(__('Manajemen Guru Pengganti')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Jadwal Kosong Hari Ini (<?php echo e(now()->format('d M Y')); ?>)
                    </h3>
                    
                    <?php if(session('success')): ?>
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm font-medium"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mapel / Kelas</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Guru Asli</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tugaskan Pengganti</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $kelasKosong; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <?php echo e($item['jadwal']->jam_mulai); ?> - <?php echo e($item['jadwal']->jam_selesai); ?>

                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <div class="font-bold"><?php echo e($item['jadwal']->mataPelajaran->nama_mapel); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo e($item['jadwal']->kelas->nama_kelas); ?></div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <?php echo e($item['jadwal']->guru->name); ?>

                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                <?php echo e($item['status_guru_asli']); ?>

                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <form method="POST" action="<?php echo e(route('sekolah.admin.guru-pengganti.store')); ?>" class="flex gap-2">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="jadwal_id" value="<?php echo e($item['jadwal']->id); ?>">
                                                <input type="hidden" name="absensi_pelajaran_id" value="<?php echo e($item['absensi_pelajaran_id']); ?>">
                                                
                                                <select name="guru_pengganti_id" class="text-xs border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                                    <option value="">-- Pilih Guru --</option>
                                                    <?php $__currentLoopData = $availableGurus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guru): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($guru->id != $item['jadwal']->guru_user_id): ?>
                                                            <option value="<?php echo e($guru->id); ?>"><?php echo e($guru->name); ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    Simpan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-center text-sm text-gray-500">Tidak ada kelas kosong yang membutuhkan pengganti saat ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/guru-pengganti/index.blade.php ENDPATH**/ ?>