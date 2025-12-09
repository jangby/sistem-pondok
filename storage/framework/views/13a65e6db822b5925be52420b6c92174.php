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
            <?php echo e(__('Monitoring Remedial')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form action="<?php echo e(route('pendidikan.admin.monitoring.remedial.index')); ?>" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            
                            
                            <div>
                                <label class="block font-medium text-sm text-gray-700 mb-1">Kelas (Mustawa)</label>
                                <select name="mustawa_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" onchange="this.form.submit()">
                                    <option value="">- Pilih Kelas -</option>
                                    <?php $__currentLoopData = $mustawas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($m->id); ?>" <?php echo e(request('mustawa_id') == $m->id ? 'selected' : ''); ?>>
                                            <?php echo e($m->nama); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <?php if(request('mustawa_id')): ?>
                                
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-1">Mata Pelajaran</label>
                                    <select name="mapel_diniyah_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                                        <option value="">- Pilih Mapel -</option>
                                        <?php $__currentLoopData = $mapels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($mapel->id); ?>" <?php echo e(request('mapel_diniyah_id') == $mapel->id ? 'selected' : ''); ?>>
                                                <?php echo e($mapel->nama_mapel); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-1">Kategori Ujian</label>
                                    <select name="kategori" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                                        <option value="tulis" <?php echo e(request('kategori') == 'tulis' ? 'selected' : ''); ?>>Tulis</option>
                                        <option value="lisan" <?php echo e(request('kategori') == 'lisan' ? 'selected' : ''); ?>>Lisan</option>
                                        <option value="praktek" <?php echo e(request('kategori') == 'praktek' ? 'selected' : ''); ?>>Praktek</option>
                                        <option value="hafalan" <?php echo e(request('kategori') == 'hafalan' ? 'selected' : ''); ?>>Hafalan</option>
                                    </select>
                                </div>

                                
                                <div>
                                    <label class="block font-medium text-sm text-gray-700 mb-1">Semester</label>
                                    <div class="flex gap-2">
                                        <select name="semester" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                                            <option value="ganjil" <?php echo e(request('semester') == 'ganjil' ? 'selected' : ''); ?>>Ganjil</option>
                                            <option value="genap" <?php echo e(request('semester') == 'genap' ? 'selected' : ''); ?>>Genap</option>
                                        </select>
                                        
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Cari
                                        </button>
                                    </div>
                                </div>

                                <input type="hidden" name="tahun_ajaran" value="<?php echo e(request('tahun_ajaran', date('Y') . '/' . (date('Y') + 1))); ?>">
                            <?php endif; ?>

                        </div>
                    </form>
                </div>
            </div>

            
            <?php if($selectedMapel): ?>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6 border-b pb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">
                                Hasil Pencarian: <?php echo e($selectedMapel->nama_mapel); ?>

                            </h3>
                            <p class="text-sm text-gray-500">
                                KKM: <span class="font-bold text-red-600"><?php echo e($kkm); ?></span> | 
                                Kategori: <?php echo e(ucfirst(request('kategori'))); ?>

                            </p>
                        </div>

                        <?php if(count($remedialList) > 0): ?>
                            <a href="<?php echo e(route('pendidikan.admin.monitoring.remedial.pdf', request()->all())); ?>" target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Download PDF
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Santri</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai <?php echo e(ucfirst(request('kategori'))); ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Defisit</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $remedialList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php 
                                        $col = 'nilai_' . strtolower(request('kategori'));
                                        $nilai = $item->$col;
                                    ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($loop->iteration); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($item->santri->nis); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo e($item->santri->full_name); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-bold"><?php echo e($nilai); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">- <?php echo e($kkm - $nilai); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <p class="text-lg font-medium text-gray-900">Alhamdulillah!</p>
                                                <p class="text-sm">Tidak ada santri yang remedial pada kategori ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/remedial/index.blade.php ENDPATH**/ ?>