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
<h2 class="font-semibold text-xl text-gray-800 leading-tight"><?php echo e(__('Persetujuan Izin Guru')); ?></h2>
 <?php $__env->endSlot(); ?>
<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 text-gray-900">
    <div class="mb-4">
        <a href="<?php echo e(route('sekolah.admin.persetujuan-izin.index', ['status' => 'pending'])); ?>" class="<?php echo e($status == 'pending' ? 'font-bold text-blue-600' : 'text-gray-500'); ?>">Pending</a> |
        <a href="<?php echo e(route('sekolah.admin.persetujuan-izin.index', ['status' => 'approved'])); ?>" class="<?php echo e($status == 'approved' ? 'font-bold text-green-600' : 'text-gray-500'); ?>">Disetujui</a> |
        <a href="<?php echo e(route('sekolah.admin.persetujuan-izin.index', ['status' => 'rejected'])); ?>" class="<?php echo e($status == 'rejected' ? 'font-bold text-red-600' : 'text-gray-500'); ?>">Ditolak</a>
    </div>
    <?php if(session('success')): ?>
    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm font-medium"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    
    <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
    <tr>
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Guru</th>
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
        <?php if($status == 'pending'): ?>
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
        <?php else: ?>
        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Catatan Admin</th>
        <?php endif; ?>
    </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
    <?php $__empty_1 = true; $__currentLoopData = $izins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $izin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
        <td class="px-4 py-3 text-sm"><?php echo e($izin->guru->name); ?> (<b><?php echo e($izin->tipe_izin); ?></b>)</td>
        <td class="px-4 py-3 text-sm"><?php echo e($izin->tanggal_mulai->format('d/m/Y')); ?> - <?php echo e($izin->tanggal_selesai->format('d/m/Y')); ?></td>
        <td class="px-4 py-3 text-sm"><?php echo e($izin->keterangan_guru); ?></td>
        <td class="px-4 py-3 text-sm">
            <?php if($status == 'pending'): ?>
            <form method="POST" action="<?php echo e(route('sekolah.admin.persetujuan-izin.reject', $izin->id)); ?>" onsubmit="return confirm('Tolak pengajuan ini?');" class="mb-2">
                <?php echo csrf_field(); ?>
                <input type="text" name="keterangan_admin" placeholder="Alasan ditolak (wajib)" class="block w-full text-xs border-gray-300 rounded-md shadow-sm" required>
                <button type="submit" class="mt-1 text-xs text-red-600 font-medium">Tolak</button>
            </form>
            <form method="POST" action="<?php echo e(route('sekolah.admin.persetujuan-izin.approve', $izin->id)); ?>" onsubmit="return confirm('Setujui pengajuan ini?');">
                <?php echo csrf_field(); ?>
                <input type="text" name="keterangan_admin" placeholder="Catatan (opsional)" class="block w-full text-xs border-gray-300 rounded-md shadow-sm">
                <button type="submit" class="mt-1 text-xs text-green-600 font-medium">Setujui</button>
            </form>
            <?php else: ?>
            <?php echo e($izin->keterangan_admin ?? '-'); ?>

            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr><td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500">Tidak ada data.</td></tr>
    <?php endif; ?>
    </tbody>
    </table>
    </div>
    <div class="mt-4"><?php echo e($izins->links()); ?></div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/persetujuan-izin/index.blade.php ENDPATH**/ ?>