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

    <div class="min-h-screen bg-gray-50 pb-28 relative">
        
        
        <div class="h-64 bg-emerald-600 rounded-b-[40px] relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-40 h-40 bg-white opacity-10 rounded-full -ml-10 -mt-10 blur-2xl"></div>
            
            
            <div class="flex justify-between items-center p-6 text-white relative z-10">
                <a href="<?php echo e(route('pengurus.perizinan.index')); ?>" class="bg-white/20 p-2 rounded-xl backdrop-blur-md hover:bg-white/30 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="font-bold text-lg">Detail Izin</h1>
                <div class="w-10"></div> 
            </div>
        </div>

        
        <div class="px-6 -mt-36 relative z-20">
            <div class="bg-white rounded-3xl shadow-xl p-6 text-center border border-gray-100">
                
                <div class="w-20 h-20 bg-emerald-50 rounded-full mx-auto mb-3 flex items-center justify-center text-2xl font-bold text-emerald-600 ring-4 ring-white shadow-md">
                    <?php echo e(substr($izin->santri->full_name, 0, 1)); ?>

                </div>
                
                <h2 class="text-lg font-bold text-gray-800 leading-tight"><?php echo e($izin->santri->full_name); ?></h2>
                <p class="text-gray-500 text-xs mt-1"><?php echo e($izin->santri->kelas->nama_kelas ?? '-'); ?> • <?php echo e($izin->santri->nis); ?></p>

                <div class="mt-4 flex justify-center gap-2">
                    
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 uppercase tracking-wider">
                        <?php echo e($izin->jenis_izin == 'keluar_sebentar' ? 'Keluar Sebentar' : ($izin->jenis_izin == 'sakit_pulang' ? 'Sakit (Pulang)' : 'Pulang Bermalam')); ?>

                    </span>

                    
                    <?php if($izin->status == 'kembali'): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 uppercase tracking-wider">Sudah Kembali</span>
                    <?php elseif($izin->tgl_selesai_rencana < now()): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 uppercase tracking-wider animate-pulse">Terlambat</span>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 uppercase tracking-wider">Aktif (Diluar)</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="px-6 mt-6 space-y-4">
            
            
            <div class="grid grid-cols-2 gap-4">
                
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Waktu Keluar</p>
                    <p class="font-bold text-gray-800 text-sm"><?php echo e($izin->tgl_mulai->format('d M, H:i')); ?></p>
                    <p class="text-[10px] text-gray-500"><?php echo e($izin->tgl_mulai->diffForHumans()); ?></p>
                </div>

                
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <?php if($izin->status != 'kembali' && $izin->tgl_selesai_rencana < now()): ?>
                        <div class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full animate-ping m-2"></div>
                    <?php endif; ?>
                    
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Wajib Kembali</p>
                    <p class="font-bold text-gray-800 text-sm"><?php echo e($izin->tgl_selesai_rencana->format('d M, H:i')); ?></p>
                    
                    <?php if($izin->status == 'kembali'): ?>
                        <p class="text-[10px] text-green-600 font-bold">Selesai</p>
                    <?php elseif($izin->tgl_selesai_rencana < now()): ?>
                        <p class="text-[10px] text-red-600 font-bold">Lewat <?php echo e($izin->tgl_selesai_rencana->diffForHumans(null, true)); ?></p>
                    <?php else: ?>
                        <p class="text-[10px] text-blue-600 font-bold">Sisa <?php echo e($izin->tgl_selesai_rencana->diffForHumans(null, true)); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Alasan Izin</h3>
                <p class="text-gray-800 text-sm leading-relaxed font-medium">
                    "<?php echo e($izin->alasan); ?>"
                </p>
                
                <div class="mt-4 pt-3 border-t border-gray-50 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[9px] font-bold text-gray-500">
                        <?php echo e(substr($izin->penyetuju->name ?? 'A', 0, 1)); ?>

                    </div>
                    <p class="text-xs text-gray-400">Disetujui oleh: <span class="text-gray-600"><?php echo e($izin->penyetuju->name ?? 'Admin'); ?></span></p>
                </div>
            </div>

            
            <?php if($izin->status == 'kembali'): ?>
                <div class="bg-green-50 p-4 rounded-2xl border border-green-100 flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-green-600 font-bold uppercase">Telah Kembali</p>
                        <p class="text-sm font-bold text-green-800"><?php echo e($izin->tgl_kembali_realisasi->format('d M Y, H:i')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        
        <?php if($izin->status == 'disetujui' || $izin->status == 'terlambat'): ?>
            <div class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-md border-t border-gray-200 p-4 pb-8 z-30">
                <div class="max-w-7xl mx-auto">
                    <a href="<?php echo e(route('pengurus.perizinan.edit', $izin->id)); ?>" class="flex items-center justify-center w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-emerald-200 active:scale-95 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Konfirmasi Kepulangan
                    </a>
                </div>
            </div>
        <?php endif; ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/perizinan/show.blade.php ENDPATH**/ ?>