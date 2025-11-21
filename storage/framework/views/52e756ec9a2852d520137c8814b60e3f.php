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
            
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            
            <div class="relative z-10 flex items-center gap-4">
                <a href="<?php echo e(route('pengurus.absensi.asrama')); ?>" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition active:scale-95">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Pengaturan Asrama</h1>
                    <p class="text-emerald-100 text-xs font-medium">Jam Absen & Hari Libur</p>
                </div>
            </div>
        </div>

        
        <div class="px-5 -mt-16 relative z-20 space-y-6">
            
            
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 pb-2 border-b border-gray-50">
                    <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    Waktu Absensi
                </h3>

                <form action="<?php echo e(route('pengurus.absensi.asrama.settings.store')); ?>" method="POST" class="space-y-5">
                    <?php echo csrf_field(); ?>
                    
                    
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase block mb-2 tracking-wider">Sesi Pagi</label>
                        <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                            <input type="time" name="pagi_mulai" value="<?php echo e($pagi->jam_mulai ?? '05:00'); ?>" class="bg-white border-0 rounded-xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-emerald-500 w-full text-center shadow-sm">
                            <span class="text-gray-400 font-bold">-</span>
                            <input type="time" name="pagi_selesai" value="<?php echo e($pagi->jam_selesai ?? '07:00'); ?>" class="bg-white border-0 rounded-xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-emerald-500 w-full text-center shadow-sm">
                        </div>
                    </div>

                    
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase block mb-2 tracking-wider">Sesi Malam</label>
                        <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                            <input type="time" name="malam_mulai" value="<?php echo e($malam->jam_mulai ?? '18:00'); ?>" class="bg-white border-0 rounded-xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-emerald-500 w-full text-center shadow-sm">
                            <span class="text-gray-400 font-bold">-</span>
                            <input type="time" name="malam_selesai" value="<?php echo e($malam->jam_selesai ?? '21:00'); ?>" class="bg-white border-0 rounded-xl text-sm font-bold text-gray-800 focus:ring-2 focus:ring-emerald-500 w-full text-center shadow-sm">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white py-3.5 rounded-2xl font-bold text-sm shadow-lg shadow-emerald-200 active:scale-95 transition hover:bg-emerald-700">
                        Simpan Perubahan Jam
                    </button>
                </form>
            </div>

            
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 pb-2 border-b border-gray-50">
                    <div class="p-2 bg-red-50 rounded-lg text-red-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    Jadwal Libur
                </h3>

                
                <form action="<?php echo e(route('pengurus.absensi.asrama.libur.store')); ?>" method="POST" class="mb-6">
                    <?php echo csrf_field(); ?>
                    <div class="flex flex-col gap-3">
                        <div class="flex gap-2">
                            <input type="date" name="tanggal" class="w-1/3 rounded-xl border-gray-200 text-xs focus:ring-red-500 focus:border-red-500" required>
                            <input type="text" name="keterangan" placeholder="Keterangan (Cth: Maulid)" class="w-2/3 rounded-xl border-gray-200 text-xs focus:ring-red-500 focus:border-red-500" required>
                        </div>
                        <button type="submit" class="w-full bg-red-50 text-red-600 py-2.5 rounded-xl font-bold text-xs border border-red-100 hover:bg-red-100 transition active:scale-95">
                            + Tambah Hari Libur
                        </button>
                    </div>
                </form>

                
                <div class="space-y-2 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                    <?php $__empty_1 = true; $__currentLoopData = $libur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-2xl border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="text-center bg-white px-2 py-1 rounded-lg border border-gray-200 shadow-sm">
                                    <span class="block text-[10px] text-gray-400 uppercase font-bold"><?php echo e($l->tanggal->format('M')); ?></span>
                                    <span class="block text-lg font-bold text-gray-800 leading-none"><?php echo e($l->tanggal->format('d')); ?></span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-medium"><?php echo e($l->tanggal->format('Y')); ?></p>
                                    <p class="font-bold text-gray-700 text-sm truncate max-w-[120px]"><?php echo e($l->keterangan); ?></p>
                                </div>
                            </div>
                            
                            <form action="<?php echo e(route('pengurus.absensi.asrama.libur.delete', $l->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="bg-white p-2 rounded-xl text-red-400 hover:text-red-600 border border-gray-200 shadow-sm active:scale-90 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-4 text-gray-400 text-xs italic">
                            Belum ada jadwal libur.
                        </div>
                    <?php endif; ?>
                </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/absensi/asrama/settings.blade.php ENDPATH**/ ?>