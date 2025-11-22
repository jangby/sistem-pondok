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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Input Setoran')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <style>
        nav.bg-white.border-b { display: none !important; }
        .min-h-screen { background-color: #f3f4f6; }
    </style>

    <div class="py-6 px-4 max-w-md mx-auto pb-24">
        
        <form action="<?php echo e(route('ustadz.jurnal.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="tanggal" value="<?php echo e(date('Y-m-d')); ?>">

            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                <select name="mustawa_id" onchange="window.location.href='<?php echo e(route('ustadz.jurnal.create')); ?>?mustawa_id=' + this.value"
                    class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                    <option value="">-- Pilih Kelas Dulu --</option>
                    <?php $__currentLoopData = $mustawas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m->id); ?>" <?php echo e($selectedMustawaId == $m->id ? 'selected' : ''); ?>>
                            <?php echo e($m->nama); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Santri</label>
                <select name="santri_id" required class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm bg-gray-50"
                    <?php echo e($santris->isEmpty() ? 'disabled' : ''); ?>>
                    <option value="">
                        <?php echo e($santris->isEmpty() ? ($selectedMustawaId ? '-- Tidak ada santri --' : '-- Pilih Kelas diatas --') : '-- Pilih Santri --'); ?>

                    </option>
                    <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>"><?php echo e($s->full_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 space-y-5">
                
                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Kategori</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="kategori_hafalan" value="quran" class="peer sr-only" checked>
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-600 transition text-sm font-medium bg-white">
                                Al-Qur'an
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="kategori_hafalan" value="kitab" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-600 transition text-sm font-medium bg-white">
                                Kitab/Hadits
                            </div>
                        </label>
                    </div>
                </div>

                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Jenis Setoran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_setoran" value="ziyadah" class="peer sr-only" checked>
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-600 transition text-sm font-medium bg-white">
                                Ziyadah (Baru)
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_setoran" value="murojaah" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-600 transition text-sm font-medium bg-white">
                                Murojaah (Ulang)
                            </div>
                        </label>
                    </div>
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Surat / Kitab</label>
                    <input type="text" name="materi" placeholder="Contoh: Al-Mulk" required
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dari (Ayat/Hal)</label>
                        <input type="text" name="start_at" placeholder="1" 
                            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sampai</label>
                        <input type="text" name="end_at" placeholder="5" 
                            class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                    </div>
                </div>

                
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Hasil / Predikat</label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" name="predikat" value="A" class="peer sr-only" checked>
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-green-500 peer-checked:text-white hover:bg-gray-50 transition bg-white">
                                <span class="block font-bold text-lg">A</span>
                                <span class="text-[10px]">Lancar</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="predikat" value="B" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-blue-500 peer-checked:text-white hover:bg-gray-50 transition bg-white">
                                <span class="block font-bold text-lg">B</span>
                                <span class="text-[10px]">Cukup</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="predikat" value="C" class="peer sr-only">
                            <div class="text-center py-2 border rounded-lg peer-checked:bg-red-500 peer-checked:text-white hover:bg-gray-50 transition bg-white">
                                <span class="block font-bold text-lg">C</span>
                                <span class="text-[10px]">Ulang</span>
                            </div>
                        </label>
                    </div>
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Tajwid perlu diperbaiki..."
                        class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"></textarea>
                </div>

            </div>

            
            <div class="mt-6">
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:bg-emerald-700 transition active:scale-95">
                    Simpan Hafalan
                </button>
                <a href="<?php echo e(route('ustadz.jurnal.index')); ?>" class="block text-center text-gray-500 text-sm mt-4 hover:text-gray-800">
                    Batal / Kembali
                </a>
            </div>

        </form>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/ustadz/jurnal/create.blade.php ENDPATH**/ ?>