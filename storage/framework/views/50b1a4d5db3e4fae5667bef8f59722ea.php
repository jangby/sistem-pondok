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
    <div class="bg-white p-4 shadow-sm flex items-center sticky top-0 z-30">
        <a href="<?php echo e(route('petugas-lab.dashboard')); ?>" class="mr-4 text-gray-600 hover:text-blue-600">
            <i class="fas fa-arrow-left text-lg"></i>
        </a>
        <h1 class="text-lg font-bold text-gray-800">Lapor Masalah</h1>
    </div>

    <div class="p-6">
        <form action="#" class="space-y-4">
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Pilih Komputer</label>
                <select class="w-full mt-1 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500">
                    <option>PC-LAB-01</option>
                    <option>PC-LAB-02</option>
                    <option>Jaringan / Wifi</option>
                    <option>Lainnya</option>
                </select>
            </div>
            
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Deskripsi Masalah</label>
                <textarea rows="4" class="w-full mt-1 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Mouse tidak berfungsi..."></textarea>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Foto Bukti (Opsional)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl bg-gray-50">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-camera text-gray-400 text-3xl"></i>
                        <div class="text-sm text-gray-600">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                <span>Upload file</span>
                                <input type="file" class="sr-only">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <button class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl shadow hover:bg-indigo-700 mt-4">
                Kirim Laporan
            </button>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/petugas/lab-komputer/laporan.blade.php ENDPATH**/ ?>