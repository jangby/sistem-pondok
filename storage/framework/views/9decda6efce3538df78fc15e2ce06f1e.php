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
    
    <div class="min-h-screen bg-gray-50 pb-24 font-sans">

        
        <div class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
            <div class="px-5 py-4">
                <div class="flex items-center justify-between mb-3">
                    <h1 class="text-lg font-extrabold text-gray-800 tracking-tight">Status Komputer</h1>
                    <div class="text-xs font-medium px-2 py-1 bg-blue-50 text-blue-600 rounded-lg">
                        Total: <?php echo e($computers->count()); ?> Unit
                    </div>
                </div>

                
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input type="text" id="searchPc" onkeyup="filterComputers()" 
                        class="block w-full pl-10 pr-4 py-2.5 bg-gray-100 border-transparent text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all shadow-sm" 
                        placeholder="Cari PC (Contoh: Client-01)...">
                </div>
            </div>
        </div>

        
        <div class="px-5 mt-4 space-y-3" id="pcListContainer">
            <?php $__empty_1 = true; $__currentLoopData = $computers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    // Logika Online (Toleransi 2 menit)
                    $isOnline = $pc->last_seen >= now()->subMinutes(2);
                    $lastSeenText = \Carbon\Carbon::parse($pc->last_seen)->diffForHumans();
                ?>

                <div class="pc-item bg-white p-4 rounded-2xl shadow-[0_2px_8px_rgba(0,0,0,0.04)] border border-gray-100 flex justify-between items-center transition-all hover:shadow-md hover:border-blue-100 group">
                    
                    <div class="flex items-center gap-4">
                        
                        <div class="relative">
                            <div class="w-12 h-12 rounded-2xl <?php echo e($isOnline ? 'bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600' : 'bg-gray-50 text-gray-400'); ?> flex items-center justify-center text-xl shadow-inner">
                                <i class="fas fa-desktop"></i>
                            </div>
                            
                            
                            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                <?php if($isOnline): ?>
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-green-500 border-2 border-white"></span>
                                <?php else: ?>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-400 border-2 border-white"></span>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        
                        <div>
                            <h3 class="pc-name text-sm font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                                <?php echo e($pc->pc_name); ?>

                            </h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="bg-gray-100 text-gray-500 text-[10px] px-1.5 py-0.5 rounded font-mono">
                                    <?php echo e($pc->ip_address ?? '0.0.0.0'); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="text-right">
                        <?php if($pc->pending_command): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-100 text-yellow-700 animate-pulse mb-1">
                                <i class="fas fa-spinner fa-spin mr-1.5"></i> <?php echo e(strtoupper($pc->pending_command)); ?>

                            </span>
                        <?php else: ?>
                            <span class="block text-[11px] font-bold <?php echo e($isOnline ? 'text-green-600' : 'text-red-500'); ?>">
                                <?php echo e($isOnline ? 'ONLINE' : 'OFFLINE'); ?>

                            </span>
                        <?php endif; ?>
                        
                        <p class="text-[10px] text-gray-400 mt-0.5 flex items-center justify-end gap-1">
                            <i class="far fa-clock"></i> <?php echo e($lastSeenText); ?>

                        </p>
                    </div>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <i class="fas fa-desktop text-3xl"></i>
                    </div>
                    <h3 class="text-gray-900 font-bold">Belum ada data PC</h3>
                    <p class="text-gray-500 text-xs mt-1">Jalankan script client di komputer lab.</p>
                </div>
            <?php endif; ?>

            
            <div id="noResult" class="hidden text-center py-10">
                <p class="text-gray-400 text-sm">Komputer tidak ditemukan.</p>
            </div>
        </div>

    </div>

    
    <?php echo $__env->make('layouts.petugas-lab-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php $__env->startPush('scripts'); ?>
    <script>
        function filterComputers() {
            var input, filter, container, items, name, i, txtValue;
            input = document.getElementById('searchPc');
            filter = input.value.toUpperCase();
            container = document.getElementById("pcListContainer");
            items = container.getElementsByClassName('pc-item');
            var foundCount = 0;

            for (i = 0; i < items.length; i++) {
                name = items[i].getElementsByClassName("pc-name")[0];
                if (name) {
                    txtValue = name.textContent || name.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        items[i].style.display = "";
                        foundCount++;
                    } else {
                        items[i].style.display = "none";
                    }
                }
            }

            // Tampilkan pesan jika tidak ada hasil
            var noResult = document.getElementById("noResult");
            if (foundCount === 0 && items.length > 0) {
                noResult.classList.remove('hidden');
            } else {
                noResult.classList.add('hidden');
            }
        }
    </script>
    <?php $__env->stopPush(); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/petugas/lab-komputer/list.blade.php ENDPATH**/ ?>