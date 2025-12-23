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
            Atur Rincian Biaya: <?php echo e($setting->nama_gelombang); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-4">Tambah Komponen Biaya</h3>
                <form action="<?php echo e(route('adminpondok.ppdb.setting.biaya.store', $setting->id)); ?>" method="POST" class="flex gap-4 items-end">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenjang</label>
                        <select name="jenjang" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="TAKHOSUS">TAKHOSUS</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Nama Biaya</label>
                        <input type="text" name="nama_biaya" placeholder="Contoh: Seragam, Gedung" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nominal (Rp)</label>
                        <input type="number" name="nominal" placeholder="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-500 font-bold">
                        + Tambah
                    </button>
                </form>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php $__currentLoopData = ['SMP', 'SMA', 'TAKHOSUS']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenjang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 bg-gray-50 border-b font-bold text-center text-gray-700">
                        Biaya <?php echo e($jenjang); ?>

                    </div>
                    <div class="p-4">
                        <ul class="space-y-3">
                            <?php 
                                $biayas = $setting->biayas->where('jenjang', $jenjang); 
                                $total = 0;
                            ?>
                            
                            <?php $__empty_1 = true; $__currentLoopData = $biayas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $biaya): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php $total += $biaya->nominal; ?>
                                <li class="flex justify-between items-center text-sm border-b pb-2">
                                    <span><?php echo e($biaya->nama_biaya); ?></span>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono">Rp <?php echo e(number_format($biaya->nominal, 0, ',', '.')); ?></span>
                                        <form action="<?php echo e(route('adminpondok.ppdb.biaya.destroy', $biaya->id)); ?>" method="POST" onsubmit="return confirm('Hapus?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="text-red-500 hover:text-red-700 font-bold">&times;</button>
                                        </form>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="text-gray-400 text-sm text-center italic">Belum ada rincian.</li>
                            <?php endif; ?>
                        </ul>
                        <div class="mt-4 pt-4 border-t border-dashed flex justify-between font-bold text-emerald-700">
                            <span>Total:</span>
                            <span>Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-6">
                <a href="<?php echo e(route('adminpondok.ppdb.setting.index')); ?>" class="text-gray-600 hover:underline">&larr; Kembali ke Daftar Gelombang</a>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/adminpondok/ppdb/setting/biaya.blade.php ENDPATH**/ ?>