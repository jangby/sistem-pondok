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
                <a href="<?php echo e(route('sekolah.guru.nilai.index')); ?>" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white"><?php echo e($kegiatan->nama_kegiatan); ?></h1>
            </div>
        </div>

        
        <div class="px-5 -mt-16 relative z-20 space-y-4">
            
            <div class="mb-3 px-1">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Pilih Kelas & Mapel Anda</p>
            </div>

            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $listKelasMapel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Hitung persentase untuk visual
                        $persen = $item->persen;
                        $progressColor = 'indigo';
                        if ($persen == 100) {
                            $progressColor = 'emerald';
                        } elseif ($persen > 0) {
                            $progressColor = 'blue';
                        }
                    ?>

                    
                    <a href="<?php echo e(route('sekolah.guru.nilai.form', ['kegiatan' => $kegiatan->id, 'kelasId' => $item->kelas_id, 'mapelId' => $item->mapel_id])); ?>" 
                       class="block bg-white p-4 rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative group active:scale-[0.98] transition-transform hover:shadow-md">
                        
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1 pr-4">
                                <span class="inline-block px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-[10px] font-bold uppercase tracking-wider mb-1.5">
                                    Kelas <?php echo e($item->nama_kelas); ?>

                                </span>
                                <h4 class="text-base font-bold text-gray-800"><?php echo e($item->nama_mapel); ?></h4>
                            </div>
                            
                            
                            <div class="text-right shrink-0">
                                <span class="text-[10px] font-bold px-2 py-1 rounded-full 
                                    <?php echo e($persen == 100 ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600'); ?>">
                                    <?php echo e($item->sudah_dinilai); ?> / <?php echo e($item->total_santri); ?> Siswa
                                </span>
                            </div>
                        </div>

                        
                        <div class="w-full bg-gray-100 rounded-full h-2.5 mt-1 relative">
                            <div class="bg-<?php echo e($progressColor); ?>-600 h-2.5 rounded-full transition-all duration-500" style="width: <?php echo e($persen); ?>%"></div>
                            <p class="absolute right-0 top-1/2 transform -translate-y-1/2 pr-2 text-[10px] font-bold text-white z-10"><?php echo e($persen); ?>%</p>
                            <p class="text-[10px] text-right mt-1 text-gray-500">Selesai</p>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-10 bg-white rounded-2xl border border-dashed border-gray-200">
                        <p class="text-gray-500 text-sm font-medium">Anda tidak memiliki tugas mengajar di kegiatan ini.</p>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/guru/nilai/list-kelas.blade.php ENDPATH**/ ?>