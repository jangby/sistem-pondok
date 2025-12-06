<?php if (isset($component)) { $__componentOriginal2baa2b06861b749638c1d80b12e38e99 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2baa2b06861b749638c1d80b12e38e99 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.petugas-lab-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('petugas-lab-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <div class="relative pt-10 pb-16 px-6 rounded-b-[40px] overflow-hidden shadow-2xl bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-900">
        
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl mix-blend-overlay"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-indigo-400 opacity-20 rounded-full blur-2xl mix-blend-overlay"></div>

        <div class="relative z-10 flex justify-between items-center">
            <div class="text-white">
                <p class="text-xs font-semibold text-blue-200 uppercase tracking-widest mb-1">Control Panel</p>
                <h1 class="text-3xl font-extrabold tracking-tight">Halo, <?php echo e(explode(' ', Auth::user()->name)[0]); ?> 👋</h1>
            </div>
            <div class="h-12 w-12 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-md border border-white/20 shadow-lg">
                <i class="fas fa-bell text-white text-lg"></i>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mt-8 relative z-10">
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10 text-center shadow-lg">
                <span class="block text-3xl font-black text-white tracking-tighter"><?php echo e($totalKomputer); ?></span>
                <span class="text-[10px] font-medium text-blue-100 uppercase tracking-wide">Total PC</span>
            </div>
            <div class="bg-green-500/20 backdrop-blur-md rounded-2xl p-4 border border-green-400/30 text-center shadow-lg relative overflow-hidden">
                <div class="absolute inset-0 bg-green-400/10 blur-xl"></div>
                <span class="block text-3xl font-black text-green-300 tracking-tighter relative z-10"><?php echo e($komputerOnline); ?></span>
                <span class="text-[10px] font-medium text-green-100 uppercase tracking-wide relative z-10">Online</span>
            </div>
            <div class="bg-red-500/20 backdrop-blur-md rounded-2xl p-4 border border-red-400/30 text-center shadow-lg relative overflow-hidden">
                <div class="absolute inset-0 bg-red-400/10 blur-xl"></div>
                <span class="block text-3xl font-black text-red-300 tracking-tighter relative z-10"><?php echo e($komputerOffline); ?></span>
                <span class="text-[10px] font-medium text-red-100 uppercase tracking-wide relative z-10">Offline</span>
            </div>
        </div>
    </div>

    <div class="px-6 -mt-8 relative z-20 pb-24">
        <div class="bg-white rounded-[30px] shadow-xl p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-gray-800 text-sm font-bold uppercase tracking-wider">Aksi Cepat</h3>
                <span class="h-1 w-12 bg-gray-200 rounded-full"></span>
            </div>
            
            <div class="grid grid-cols-3 gap-y-8 gap-x-4">
                
                <a href="<?php echo e(route('petugas-lab.komputer.index')); ?>" class="flex flex-col items-center group cursor-pointer">
                    <div class="w-16 h-16 bg-blue-50 rounded-[20px] flex items-center justify-center text-blue-600 text-2xl shadow-sm border border-blue-100 group-active:scale-90 transition-transform duration-200 ease-out">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-600 text-center mt-2 leading-tight group-hover:text-blue-600 transition-colors">Status PC</span>
                </a>

                <form action="<?php echo e(route('petugas-lab.shutdown.all')); ?>" method="POST" class="flex flex-col items-center w-full" onsubmit="return confirm('⚠️ KONFIRMASI:\n\nMatikan SEMUA komputer yang sedang Online?');">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-16 h-16 bg-red-50 rounded-[20px] flex items-center justify-center text-red-600 text-2xl shadow-sm border border-red-100 active:scale-90 transition-transform duration-200 ease-out w-full">
                        <i class="fas fa-power-off"></i>
                    </button>
                    <span class="text-[11px] font-bold text-gray-600 text-center mt-2 leading-tight">Matikan<br>Semua</span>
                </form>

                <a href="<?php echo e(route('petugas-lab.refresh')); ?>" class="flex flex-col items-center group cursor-pointer">
                    <div class="w-16 h-16 bg-green-50 rounded-[20px] flex items-center justify-center text-green-600 text-2xl shadow-sm border border-green-100 group-active:scale-90 transition-transform duration-200 ease-out">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-600 text-center mt-2 leading-tight group-hover:text-green-600 transition-colors">Refresh<br>Data</span>
                </a>

                <a href="<?php echo e(route('petugas-lab.password.form')); ?>" class="flex flex-col items-center group cursor-pointer">
                    <div class="w-16 h-16 bg-yellow-50 rounded-[20px] flex items-center justify-center text-yellow-600 text-2xl shadow-sm border border-yellow-100 group-active:scale-90 transition-transform duration-200 ease-out">
                        <i class="fas fa-key"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-600 text-center mt-2 leading-tight group-hover:text-yellow-600 transition-colors">Ganti<br>Pass</span>
                </a>

                <a href="<?php echo e(route('petugas-lab.jadwal')); ?>" class="flex flex-col items-center group cursor-pointer">
                    <div class="w-16 h-16 bg-purple-50 rounded-[20px] flex items-center justify-center text-purple-600 text-2xl shadow-sm border border-purple-100 group-active:scale-90 transition-transform duration-200 ease-out">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-600 text-center mt-2 leading-tight group-hover:text-purple-600 transition-colors">Jadwal<br>Lab</span>
                </a>

                <a href="<?php echo e(route('petugas-lab.laporan')); ?>" class="flex flex-col items-center group cursor-pointer">
                    <div class="w-16 h-16 bg-indigo-50 rounded-[20px] flex items-center justify-center text-indigo-600 text-2xl shadow-sm border border-indigo-100 group-active:scale-90 transition-transform duration-200 ease-out">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <span class="text-[11px] font-bold text-gray-600 text-center mt-2 leading-tight group-hover:text-indigo-600 transition-colors">Laporan</span>
                </a>

            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-4 px-2">Log Aktivitas Live</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center transition-all hover:shadow-md">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl <?php echo e($pc->last_seen >= now()->subMinutes(2) ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400'); ?> flex items-center justify-center text-lg">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <?php if($pc->last_seen >= now()->subMinutes(2)): ?>
                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between items-center">
                            <h4 class="text-sm font-bold text-gray-800"><?php echo e($pc->pc_name); ?></h4>
                            <span class="text-[10px] text-gray-400"><?php echo e(\Carbon\Carbon::parse($pc->last_seen)->diffForHumans(null, true)); ?></span>
                        </div>
                        <div class="flex items-center mt-1">
                            <?php if($pc->pending_command): ?>
                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] rounded font-bold mr-2">
                                    <i class="fas fa-hourglass-half mr-1"></i> <?php echo e(strtoupper($pc->pending_command)); ?>

                                </span>
                            <?php endif; ?>
                            <p class="text-xs text-gray-500 truncate max-w-[150px]">IP: <?php echo e($pc->ip_address ?? '-'); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-8 bg-white rounded-2xl border border-dashed border-gray-300">
                    <i class="fas fa-history text-gray-300 text-2xl mb-2"></i>
                    <p class="text-gray-400 text-xs">Belum ada aktivitas.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2baa2b06861b749638c1d80b12e38e99)): ?>
<?php $attributes = $__attributesOriginal2baa2b06861b749638c1d80b12e38e99; ?>
<?php unset($__attributesOriginal2baa2b06861b749638c1d80b12e38e99); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2baa2b06861b749638c1d80b12e38e99)): ?>
<?php $component = $__componentOriginal2baa2b06861b749638c1d80b12e38e99; ?>
<?php unset($__componentOriginal2baa2b06861b749638c1d80b12e38e99); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/petugas/lab-komputer/dashboard.blade.php ENDPATH**/ ?>