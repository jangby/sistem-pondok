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
    <div class="bg-blue-600 p-6 pb-12 rounded-b-3xl">
        <div class="flex items-center text-white mb-4">
            <a href="<?php echo e(route('petugas-lab.dashboard')); ?>" class="mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-xl font-bold">Jadwal Penggunaan</h1>
        </div>
        <div class="flex justify-between items-center text-blue-100 bg-blue-700/50 rounded-lg p-1">
            <button class="p-2 hover:bg-blue-600 rounded"><i class="fas fa-chevron-left"></i></button>
            <span class="font-medium text-sm"><?php echo e(now()->translatedFormat('l, d F Y')); ?></span>
            <button class="p-2 hover:bg-blue-600 rounded"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>

    <div class="px-4 -mt-8 space-y-4">
        <div class="bg-white p-4 rounded-2xl shadow-md border-l-4 border-blue-500 flex">
            <div class="flex flex-col items-center justify-center pr-4 border-r border-gray-100 mr-4">
                <span class="text-lg font-bold text-gray-800">08:00</span>
                <span class="text-xs text-gray-500">09:30</span>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Kelas 10 RPL A</h3>
                <p class="text-sm text-gray-600">Mapel: Pemrograman Dasar</p>
                <div class="mt-2 flex items-center gap-2">
                    <span class="px-2 py-1 bg-blue-100 text-blue-600 text-[10px] rounded-md font-bold">LAB 1</span>
                    <span class="text-xs text-gray-400"><i class="fas fa-user-tie mr-1"></i> Ust. Ahmad</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-md border-l-4 border-purple-500 flex">
            <div class="flex flex-col items-center justify-center pr-4 border-r border-gray-100 mr-4">
                <span class="text-lg font-bold text-gray-800">10:00</span>
                <span class="text-xs text-gray-500">11:30</span>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Kelas 11 TKJ B</h3>
                <p class="text-sm text-gray-600">Mapel: Jaringan Komputer</p>
                <div class="mt-2 flex items-center gap-2">
                    <span class="px-2 py-1 bg-purple-100 text-purple-600 text-[10px] rounded-md font-bold">LAB 1</span>
                    <span class="text-xs text-gray-400"><i class="fas fa-user-tie mr-1"></i> Ust. Budi</span>
                </div>
            </div>
        </div>
        
        <div class="text-center text-xs text-gray-400 mt-6">
            -- Tidak ada jadwal lagi hari ini --
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/petugas/lab-komputer/jadwal.blade.php ENDPATH**/ ?>