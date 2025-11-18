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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6 flex justify-between items-center">
                <form method="GET" class="flex gap-4 items-center">
                    <select name="bulan" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        <?php for($i=1; $i<=12; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php if($i == $bulan): echo 'selected'; endif; ?>><?php echo e(\Carbon\Carbon::create()->month($i)->format('F')); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="tahun" class="rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        <?php for($y=now()->year; $y>=2023; $y--): ?>
                            <option value="<?php echo e($y); ?>" <?php if($y == $tahun): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
                        <?php endfor; ?>
                    </select>
                    <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['class' => 'bg-teal-600 hover:bg-teal-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'bg-teal-600 hover:bg-teal-700']); ?>Filter <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                </form>
                
                <a href="<?php echo e(route('sekolah.admin.monitoring.siswa')); ?>" class="text-gray-600 hover:text-gray-900">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Ranking Kedisiplinan Siswa (Periode: <?php echo e(\Carbon\Carbon::create()->month($bulan)->format('F')); ?> <?php echo e($tahun); ?>)</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total Hadir</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Tepat Waktu</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Terlambat</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Skor Disiplin</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $laporan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="<?php echo e($index < 3 ? 'bg-teal-50' : ''); ?>">
                                        <td class="px-4 py-3 whitespace-nowrap text-center font-bold text-gray-500">
                                            #<?php echo e($index + 1); ?>

                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?php echo e($row->nama); ?>

                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($row->kelas); ?>

                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-gray-900 font-bold"><?php echo e($row->hadir); ?></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-green-600"><?php echo e($row->tepat_waktu); ?></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-yellow-600"><?php echo e($row->terlambat); ?></td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <?php
                                                $color = $row->skor >= 90 ? 'green' : ($row->skor >= 75 ? 'yellow' : 'red');
                                            ?>
                                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-800">
                                                <?php echo e($row->skor); ?>%
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/monitoring/kinerja-siswa.blade.php ENDPATH**/ ?>