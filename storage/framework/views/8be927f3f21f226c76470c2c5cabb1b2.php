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

    <div class="min-h-screen bg-gray-50 pb-24">
        
        
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="<?php echo e(route('sekolah.guru.dashboard')); ?>" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Input Nilai (Kegiatan)</h1>
            </div>
        </div>

        
        <div class="px-5 -mt-16 relative z-20 space-y-4">
            
            <div class="mb-3 px-1">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Pilih Kegiatan Akademik</p>
            </div>

            <?php $__empty_1 = true; $__currentLoopData = $kegiatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                
                
                <a href="<?php echo e(route('sekolah.guru.nilai.kelas', $kegiatan->id)); ?>" 
                   class="block bg-white p-5 rounded-2xl shadow-md border border-gray-100 relative group hover:border-emerald-300 transition-all active:scale-[0.98] hover:shadow-lg">
                    
                    <div class="flex justify-between items-start">
                        <div class="pr-4">
                            
                            <span class="inline-block px-2.5 py-1 text-[10px] uppercase font-bold 
                                <?php echo e($kegiatan->tipe == 'ujian' ? 'text-red-600 bg-red-50' : 'text-blue-600 bg-blue-50'); ?>

                                rounded-lg mb-1">
                                <?php echo e($kegiatan->tipe); ?>

                            </span>
                            
                            
                            <h4 class="text-lg font-bold text-gray-800"><?php echo e($kegiatan->nama_kegiatan); ?></h4>
                            
                            
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <?php echo e($kegiatan->tanggal_mulai->format('d M Y')); ?> 
                                <?php if($kegiatan->tanggal_mulai != $kegiatan->tanggal_selesai): ?>
                                    - <?php echo e($kegiatan->tanggal_selesai->format('d M Y')); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                        
                        
                        <div class="text-gray-300 group-hover:text-emerald-500 transition-colors shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-10 bg-white rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-500 text-sm">Belum ada kegiatan akademik yang tersedia untuk pengisian nilai.</p>
                </div>
            <?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/guru/nilai/index.blade.php ENDPATH**/ ?>