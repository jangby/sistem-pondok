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

    <div class="min-h-screen bg-gray-50 pb-20">
        
        
        <div class="bg-emerald-600 pt-6 pb-24 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-400 opacity-20 rounded-full -ml-10 -mb-10 blur-2xl"></div>
            
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('pengurus.jadwal.index')); ?>" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">Kios Absensi</h1>
                        <p class="text-emerald-100 text-xs font-medium">Jadwal Jaga: <?php echo e($hariIni); ?></p>
                    </div>
                </div>

                <a href="<?php echo e(route('pengurus.rekap-gerbang.index')); ?>" class="bg-white text-emerald-700 text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm hover:bg-emerald-50 hover:scale-105 active:scale-95 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="hidden sm:inline">Rekap Absensi</span>
                    <span class="sm:hidden">Rekap</span>
                </a>
            </div>
        </div>

        
        <div class="px-5 -mt-16 relative z-20 space-y-6 max-w-5xl mx-auto">
            
            
            <?php if(session('success')): ?>
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-2xl shadow-sm flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-full"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                    <p class="text-sm font-medium"><?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-2xl shadow-sm flex items-center gap-3">
                    <div class="bg-red-100 p-2 rounded-full"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></div>
                    <p class="text-sm font-medium"><?php echo e(session('error')); ?></p>
                </div>
            <?php endif; ?>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $jadwalHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $sudahAbsenPagi = isset($absensiHariIni[$jadwal->santri_id]) && $absensiHariIni[$jadwal->santri_id]->absen_pagi != null;
                        $sudahAbsenSore = isset($absensiHariIni[$jadwal->santri_id]) && $absensiHariIni[$jadwal->santri_id]->absen_sore != null;
                    ?>
                    
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden flex flex-col justify-between transform transition hover:-translate-y-1 hover:shadow-xl">
                        
                        <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="bg-emerald-100 text-emerald-600 p-3 rounded-2xl">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div class="overflow-hidden">
                                    <h3 class="font-bold text-gray-800 text-lg truncate"><?php echo e($jadwal->santri->full_name); ?></h3>
                                    <p class="text-xs text-gray-500">Petugas Hari <?php echo e($hariIni); ?></p>
                                </div>
                            </div>
                            
                            <div class="flex gap-2 text-xs font-bold">
                                <div class="flex-1 text-center py-1.5 rounded-lg border <?php echo e($sudahAbsenPagi ? 'bg-green-50 border-green-200 text-green-600' : 'bg-red-50 border-red-200 text-red-500'); ?>">
                                    Pagi: <?php echo e($sudahAbsenPagi ? '✅ Selesai' : '❌ Belum'); ?>

                                </div>
                                <div class="flex-1 text-center py-1.5 rounded-lg border <?php echo e($sudahAbsenSore ? 'bg-green-50 border-green-200 text-green-600' : 'bg-red-50 border-red-200 text-red-500'); ?>">
                                    Sore: <?php echo e($sudahAbsenSore ? '✅ Selesai' : '❌ Belum'); ?>

                                </div>
                            </div>
                        </div>
                        
                        <div class="p-5">
                            <form action="<?php echo e(route('pengurus.kios.store')); ?>" method="POST" class="flex flex-col gap-4">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="santri_id" value="<?php echo e($jadwal->santri_id); ?>">
                                
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase block mb-1 text-center tracking-widest">Masukkan PIN Anda</label>
                                    <input type="text" name="pin" inputmode="numeric" maxlength="6" pattern="\d{6}" required placeholder="••••••" class="bg-gray-50 border border-gray-200 text-gray-800 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 block w-full py-4 text-center text-3xl tracking-[0.5em] font-bold shadow-inner" autocomplete="off" style="-webkit-text-security: disc;">
                                </div>
                                
                                <div class="flex gap-3 mt-2">
                                    <button type="submit" name="tipe_absen" value="pagi" class="w-full py-3 px-2 rounded-xl font-bold text-sm text-white transition active:scale-95 shadow-md <?php echo e($sudahAbsenPagi ? 'bg-gray-300 shadow-none cursor-not-allowed' : 'bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 shadow-blue-200'); ?>" <?php echo e($sudahAbsenPagi ? 'disabled' : ''); ?>>
                                        ☀️ Absen Pagi
                                    </button>
                                    
                                    <button type="submit" name="tipe_absen" value="sore" class="w-full py-3 px-2 rounded-xl font-bold text-sm text-white transition active:scale-95 shadow-md <?php echo e($sudahAbsenSore ? 'bg-gray-300 shadow-none cursor-not-allowed' : 'bg-gradient-to-r from-orange-400 to-orange-500 hover:from-orange-500 hover:to-orange-600 shadow-orange-200'); ?>" <?php echo e($sudahAbsenSore ? 'disabled' : ''); ?>>
                                        🌇 Absen Sore
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full flex flex-col items-center justify-center p-12 bg-white rounded-3xl shadow-sm border border-gray-100 border-dashed">
                        <div class="bg-gray-50 p-4 rounded-full text-gray-300 mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-700">Tidak Ada Jadwal Penjaga</h3>
                        <p class="text-sm text-gray-400 text-center max-w-sm mt-1">Tidak ada santri yang dijadwalkan untuk menjaga gerbang pada hari ini (<?php echo e($hariIni); ?>).</p>
                        <a href="<?php echo e(route('pengurus.jadwal.index')); ?>" class="mt-6 bg-emerald-50 text-emerald-600 font-bold px-6 py-2.5 rounded-xl hover:bg-emerald-100 transition">
                            + Tambah Jadwal Baru
                        </a>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/absensi/gerbang/kios.blade.php ENDPATH**/ ?>