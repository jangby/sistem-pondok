<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Scan Pengembalian Buku')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Scan Barcode Buku</h3>
                    
                    <form action="<?php echo e(route('sekolah.superadmin.perpustakaan.sirkulasi.kembali.index')); ?>" method="GET" class="flex gap-4">
                        <div class="flex-1">
                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['name' => 'kode_buku','class' => 'w-full text-lg','placeholder' => 'Scan barcode buku di sini...','autofocus' => true,'required' => true,'autocomplete' => 'off']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'kode_buku','class' => 'w-full text-lg','placeholder' => 'Scan barcode buku di sini...','autofocus' => true,'required' => true,'autocomplete' => 'off']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                        </div>
                        <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['class' => 'py-3 px-6 text-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'py-3 px-6 text-lg']); ?>
                            <?php echo e(__('Cari')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                    </form>
                    <p class="text-sm text-gray-500 mt-2">Pastikan kursor aktif di kolom input sebelum melakukan scan.</p>
                </div>
            </div>

            
            <?php if(isset($peminjaman) && $peminjaman): ?>
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 shadow-sm animate-pulse-once">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-lg font-bold text-green-800 mb-1">Data Peminjaman Ditemukan!</h4>
                        <p class="text-green-700">Buku ini sedang dipinjam. Lanjutkan proses untuk mengembalikan.</p>
                        
                        <div class="mt-4 grid grid-cols-2 gap-x-12 gap-y-2 text-sm">
                            <div>
                                <span class="text-gray-500 block">Judul Buku:</span>
                                <span class="font-semibold text-gray-900 text-base"><?php echo e($peminjaman->buku->judul); ?></span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Peminjam:</span>
                                <span class="font-semibold text-gray-900 text-base"><?php echo e($peminjaman->santri->name); ?></span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Kode Buku:</span>
                                <span class="font-mono text-gray-700"><?php echo e($peminjaman->buku->kode_buku); ?></span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Tgl Wajib Kembali:</span>
                                <span class="font-semibold <?php echo e(\Carbon\Carbon::now()->gt($peminjaman->tgl_wajib_kembali) ? 'text-red-600' : 'text-gray-700'); ?>">
                                    <?php echo e($peminjaman->tgl_wajib_kembali->format('d M Y')); ?>

                                </span>
                            </div>
                        </div>
                    </div>

                    <a href="<?php echo e(route('sekolah.superadmin.perpustakaan.sirkulasi.kembali.form', $peminjaman->id)); ?>" 
                       class="bg-blue-600 text-white font-bold py-3 px-6 rounded-md shadow hover:bg-blue-700 transition flex items-center gap-2">
                        <span>✅</span> PROSES PENGEMBALIAN
                    </a>
                </div>
            </div>
            <?php elseif(request()->has('kode_buku')): ?>
                
                
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/superadmin/perpus/sirkulasi/return-scan.blade.php ENDPATH**/ ?>