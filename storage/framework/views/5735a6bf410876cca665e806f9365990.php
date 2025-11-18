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

    <div class="min-h-screen bg-gray-50 pb-20 relative">
        
        
        <div class="h-48 bg-emerald-600 rounded-b-[40px]">
            <div class="flex justify-between items-start p-6 text-white">
                <a href="<?php echo e(route('pengurus.santri.index')); ?>" class="bg-white/20 p-2 rounded-xl backdrop-blur-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <a href="<?php echo e(route('pengurus.santri.edit', $santri->id)); ?>" class="bg-white/20 px-4 py-2 rounded-xl backdrop-blur-md text-xs font-bold">
                    Edit Data
                </a>
            </div>
        </div>

        <div class="px-6 mt-6">
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-4 text-white shadow-lg shadow-emerald-200 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10"></div>
                
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest opacity-80">Kartu Santri</p>
                        <h2 class="text-lg font-bold mt-1"><?php echo e($santri->full_name); ?></h2>
                        <p class="text-xs opacity-90 font-mono mt-0.5"><?php echo e($santri->nis); ?></p>
                    </div>
                    
                    <div class="bg-white p-1.5 rounded-lg shadow-sm">
                         <?php if($santri->qrcode_token): ?>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo e($santri->qrcode_token); ?>" class="w-14 h-14">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-6 flex justify-between items-end">
                    <div>
                        <p class="text-[9px] opacity-70 uppercase">RFID UID</p>
                        <p class="font-mono text-sm font-bold tracking-wider"><?php echo e($santri->rfid_uid ?? 'BELUM ADA KARTU'); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] opacity-70">Status</p>
                        <p class="text-xs font-bold flex items-center gap-1 justify-end">
                            <?php if($santri->status == 'active'): ?>
                                <span class="w-2 h-2 rounded-full bg-green-300 animate-pulse"></span> Aktif
                            <?php else: ?>
                                <span class="w-2 h-2 rounded-full bg-red-300"></span> Nonaktif
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="px-6 mt-6 space-y-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Informasi Pribadi
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Jenis Kelamin</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->jenis_kelamin); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Tempat/Tgl Lahir</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->tempat_lahir); ?>, <?php echo e($santri->tanggal_lahir ? $santri->tanggal_lahir->format('d M Y') : '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Golongan Darah</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->golongan_darah ?? '-'); ?></span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Kesehatan
                </h3>
                <p class="text-sm text-gray-600">
                    <?php echo e($santri->riwayat_penyakit ?? 'Tidak ada riwayat penyakit khusus.'); ?>

                </p>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Wali Santri
                </h3>
                <div class="text-sm">
                    <p class="font-bold text-gray-800"><?php echo e($santri->orangTua->name); ?></p>
                    <p class="text-gray-500 mt-1"><?php echo e($santri->orangTua->phone); ?></p>
                    <p class="text-gray-500 text-xs mt-2"><?php echo e($santri->orangTua->address); ?></p>
                    
                    <a href="https://wa.me/<?php echo e(preg_replace('/^0/', '62', $santri->orangTua->phone)); ?>" target="_blank" class="mt-4 block text-center bg-emerald-100 text-emerald-700 py-2 rounded-xl font-bold text-xs hover:bg-emerald-200">
                        Hubungi via WhatsApp
                    </a>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/santri/show.blade.php ENDPATH**/ ?>