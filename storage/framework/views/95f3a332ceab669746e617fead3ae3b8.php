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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Pendaftar PPDB</h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    
                    <div class="mb-4 flex gap-2">
                        <a href="<?php echo e(route('adminpondok.ppdb.pendaftar.index')); ?>" class="px-3 py-1 rounded bg-gray-200 text-sm hover:bg-gray-300">Semua</a>
                        <a href="?status=menunggu_verifikasi" class="px-3 py-1 rounded bg-yellow-100 text-yellow-800 text-sm hover:bg-yellow-200">Perlu Verifikasi</a>
                        <a href="?status=diterima" class="px-3 py-1 rounded bg-emerald-100 text-emerald-800 text-sm hover:bg-emerald-200">Diterima</a>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Daftar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Calon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenjang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Bayar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Daftar</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $pendaftar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono"><?php echo e($item->no_pendaftaran); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold"><?php echo e($item->full_name); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo e($item->jenjang); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($item->status_pembayaran == 'lunas'): ?>
                                        <span class="px-2 text-xs font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                                    <?php else: ?>
                                        <span class="px-2 text-xs font-semibold rounded-full bg-red-100 text-red-800">Belum Lunas</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($item->status_pendaftaran == 'diterima'): ?>
                                        <span class="px-2 text-xs font-semibold rounded-full bg-green-100 text-green-800">Diterima</span>
                                    <?php elseif($item->status_pendaftaran == 'menunggu_verifikasi'): ?>
                                        <span class="px-2 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Verifikasi</span>
                                    <?php else: ?>
                                        <span class="px-2 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="<?php echo e(route('adminpondok.ppdb.pendaftar.show', $item->id)); ?>" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded">Detail & Aksi</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <div class="mt-4">
                        <?php echo e($pendaftar->links()); ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/adminpondok/ppdb/pendaftar/index.blade.php ENDPATH**/ ?>