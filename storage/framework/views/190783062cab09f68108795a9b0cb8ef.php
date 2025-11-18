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
            
            <?php echo e(__('Dashboard Admin: ')); ?> <?php echo e($sekolah->nama_sekolah); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Selamat Datang, <?php echo e(Auth::user()->name); ?>!
                    </h3>
                    <p class="mb-6 text-sm text-gray-600">
                        Anda login sebagai Admin Sekolah untuk unit <span class="font-semibold"><?php echo e($sekolah->nama_sekolah); ?></span>. 
                        Anda dapat mengelola data master akademik dari sini.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-blue-700">Total Mata Pelajaran</h4>
                            <p class="text-3xl font-bold text-blue-900 mt-2"><?php echo e($jumlahMapel); ?></p>
                        </div>

                        <div class="bg-green-50 border border-green-200 p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-green-700">Total Guru Bertugas</h4>
                            <p class="text-3xl font-bold text-green-900 mt-2"><?php echo e($jumlahGuru); ?></p>
                        </div>

                        <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-lg shadow">
                            <h4 class="text-sm font-medium text-indigo-700">Tahun Ajaran Aktif</h4>
                            <p class="text-xl font-bold text-indigo-900 mt-2">
                                <?php echo e($tahunAjaranAktif->nama_tahun_ajaran ?? 'Belum Diatur'); ?>

                            </p>
                        </div>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/dashboard.blade.php ENDPATH**/ ?>