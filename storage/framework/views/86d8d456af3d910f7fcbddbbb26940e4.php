<?php
use Carbon\Carbon;
Carbon::setLocale('id');
?>

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

    <div class="min-h-screen bg-gray-50 pb-10">
        
        
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden sticky top-0 z-30">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="relative z-10 flex items-center gap-4 text-white">
                <a href="<?php echo e(route('ustadz.dashboard')); ?>" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition backdrop-blur-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold">Jadwal Mengawas</h1>
                    <p class="text-xs text-emerald-100 opacity-90">Daftar ujian yang antum awasi</p>
                </div>
            </div>
        </div>

        
        <div class="px-6 -mt-12 relative z-20 space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('ustadz.ujian.show', $jadwal->id)); ?>" class="block bg-white p-5 rounded-2xl shadow-sm border border-gray-50 hover:shadow-md transition active:scale-[0.98] group">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider 
                                <?php echo e($jadwal->jenis_ujian == 'uts' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'); ?>">
                                <?php echo e($jadwal->jenis_ujian); ?>

                            </span>
                            <span class="ml-2 px-2 py-1 rounded text-[10px] font-bold bg-gray-100 text-gray-600">
                                <?php echo e(ucfirst($jadwal->kategori_tes)); ?>

                            </span>
                        </div>
                        <div class="text-xs font-bold text-emerald-600">
                            <?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y')); ?>

                        </div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-800 group-hover:text-emerald-700 transition"><?php echo e($jadwal->mapel->nama_mapel); ?></h3>
                    <p class="text-sm text-gray-500"><?php echo e($jadwal->mustawa->nama); ?> • <?php echo e(\Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')); ?></p>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-12 text-gray-400">
                    <p class="text-sm">Belum ada jadwal mengawas.</p>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/ustadz/ujian/index.blade.php ENDPATH**/ ?>