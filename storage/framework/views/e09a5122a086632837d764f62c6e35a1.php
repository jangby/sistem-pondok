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
            <?php echo e(__('Kelola Nilai: ')); ?><?php echo e($kegiatan->nama_kegiatan); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        Daftar Mata Pelajaran (Kelas: <?php echo e($kelas->nama_kelas); ?>)
                    </h3>

                    <div class="mb-4">
                        
                        <a href="<?php echo e(route('sekolah.admin.kegiatan-akademik.kelola.kelas', $kegiatan->id)); ?>" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali ke Daftar Kelas</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mapel</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress Input</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $mapelList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($mapel->nama_mapel); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: <?php echo e($mapel->completion); ?>%"></div>
                                            </div>
                                            <span class="text-xs text-gray-600 mt-1 block"><?php echo e($mapel->completion); ?>% Selesai</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-2">
                                            <a href="<?php echo e(route('sekolah.admin.kegiatan-akademik.kelola.nilai', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id])); ?>" class="text-green-600 hover:text-green-900">
                                                Lihat Nilai
                                            </a>
                                            <a href="<?php echo e(route('sekolah.admin.kegiatan-akademik.cetak.nilai', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id])); ?>" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                Cetak PDF
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada mata pelajaran terdaftar.</td></tr>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/laporan-nilai/mapel.blade.php ENDPATH**/ ?>