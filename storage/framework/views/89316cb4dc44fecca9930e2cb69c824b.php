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

    <div class="min-h-screen bg-gray-50 pb-28" x-data="{ showAdd: false, price: 0, qty: 0 }">
        
        
        <div class="bg-emerald-600 pt-8 pb-16 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-6">
                
                <div class="flex items-center gap-3">
                    <a href="<?php echo e(route('pengurus.inventaris.barang.index')); ?>" class="bg-white/20 p-2 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-bold text-white leading-tight truncate max-w-[150px] sm:max-w-md"><?php echo e($lokasi->name); ?></h1>
                        <p class="text-emerald-100 text-xs">Inventaris Aset</p>
                    </div>
                </div>

                
                <div class="flex items-center gap-2">
                    
                    <a href="<?php echo e(route('pengurus.inventaris.barang.print-labels', $lokasi->id)); ?>" target="_blank" 
   class="bg-white/20 border border-white/30 text-white px-3 py-2 rounded-xl text-xs font-bold backdrop-blur-md hover:bg-white/30 transition flex items-center gap-2 shadow-sm"
   title="Download Label Barcode Lokasi Ini">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
    </svg>
    <span class="hidden sm:inline">Label</span>
</a>

                    
                    <button @click="showAdd = true" class="bg-white text-emerald-700 px-4 py-2 rounded-xl text-xs font-bold shadow-lg active:scale-95 transition flex items-center gap-2 hover:bg-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Barang</span>
                    </button>
                </div>
            </div>
            
            
            <form method="GET" class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-emerald-200 group-focus-within:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                       placeholder="Cari nama atau kode barang..." 
                       class="w-full pl-10 pr-4 py-3 rounded-2xl border-0 bg-emerald-800/30 text-white placeholder-emerald-200/70 text-sm focus:ring-2 focus:ring-white/50 focus:bg-emerald-800/50 backdrop-blur-sm transition shadow-inner">
            </form>
        </div>

        
        <div class="px-5 -mt-6 relative z-20 space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $barangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white p-4 rounded-2xl shadow-[0_2px_8px_rgb(0,0,0,0.04)] border border-gray-100 flex flex-col gap-2 hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1 pr-2">
                            <h3 class="font-bold text-gray-800 leading-snug"><?php echo e($b->name); ?></h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] text-gray-500 bg-gray-100 px-2 py-0.5 rounded font-mono border border-gray-200 tracking-wide"><?php echo e($b->code ?? '-'); ?></span>
                            </div>
                        </div>
                        
                        
                        <form action="<?php echo e(route('pengurus.inventaris.barang.destroy', $b->id)); ?>" method="POST" onsubmit="return confirm('Hapus barang ini?')" class="ml-2 flex-shrink-0">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="text-gray-300 hover:text-red-500 p-1.5 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                    
                    <div class="flex justify-between items-end border-t border-gray-50 pt-3 mt-1">
                        <div class="text-xs text-gray-500 space-y-1.5">
                            <p class="flex items-center gap-1.5">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full shadow-sm"></span> 
                                Bagus: <strong class="text-emerald-700 font-bold"><?php echo e($b->qty_good); ?></strong> <?php echo e($b->unit); ?>

                            </p>
                            <?php if($b->qty_damaged > 0 || $b->qty_borrowed > 0): ?>
                                <p class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-orange-400 rounded-full shadow-sm"></span> 
                                    Lainnya: <strong class="text-orange-600"><?php echo e($b->qty_damaged + $b->qty_borrowed + $b->qty_repairing); ?></strong>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider font-medium">Nilai Aset</p>
                            <p class="font-bold text-gray-800 text-sm">Rp <?php echo e(number_format($b->price * ($b->qty_good + $b->qty_damaged + $b->qty_borrowed + $b->qty_repairing), 0, ',', '.')); ?></p>
                        </div>
                    </div>
                    
                    <?php if($b->pic): ?>
                        <div class="mt-2 pt-2 border-t border-dashed border-gray-100 text-[10px] text-gray-400 flex items-center gap-1.5">
                            <div class="p-0.5 bg-gray-100 rounded-full">
                                <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <span>PIC: <span class="text-gray-600 font-medium"><?php echo e($b->pic->full_name); ?></span></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Belum ada barang di lokasi ini.</p>
                    <p class="text-gray-400 text-xs mt-1">Silakan tambah barang baru.</p>
                </div>
            <?php endif; ?>
            
            <div class="mt-4 pb-6"><?php echo e($barangs->links('pagination::tailwind')); ?></div>
        </div>

        
        <div x-show="showAdd" class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center px-4 pb-4 sm:pb-0" style="display: none;">
            <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" @click="showAdd = false"></div>
            
            <div class="bg-white w-full max-w-md rounded-t-[2rem] sm:rounded-[2rem] relative z-10 p-6 shadow-2xl h-[85vh] sm:h-auto sm:max-h-[90vh] flex flex-col overflow-hidden transform transition-transform duration-300">
                
                <div class="flex justify-between items-center mb-6 flex-shrink-0">
                    <div>
                        <h3 class="font-bold text-xl text-gray-800">Tambah Barang</h3>
                        <p class="text-sm text-gray-500"><?php echo e($lokasi->name); ?></p>
                    </div>
                    <button @click="showAdd = false" class="bg-gray-100 p-2 rounded-full text-gray-500 hover:bg-gray-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 pb-4 custom-scrollbar pr-2">
                    <form action="<?php echo e(route('pengurus.inventaris.barang.store')); ?>" method="POST" class="space-y-5">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="location_id" value="<?php echo e($lokasi->id); ?>">
                        
                        
                        <div class="bg-blue-50 border border-blue-100 p-4 rounded-2xl">
                            <label class="block text-blue-800 text-xs font-bold uppercase mb-1">Kode Barang</label>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H6v-4h12v4zm-6-2a2 2 0 100-4 2 2 0 000 4zm-6-6h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2v-8a2 2 0 012-2z"></path></svg>
                                <input type="text" name="kode_barang" 
                                    class="w-full bg-transparent border-none p-0 text-blue-900 font-mono text-sm focus:ring-0 cursor-default" 
                                    placeholder="Auto Generate (INV-...)" 
                                    readonly>
                            </div>
                            <p class="text-[10px] text-blue-400 mt-1">*Kode akan dibuat otomatis oleh sistem.</p>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1.5 ml-1">Nama Barang</label>
                            <input type="text" name="name" class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 py-3 px-4 bg-gray-50 focus:bg-white transition" placeholder="Contoh: Meja Belajar Kayu" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1.5 ml-1">Satuan</label>
                                <input type="text" name="unit" class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 py-3 px-4 bg-gray-50 focus:bg-white transition" placeholder="Pcs/Unit" required>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase block mb-1.5 ml-1">Stok Awal</label>
                                <input type="number" name="qty_good" x-model="qty" class="w-full rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500 py-3 px-4 bg-gray-50 focus:bg-white transition" required>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200">
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1.5">Estimasi Harga Satuan</label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="price" x-model="price" class="w-full rounded-xl border-gray-200 pl-10 focus:border-emerald-500 focus:ring-emerald-500" placeholder="0" required>
                            </div>
                            
                            <div class="flex justify-between items-center pt-3 mt-2 border-t border-gray-200/50">
                                <span class="text-xs font-medium text-gray-400">Total Nilai Aset</span>
                                <span class="text-base font-black text-emerald-600 tracking-tight" x-text="'Rp ' + (qty * price).toLocaleString('id-ID')"></span>
                            </div>
                        </div>

                        <div wire:ignore>
                            <label class="text-xs font-bold text-gray-500 uppercase block mb-1.5 ml-1">Penanggung Jawab (PIC)</label>
                            <select id="select-pic" name="pic_santri_id" placeholder="Cari Nama Santri..." autocomplete="off" class="w-full">
                                <option value="">- Pilih Santri -</option>
                                <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s->id); ?>"><?php echo e($s->full_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-emerald-200 active:scale-[0.98] transition flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Data Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <?php echo $__env->make('layouts.pengurus-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#select-pic",{ 
                create: false, 
                sortField: { field: "text", direction: "asc" },
                plugins: ['remove_button'],
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/inventaris/barang/list.blade.php ENDPATH**/ ?>