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
                <div>
                    <h1 class="text-xl font-bold text-white">Cetak Kartu</h1>
                    <p class="text-emerald-100 text-xs"><?php echo e($event->judul); ?></p>
                </div>
            </div>
        </div>

        <div class="px-5 -mt-6 relative z-10">
            
            <div class="bg-white rounded-2xl shadow-lg p-5 border border-gray-100 mb-4">
                <form action="<?php echo e(route('pengurus.perpulangan.pilih-santri', $event->id)); ?>" method="GET">
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Pilih Kelas Pesantren (Mustawa)</label>
                    <div class="flex gap-2">
                        <select name="mustawa_id" class="w-full rounded-xl border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-200" required>
                            <option value="">-- Pilih Mustawa --</option>
                            <?php $__currentLoopData = $mustawas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($m->id); ?>" <?php echo e(request('mustawa_id') == $m->id ? 'selected' : ''); ?>>
                                    <?php echo e($m->nama); ?> 
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="submit" class="bg-emerald-600 text-white px-4 rounded-xl font-bold shadow-md hover:bg-emerald-700">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <?php if(request('mustawa_id') && $santris->count() > 0): ?>
            <form action="<?php echo e(route('pengurus.perpulangan.cetak', $event->id)); ?>" method="POST" target="_blank">
                <?php echo csrf_field(); ?>
                
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700">Daftar Santri</h3>
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="checkAll" class="rounded text-emerald-600 focus:ring-emerald-500">
                            <span class="ml-2 text-xs text-gray-600">Pilih Semua</span>
                        </label>
                    </div>

                    <div class="divide-y divide-gray-100 max-h-[400px] overflow-y-auto">
                        <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center gap-3 p-4 hover:bg-emerald-50 cursor-pointer transition">
                            <input type="checkbox" name="santri_ids[]" value="<?php echo e($santri->id); ?>" class="item-checkbox rounded text-emerald-600 focus:ring-emerald-500 w-5 h-5">
                            
                            <div class="flex-1">
                                <p class="font-bold text-gray-800 text-sm"><?php echo e($santri->full_name); ?></p>
                                <p class="text-xs text-gray-500">
                                    <?php echo e($santri->nis); ?> | 
                                    <?php echo e($santri->mustawa->nama ?? 'Mustawa ?'); ?> | 
                                    <?php echo e($santri->desa ?? '-'); ?>

                                </p>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="p-4 bg-gray-50 border-t border-gray-100">
                        <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-emerald-700 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Kartu
                        </button>
                    </div>
                </div>
            </form>
            <?php elseif(request('mustawa_id')): ?>
                <div class="text-center py-10 bg-white rounded-2xl shadow border border-gray-100">
                    <p class="text-gray-500 text-sm">Tidak ada santri di mustawa ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php $__env->startPush('scripts'); ?>
    <script>
        document.getElementById('checkAll')?.addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/perpulangan/pilih_santri.blade.php ENDPATH**/ ?>