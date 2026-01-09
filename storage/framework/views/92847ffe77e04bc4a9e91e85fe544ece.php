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
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('adminpondok.ppdb.distribusi.show', ['kategori' => $kategori, 'gelombang_id' => $setting->id])); ?>" 
                   class="bg-white p-2 rounded-full shadow-sm hover:bg-gray-50 border border-gray-200 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Rincian: <?php echo e($namaBiaya); ?></h2>
                    <p class="text-sm text-gray-500">Kategori: <?php echo e(ucfirst($kategori)); ?> | Tahun Ajaran <?php echo e($setting->tahun_ajaran); ?></p>
                </div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total Terkumpul untuk Item Ini</p>
                    <h3 class="text-3xl font-bold text-emerald-600">Rp <?php echo e(number_format($totalTerkumpul, 0, ',', '.')); ?></h3>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">
                        <?php echo e(count($dataSantri)); ?> Santri Berkontribusi
                    </span>
                </div>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-center w-10">No</th>
                                <th class="px-6 py-3">Nama Santri</th>
                                <th class="px-6 py-3 text-center">Jenjang</th>
                                <th class="px-6 py-3 text-center">Status Lunas</th>
                                <th class="px-6 py-3 text-right">Harga Item (Full)</th>
                                <th class="px-6 py-3 text-right bg-emerald-50 text-emerald-700">Dana Masuk (Real)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php $__empty_1 = true; $__currentLoopData = $dataSantri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-center text-gray-400"><?php echo e($index + 1); ?></td>
                                <td class="px-6 py-4 font-medium text-gray-800"><?php echo e($row['nama']); ?></td>
                                <td class="px-6 py-4 text-center text-xs">
                                    <span class="px-2 py-1 rounded bg-gray-100 border"><?php echo e($row['jenjang']); ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if($row['status_bayar'] == 'lunas'): ?>
                                        <span class="text-xs text-green-600 font-bold">LUNAS</span>
                                    <?php else: ?>
                                        <span class="text-xs text-orange-500 font-bold">CICILAN</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-500">
                                    Rp <?php echo e(number_format($row['nilai_item_penuh'], 0, ',', '.')); ?>

                                </td>
                                <td class="px-6 py-4 text-right font-bold text-emerald-600 bg-emerald-50/50">
                                    Rp <?php echo e(number_format($row['kontribusi_masuk'], 0, ',', '.')); ?>

                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400 italic">
                                    Belum ada dana masuk untuk item ini.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/adminpondok/ppdb/distribusi/item_detail.blade.php ENDPATH**/ ?>