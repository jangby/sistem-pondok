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
            <?php echo e(__('Monitoring Live CCTV Dahua')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 shadow-sm sm:rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Informasi Streaming:</strong> Halaman ini menampilkan siaran langsung dari CCTV sekolah. Jika video tidak muncul atau error, pastikan Anda terhubung di jaringan lokal (WiFi sekolah) yang sama dengan CCTV, atau pastikan IP address CCTV/Streaming Server sudah benar.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $cctvs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cctv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <h3 class="font-bold text-gray-700"><?php echo e($cctv['nama']); ?></h3>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full flex items-center <?php echo e($cctv['status'] == 'Online' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                            <span class="w-2 h-2 rounded-full mr-1 <?php echo e($cctv['status'] == 'Online' ? 'bg-green-500 animate-pulse' : 'bg-red-500'); ?>"></span>
                            <?php echo e($cctv['status']); ?>

                        </span>
                    </div>

                    <div class="relative w-full overflow-hidden bg-black" style="padding-top: 56.25%;">
                        <iframe 
                            src="<?php echo e($cctv['stream_url']); ?>" 
                            class="absolute top-0 left-0 w-full h-full" 
                            frameborder="0" 
                            allow="autoplay; fullscreen" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full bg-white p-8 text-center rounded-lg shadow-sm border border-gray-200">
                    <p class="text-gray-500">Belum ada data CCTV yang ditambahkan.</p>
                </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/cctv/index.blade.php ENDPATH**/ ?>