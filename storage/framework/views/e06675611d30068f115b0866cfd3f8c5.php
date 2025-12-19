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
            Ranking Santri - Kelas <?php echo e($mustawa->nama); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Informasi Penilaian</p>
                        <p>Skor Akhir dihitung dari: <strong>40% Akademik + 30% Kedisiplinan + 20% Sikap + 10% Keterampilan</strong></p>
                    </div>

                    
                    <div class="mb-4">
                        <a href="<?php echo e(route('pendidikan.admin.ranking.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            &larr; Kembali Pilih Kelas
                        </a>
                    </div>

                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-3 px-4 border-b text-center w-16">Rank</th>
                                    <th class="py-3 px-4 border-b text-left">Nama Santri</th>
                                    <th class="py-3 px-4 border-b text-center text-sm">Akademik<br><span class="text-xs text-gray-500">(40%)</span></th>
                                    <th class="py-3 px-4 border-b text-center text-sm">Disiplin<br><span class="text-xs text-gray-500">(30%)</span></th>
                                    <th class="py-3 px-4 border-b text-center text-sm">Sikap<br><span class="text-xs text-gray-500">(20%)</span></th>
                                    <th class="py-3 px-4 border-b text-center text-sm">Skill<br><span class="text-xs text-gray-500">(10%)</span></th>
                                    <th class="py-3 px-4 border-b text-center bg-yellow-100 font-bold">SKOR AKHIR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $rankingData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 border-b text-center font-bold text-lg">
                                        <?php if($index == 0): ?> 🥇 1
                                        <?php elseif($index == 1): ?> 🥈 2
                                        <?php elseif($index == 2): ?> 🥉 3
                                        <?php else: ?> <?php echo e($index + 1); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 border-b">
                                        <div class="font-medium text-gray-900"><?php echo e($data['nama']); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo e($data['nis']); ?></div>
                                    </td>
                                    <td class="py-3 px-4 border-b text-center"><?php echo e($data['akademik']); ?></td>
                                    <td class="py-3 px-4 border-b text-center"><?php echo e($data['disiplin']); ?></td>
                                    <td class="py-3 px-4 border-b text-center"><?php echo e($data['sikap']); ?></td>
                                    <td class="py-3 px-4 border-b text-center"><?php echo e($data['skill']); ?></td>
                                    <td class="py-3 px-4 border-b text-center font-bold text-lg bg-yellow-50 text-indigo-700">
                                        <?php echo e($data['total']); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="py-4 px-4 border-b text-center text-gray-500">
                                        Belum ada data nilai untuk kelas ini.
                                    </td>
                                </tr>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/ranking/show.blade.php ENDPATH**/ ?>