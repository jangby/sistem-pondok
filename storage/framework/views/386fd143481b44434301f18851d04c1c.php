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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e($mapel->nama_mapel); ?> - <?php echo e($mustawa->nama); ?>

            </h2>
            <a href="<?php echo e(route('pendidikan.admin.monitoring.ujian.mapel', ['mustawa' => $mustawa->id])); ?>" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-lg font-bold mb-4">Pilih Jenis Penilaian</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                
                <?php if($mapel->uji_tulis): ?>
                <a href="<?php echo e(route('pendidikan.admin.monitoring.ujian.input', ['mustawa' => $mustawa->id, 'mapel' => $mapel->id, 'jenis' => 'tulis', 'semester' => $semester, 'tahun_ajaran' => $tahunAjaran])); ?>" 
                   class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-xl font-bold text-gray-800">Ujian Tulis</h4>
                            <p class="text-sm text-gray-500 mt-1">Input nilai tulis & kehadiran</p>
                        </div>
                        <div class="text-2xl font-bold text-blue-600"><?php echo e($progress['tulis']); ?>%</div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 text-sm text-blue-600 font-semibold">
                        Kelola Nilai &rarr;
                    </div>
                </a>
                <?php endif; ?>

                
                <?php if($mapel->uji_lisan): ?>
                <a href="<?php echo e(route('pendidikan.admin.monitoring.ujian.input', ['mustawa' => $mustawa->id, 'mapel' => $mapel->id, 'jenis' => 'lisan', 'semester' => $semester, 'tahun_ajaran' => $tahunAjaran])); ?>" 
                   class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-xl font-bold text-gray-800">Ujian Lisan</h4>
                            <p class="text-sm text-gray-500 mt-1">Input nilai lisan (syafahi)</p>
                        </div>
                        <div class="text-2xl font-bold text-green-600"><?php echo e($progress['lisan']); ?>%</div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 text-sm text-green-600 font-semibold">
                        Kelola Nilai &rarr;
                    </div>
                </a>
                <?php endif; ?>

                
                <?php if($mapel->uji_praktek): ?>
                <a href="<?php echo e(route('pendidikan.admin.monitoring.ujian.input', ['mustawa' => $mustawa->id, 'mapel' => $mapel->id, 'jenis' => 'praktek', 'semester' => $semester, 'tahun_ajaran' => $tahunAjaran])); ?>" 
                   class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition border-l-4 border-purple-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-xl font-bold text-gray-800">Ujian Praktek</h4>
                            <p class="text-sm text-gray-500 mt-1">Input nilai praktek (amali)</p>
                        </div>
                        <div class="text-2xl font-bold text-purple-600"><?php echo e($progress['praktek']); ?>%</div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 text-sm text-purple-600 font-semibold">
                        Kelola Nilai &rarr;
                    </div>
                </a>
                <?php endif; ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/monitoring/ujian/detail.blade.php ENDPATH**/ ?>