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

    <div class="min-h-screen bg-gray-50 pb-24 font-sans">
        <div class="bg-emerald-600 pt-8 pb-12 px-6 rounded-b-[35px] shadow-lg">
            <div class="flex items-center gap-3 mt-2">
                <a href="<?php echo e(route('pengurus.perpulangan.index')); ?>" class="text-white hover:bg-white/20 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-xl font-bold text-white">Buat Jadwal Baru</h1>
            </div>
        </div>

        <div class="px-5 -mt-6 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                
                <form action="<?php echo e(route('pengurus.perpulangan.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Judul Kegiatan</label>
                        <input type="text" name="judul" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition text-sm py-3" placeholder="Contoh: Libur Semester Ganjil 2025" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Mulai Pulang</label>
                            <input type="date" name="tanggal_mulai" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Wajib Kembali</label>
                            <input type="date" name="tanggal_akhir" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition text-sm" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Catatan Tambahan</label>
                        <textarea name="keterangan" rows="3" class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition text-sm" placeholder="Contoh: Santri wajib membawa surat jalan..."></textarea>
                    </div>

                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-xl border border-gray-200 mb-6">
                        <span class="text-sm font-medium text-gray-700">Set sebagai Aktif?</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 active:scale-95 transition duration-200">
                        Simpan Jadwal
                    </button>
                </form>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/perpulangan/create.blade.php ENDPATH**/ ?>