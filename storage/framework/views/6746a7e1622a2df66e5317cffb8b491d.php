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
     <?php $__env->slot('header', null, []); ?>  <?php $__env->endSlot(); ?>
    <div class="min-h-screen bg-gray-50 pb-20 font-sans">
        
        
        <div class="bg-purple-600 px-6 pt-8 pb-10 rounded-b-[30px] shadow-lg">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('sekolah.petugas.dashboard')); ?>" class="text-white hover:bg-white/20 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-xl font-bold text-white">Buku Tamu</h1>
            </div>
        </div>

        
        <div class="px-6 -mt-6">
            <div class="bg-white p-5 rounded-2xl shadow-lg border border-gray-100">
                <form action="<?php echo e(route('sekolah.petugas.kunjungan.store')); ?>" method="POST" class="flex gap-2">
                    <?php echo csrf_field(); ?>
                    <div class="flex-1">
                        <input type="text" name="nama" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50" placeholder="Nama / ID Pengunjung">
                    </div>
                    <button type="submit" class="bg-purple-600 text-white px-4 rounded-xl font-bold shadow-md hover:bg-purple-700">
                        Masuk
                    </button>
                </form>
            </div>
        </div>

        
        <div class="px-6 mt-6">
            <h3 class="font-bold text-gray-700 mb-3">Hari Ini (<?php echo e(count($kunjungan)); ?>)</h3>
            <div class="space-y-3">
                <?php $__currentLoopData = $kunjungan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tamu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                        <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-xs">
                            <?php echo e(substr($tamu->nama ?? 'G', 0, 1)); ?>

                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm"><?php echo e($tamu->nama ?? 'Guest'); ?></h4>
                            <p class="text-[10px] text-gray-500"><?php echo e($tamu->created_at->format('H:i')); ?> WIB • <?php echo e($tamu->keperluan ?? 'Baca Buku'); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/petugas/kunjungan/index.blade.php ENDPATH**/ ?>