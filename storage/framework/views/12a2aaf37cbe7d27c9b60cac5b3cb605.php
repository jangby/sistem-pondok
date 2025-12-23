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
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Manajemen Pondok</h2>
                    <p class="text-sm text-gray-500">Kelola data pesantren dan akses admin.</p>
                </div>
                <div class="flex gap-3">
                    <a href="<?php echo e(route('superadmin.admins.create')); ?>" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        + Tambah Admin
                    </a>
                    <a href="<?php echo e(route('superadmin.pondoks.create')); ?>" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        + Pondok Baru
                    </a>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php $__empty_1 = true; $__currentLoopData = $pondoks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pondok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="group relative bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition-all duration-200 hover:border-emerald-200">
                                
                                
                                <div class="flex justify-between items-start mb-4">
                                    <div class="bg-emerald-50 rounded-lg p-3 text-emerald-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold
                                        <?php if($pondok->status == 'active'): ?> bg-emerald-100 text-emerald-800
                                        <?php elseif($pondok->status == 'trial'): ?> bg-yellow-100 text-yellow-800
                                        <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                                        <?php echo e(ucfirst($pondok->status)); ?>

                                    </span>
                                </div>

                                
                                <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-emerald-600 transition-colors">
                                    <?php echo e($pondok->name); ?>

                                </h3>
                                <p class="text-sm text-gray-500 line-clamp-2 mb-4 h-10">
                                    <?php echo e($pondok->address ?? 'Alamat belum diisi'); ?>

                                </p>

                                
                                <div class="border-t border-gray-100 pt-4 mb-4 space-y-2">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        <?php echo e($pondok->phone ?? '-'); ?>

                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <?php echo e($pondok->staff->count()); ?> Admin Terdaftar
                                    </div>
                                </div>

                                
                                <div class="flex gap-2">
                                    <a href="<?php echo e(route('superadmin.pondoks.edit', $pondok->id)); ?>" class="flex-1 text-center px-3 py-2 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-lg hover:bg-emerald-100 transition">
                                        Kelola
                                    </a>
                                    <form id="delete-form-<?php echo e($pondok->id); ?>" action="<?php echo e(route('superadmin.pondoks.destroy', $pondok->id)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="button" 
                                                onclick="confirmDelete('<?php echo e($pondok->id); ?>', '<?php echo e($pondok->name); ?>')" 
                                                class="px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition"
                                                title="Hapus Pondok">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-full text-center py-12">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Belum ada data pondok</h3>
                                <p class="mt-1 text-gray-500">Mulai dengan menambahkan pondok pesantren baru ke dalam sistem.</p>
                                <div class="mt-6">
                                    <a href="<?php echo e(route('superadmin.pondoks.create')); ?>" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition">
                                        + Tambah Pondok Sekarang
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function confirmDelete(pondokId, pondokName) {
            Swal.fire({
                title: 'Hapus Pondok?',
                text: `Anda akan menghapus '${pondokName}' beserta seluruh data santri, tagihan, dan admin terkait. Tindakan ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626', // Red-600
                cancelButtonColor: '#6b7280', // Gray-500
                confirmButtonText: 'Ya, Hapus Permanen',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + pondokId).submit();
                }
            })
        }
    </script>
    <?php $__env->stopPush(); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/superadmin/pondoks/index.blade.php ENDPATH**/ ?>