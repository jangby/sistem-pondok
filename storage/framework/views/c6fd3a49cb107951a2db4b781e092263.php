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

    <div class="min-h-screen bg-gray-50 pb-32">
        
        
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="<?php echo e(route('sekolah.guru.nilai.kelas', $kegiatan->id)); ?>" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Input Nilai Siswa</h1>
            </div>
        </div>

        
        <div class="px-5 -mt-16 relative z-20">
            
            
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 mb-5">
                <span class="inline-block px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-[10px] font-bold uppercase tracking-wider mb-2">
                    Kelas <?php echo e($kelas->nama_kelas); ?>

                </span>
                <h3 class="text-xl font-bold text-gray-800 leading-tight">
                    
                    <?php echo e($mapel->nama_mapel); ?>

                </h3>
                <p class="text-xs text-gray-500 mt-1 font-medium">
                    
                    Kegiatan: <?php echo e($kegiatan->nama_kegiatan); ?>

                </p>
                <hr class="my-3 border-gray-100">
                <p class="text-xs text-gray-700 font-medium">
                    
                    Total Siswa: <?php echo e($santris->count()); ?>

                </p>
            </div>
            
            <?php if(session('success')): ?>
                <div class="mb-4 p-4 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('sekolah.guru.nilai.store', $kegiatan->id)); ?>">
                <?php echo csrf_field(); ?>
                
                <input type="hidden" name="mapel_id" value="<?php echo e($mapel->id); ?>">
                <input type="hidden" name="kelas_id" value="<?php echo e($kelas->id); ?>">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    
                    <div class="bg-gray-50 p-4 border-b border-gray-100">
                         <p class="text-sm font-bold text-gray-800">Daftar Siswa</p>
                         <p class="text-[10px] text-gray-500">Masukkan nilai 0-100</p>
                    </div>

                    
                    <div class="divide-y divide-gray-100 max-h-[60vh] overflow-y-auto">
                        
                        <?php $__empty_1 = true; $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-4 <?php echo e($index % 2 == 0 ? 'bg-white' : 'bg-gray-50'); ?>">
                                
                                <div class="flex-1 pr-4">
                                    <p class="text-sm font-medium text-gray-800"><?php echo e($santri->full_name); ?></p>
                                    <p class="text-xs text-gray-500">NIS: <?php echo e($santri->nis); ?></p>
                                </div>
                                
                                <div class="w-20 shrink-0">
                                    
                                    <?php
                                        $val = $nilaiExisting[$santri->id] ?? '';
                                    ?>
                                    <input type="number" 
                                           name="nilai[<?php echo e($santri->id); ?>]" 
                                           value="<?php echo e(old('nilai.' . $santri->id, $val)); ?>"
                                           min="0" max="100" step="0.1"
                                           class="block w-full py-2 px-3 border-gray-200 rounded-lg text-sm text-center focus:border-emerald-500 focus:ring-emerald-500"
                                           placeholder="Nilai">
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="p-6 text-center text-gray-500 text-sm">
                                Tidak ada siswa di kelas ini.
                            </div>
                        <?php endif; ?>
                    </div>

                </div>

                
                <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-100 p-4 pb-6 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] z-50">
                    <div class="max-w-3xl mx-auto">
                        <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition active:scale-[0.98] flex items-center justify-center gap-2">
                            <span>Simpan Semua Nilai</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </button>
                    </div>
                </div>

            </form>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/guru/nilai/form.blade.php ENDPATH**/ ?>