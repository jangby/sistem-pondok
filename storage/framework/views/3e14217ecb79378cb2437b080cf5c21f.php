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
            <?php echo e(__('Sirkulasi & Peminjaman')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-medium text-gray-900">Transaksi Aktif</h3>
                <div class="space-x-2">
                    <a href="<?php echo e(route('sekolah.superadmin.perpustakaan.sirkulasi.create')); ?>" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 shadow-sm transition">
                        Pinjam Buku
                    </a>
                    <a href="<?php echo e(route('sekolah.superadmin.perpustakaan.sirkulasi.kembali.index')); ?>" class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 shadow-sm transition">
                        Kembalikan Buku
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Peminjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Buku</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tgl Pinjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Wajib Kembali</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $peminjamans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pinjam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($pinjam->santri->name); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo e($pinjam->santri->kelas->nama_kelas ?? '-'); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900"><?php echo e($pinjam->buku->judul); ?></div>
                                        <div class="text-xs font-mono text-gray-500"><?php echo e($pinjam->buku->kode_buku); ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?php echo e($pinjam->tgl_pinjam->format('d M Y')); ?>

                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <?php
                                            $isLate = \Carbon\Carbon::now()->gt($pinjam->tgl_wajib_kembali);
                                        ?>
                                        <span class="<?php echo e($isLate ? 'text-red-600 font-bold' : 'text-gray-900'); ?>">
                                            <?php echo e($pinjam->tgl_wajib_kembali->format('d M Y')); ?>

                                        </span>
                                        <?php if($isLate): ?>
                                            <span class="text-xs text-red-500 block">(Terlambat)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">Dipinjam</span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada buku yang sedang dipinjam.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <?php echo e($peminjamans->links()); ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/superadmin/perpus/sirkulasi/index.blade.php ENDPATH**/ ?>