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

    
    <div class="min-h-screen bg-gray-50 pb-40" x-data="{ showAdd: false }">
        
        
        <div class="bg-emerald-600 pt-8 pb-20 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-1">
                <h1 class="text-xl font-bold text-white">Asrama <?php echo e($gender); ?></h1>
                <a href="<?php echo e(route('pengurus.asrama.index')); ?>" class="bg-white/20 p-2 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            </div>
            <p class="text-emerald-100 text-xs">Daftar gedung asrama aktif.</p>
        </div>

        
        <div class="px-5 -mt-8 relative z-20 space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $asramas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('pengurus.asrama.show', $a->id)); ?>" class="block bg-white p-5 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition hover:border-emerald-200">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg"><?php echo e($a->nama_asrama); ?></h3>
                            <p class="text-xs text-gray-500"><?php echo e($a->komplek); ?> • Ketua: <?php echo e($a->ketua_asrama); ?></p>
                        </div>
                        <span class="bg-emerald-50 text-emerald-700 text-[10px] px-2 py-1 rounded-lg font-bold uppercase border border-emerald-100">
                            <?php echo e($a->jenis_kelamin == 'Laki-laki' ? 'Putra' : 'Putri'); ?>

                        </span>
                    </div>
                    
                    
                    <div class="mt-3">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-400 font-medium">Terisi</span>
                            <span class="font-bold <?php echo e($a->penghuni_count >= $a->kapasitas ? 'text-red-500' : 'text-emerald-600'); ?>">
                                <?php echo e($a->penghuni_count); ?> / <?php echo e($a->kapasitas); ?>

                            </span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="<?php echo e($a->penghuni_count >= $a->kapasitas ? 'bg-red-500' : 'bg-emerald-500'); ?> h-2 rounded-full transition-all duration-500" 
                                 style="width: <?php echo e(($a->penghuni_count / $a->kapasitas) * 100); ?>%"></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-12 text-gray-400 text-sm bg-white rounded-2xl border border-dashed border-gray-200">
                    <p class="mb-1">Belum ada asrama <?php echo e($gender); ?>.</p>
                    <p class="text-xs text-gray-300">Tap tombol tambah di bawah.</p>
                </div>
            <?php endif; ?>
        </div>

        
        <button @click="showAdd = true" class="fixed bottom-24 right-6 bg-emerald-600 text-white w-14 h-14 rounded-full shadow-2xl shadow-emerald-400/50 flex items-center justify-center hover:bg-emerald-700 hover:scale-110 active:scale-90 transition-all duration-300 z-40 border-[3px] border-white/20">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
        </button>

        
        <div x-show="showAdd" class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center" style="display: none;">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showAdd = false"></div>
            
            <div class="bg-white w-full max-w-md rounded-t-[30px] relative z-10 p-6 shadow-2xl transform transition-transform pb-10" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                
                <div class="w-full flex justify-center pt-0 pb-4 cursor-pointer" @click="showAdd = false">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>

                <h3 class="font-bold text-lg mb-4 text-gray-800">Buat Asrama Baru</h3>
                <form action="<?php echo e(route('pengurus.asrama.store')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="jenis_kelamin" value="<?php echo e($jkDb); ?>">
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Nama Asrama</label>
                        <input type="text" name="nama_asrama" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="Cth: Al-Farabi 1" required>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Komplek</label>
                            <input type="text" name="komplek" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="Cth: Blok A" required>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Kapasitas</label>
                            <input type="number" name="kapasitas" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="20" required>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase block mb-1">Ketua Asrama (Teks)</label>
                        <input type="text" name="ketua_asrama" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" placeholder="Nama Ketua sementara..." required>
                        <p class="text-[10px] text-gray-400 mt-1">Anda bisa memilih santri sebagai ketua nanti di menu Settings.</p>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-xl mt-2 shadow-lg shadow-emerald-200 active:scale-95 transition">Simpan Asrama</button>
                </form>
            </div>
        </div>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/asrama/list.blade.php ENDPATH**/ ?>