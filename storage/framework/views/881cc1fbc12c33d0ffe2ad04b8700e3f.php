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
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Jurnal Hafalan')); ?>

            </h2>
            
            <a href="<?php echo e(route('ustadz.dashboard')); ?>" class="text-gray-500 hover:text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <style>
        nav.bg-white.border-b { display: none !important; }
        .min-h-screen { background-color: #f3f4f6; }
    </style>

    <div class="py-6 px-4 max-w-md mx-auto">
        
        
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-lg mb-6 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-emerald-100 text-sm font-medium opacity-90">Setoran Hari Ini</p>
                <div class="flex items-baseline mt-2">
                    <span class="text-5xl font-bold tracking-tight"><?php echo e($totalHariIni ?? 0); ?></span>
                    <span class="ml-2 text-base font-medium text-emerald-100">Santri</span>
                </div>
                <div class="mt-4 pt-4 border-t border-white/20 flex items-center text-xs text-emerald-100">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <?php echo e(\Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y')); ?>

                </div>
            </div>
            
            <svg class="absolute -bottom-6 -right-6 w-32 h-32 text-white opacity-10 rotate-12" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.963 7.963 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
        </div>

        
        <a href="<?php echo e(route('ustadz.jurnal.create')); ?>" class="group relative block w-full bg-white rounded-xl p-4 shadow-sm border border-emerald-100 hover:border-emerald-300 hover:shadow-md transition-all mb-8">
            <div class="flex items-center justify-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="font-bold text-gray-700 group-hover:text-emerald-700">Catat Setoran Baru</span>
            </div>
        </a>

        
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 text-lg">Riwayat Hari Ini</h3>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md"><?php echo e($totalHariIni); ?> Data</span>
        </div>

        <?php if($hariIni->isEmpty()): ?>
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <p class="text-gray-500 text-sm">Belum ada setoran hari ini.</p>
                <p class="text-gray-400 text-xs mt-1">Ketuk tombol di atas untuk mulai mencatat.</p>
            </div>
        <?php else: ?>
            <div class="space-y-3 pb-20"> 
                <?php $__currentLoopData = $hariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:border-emerald-200 transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800 text-sm mb-1 line-clamp-1">
                                <?php echo e($item->santri->nama_lengkap ?? $item->santri->full_name ?? 'Santri Dihapus'); ?>

                            </h4>
                            <div class="flex flex-wrap gap-2 text-xs mb-2">
                                <span class="px-2 py-0.5 rounded-md <?php echo e($item->kategori_hafalan == 'quran' ? 'bg-emerald-50 text-emerald-700' : 'bg-purple-50 text-purple-700'); ?>">
                                    <?php echo e(ucfirst($item->kategori_hafalan)); ?>

                                </span>
                                <span class="px-2 py-0.5 rounded-md <?php echo e($item->jenis_setoran == 'ziyadah' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700'); ?>">
                                    <?php echo e(ucfirst($item->jenis_setoran)); ?>

                                </span>
                            </div>
                            <div class="text-sm text-gray-600 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                <span class="font-medium"><?php echo e($item->materi); ?></span>
                                <?php if($item->start_at): ?>
                                    <span class="text-gray-300 mx-1">•</span>
                                    <span><?php echo e($item->rentang); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-end">
                            <?php
                                $badgeClass = match($item->predikat) {
                                    'A', 'Mumtaz' => 'bg-green-100 text-green-700 border-green-200',
                                    'B', 'Jayyid' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    default => 'bg-red-100 text-red-700 border-red-200',
                                };
                            ?>
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold border <?php echo e($badgeClass); ?>">
                                <?php echo e($item->predikat); ?>

                            </div>
                            <span class="text-[10px] text-gray-400 mt-2"><?php echo e($item->created_at->format('H:i')); ?></span>
                        </div>
                    </div>
                    <?php if($item->catatan): ?>
                        <div class="mt-2 pt-2 border-t border-dashed border-gray-100">
                            <p class="text-xs text-gray-500 italic flex items-start gap-1">
                                <svg class="w-3 h-3 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                <?php echo e($item->catatan); ?>

                            </p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/ustadz/jurnal/index.blade.php ENDPATH**/ ?>