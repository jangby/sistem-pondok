<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hide-nav' => true]); ?>
<div class="bg-white p-4 shadow-sm flex items-center sticky top-0 z-30">
        <a href="<?php echo e(route('petugas-lab.dashboard')); ?>" class="mr-4 text-gray-600 hover:text-blue-600">
            <i class="fas fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-lg font-bold text-gray-800">Status Komputer</h1>
    </div>

    <div class="p-4 space-y-4">
        <div class="relative">
            <input type="text" placeholder="Cari nama PC..." class="w-full bg-white border border-gray-200 rounded-xl py-3 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
        </div>

        <?php $__currentLoopData = $computers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $isOnline = $pc->last_seen >= now()->subMinutes(2);
        ?>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
            <div class="flex items-center">
                <div class="relative">
                    <div class="w-10 h-10 rounded-xl <?php echo e($isOnline ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-400'); ?> flex items-center justify-center text-lg">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full <?php echo e($isOnline ? 'bg-green-400 opacity-75' : 'hidden'); ?>"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 <?php echo e($isOnline ? 'bg-green-500' : 'bg-red-500'); ?>"></span>
                    </span>
                </div>
                
                <div class="ml-3">
                    <h3 class="font-bold text-gray-800"><?php echo e($pc->pc_name); ?></h3>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <i class="fas fa-wifi text-[10px]"></i> <?php echo e($pc->ip_address ?? '0.0.0.0'); ?>

                    </p>
                </div>
            </div>

            <div class="text-right">
                <span class="block text-[10px] font-medium <?php echo e($isOnline ? 'text-green-600' : 'text-gray-400'); ?>">
                    <?php echo e($isOnline ? 'ONLINE' : 'OFFLINE'); ?>

                </span>
                <span class="text-[10px] text-gray-400 block">
                    <?php echo e(\Carbon\Carbon::parse($pc->last_seen)->diffForHumans(null, true, true)); ?>

                </span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/petugas/lab-komputer/list.blade.php ENDPATH**/ ?>