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

    <div class="min-h-screen bg-gray-50 pb-28">
        
        
        <div class="bg-emerald-600 pt-8 pb-20 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Perizinan Santri</h1>
                    <p class="text-emerald-100 text-xs mt-1 font-medium">Kelola izin keluar & pulang.</p>
                </div>
                <a href="<?php echo e(route('pengurus.dashboard')); ?>" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            </div>
        </div>

        
        <div class="px-5 -mt-12 relative z-20">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 backdrop-blur-xl">
                <div class="flex justify-between items-center divide-x divide-gray-100">
                    <div class="flex-1 text-center px-1">
                        <span class="block text-2xl font-black text-emerald-600"><?php echo e($sedangDiluar); ?></span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase">Sedang Diluar</span>
                    </div>
                    <div class="flex-1 text-center px-1">
                        <span class="block text-2xl font-black text-red-500"><?php echo e($terlambat); ?></span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase">Terlambat</span>
                    </div>
                    <div class="flex-1 text-center px-1">
                        <span class="block text-2xl font-black text-gray-800"><?php echo e($izinHariIni); ?></span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase">Total Hr Ini</span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="px-5 mt-6">
            <a href="<?php echo e(route('pengurus.perizinan.history')); ?>" class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-gray-100 group active:scale-98 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-50 rounded-full flex items-center justify-center text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">Riwayat Kepulangan</h3>
                        <p class="text-xs text-gray-500">Lihat daftar santri yang sudah kembali</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-purple-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        
        <div class="px-6 mt-8 mb-4 flex justify-between items-end">
            <h3 class="font-bold text-gray-800 text-lg">Sedang Izin</h3>
        </div>

        <div class="px-5 space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $izins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $izin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('pengurus.perizinan.show', $izin->id)); ?>" class="block bg-white p-4 rounded-2xl shadow-sm border border-gray-100 active:scale-[0.98] transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-800 text-[15px]"><?php echo e($izin->santri->full_name); ?></h3>
                            <p class="text-xs text-gray-500 mt-0.5"><?php echo e($izin->santri->kelas->nama_kelas ?? '-'); ?></p>
                        </div>
                        
                        <?php if($izin->tgl_selesai_rencana < now()): ?>
                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase bg-red-50 text-red-600 border border-red-100 animate-pulse">Terlambat</span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase bg-blue-50 text-blue-600 border border-blue-100">Aktif</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mt-3 pt-3 border-t border-gray-50 flex gap-3 items-center">
                        <div class="flex-1">
                            <p class="text-[9px] text-gray-400 uppercase font-bold">Tipe</p>
                            <p class="text-xs font-bold text-gray-700">
                                <?php echo e($izin->jenis_izin == 'keluar_sebentar' ? 'Keluar Sebentar' : ($izin->jenis_izin == 'sakit_pulang' ? 'Sakit (Pulang)' : 'Pulang Bermalam')); ?>

                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] text-gray-400 uppercase font-bold">Wajib Kembali</p>
                            <p class="text-xs font-bold <?php echo e($izin->tgl_selesai_rencana < now() ? 'text-red-600' : 'text-emerald-600'); ?>">
                                <?php echo e($izin->tgl_selesai_rencana->diffForHumans()); ?>

                            </p>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-10 bg-white rounded-3xl border border-dashed border-gray-200">
                    <p class="text-gray-400 text-xs">Semua santri ada di pondok.</p>
                </div>
            <?php endif; ?>
             <div class="mt-4">
                <?php echo e($izins->links('pagination::tailwind')); ?>

            </div>
        </div>

        
        <a href="<?php echo e(route('pengurus.perizinan.scan')); ?>" class="fixed bottom-24 right-6 bg-emerald-600 text-white w-14 h-14 rounded-2xl shadow-xl flex items-center justify-center hover:bg-emerald-700 transition z-40 border-[3px] border-white/20">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </a>
    </div>
    <?php echo $__env->make('layouts.pengurus-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/perizinan/index.blade.php ENDPATH**/ ?>