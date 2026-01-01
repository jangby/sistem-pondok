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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Instruksi Pembayaran</h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-xl mx-auto px-4">
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <div class="p-6 text-center bg-gray-50 border-b">
                    <p class="text-sm text-gray-500 mb-2">Total Pembayaran</p>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Rp <?php echo e(number_format($transaksi->gross_amount + $transaksi->biaya_admin, 0, ',', '.')); ?>

                    </h1>
                    <span class="inline-block mt-2 px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold uppercase tracking-wide">
                        Menunggu Pembayaran
                    </span>
                </div>

                <div class="p-6">
                    <?php if(in_array($transaksi->payment_type, ['bca_va', 'bni_va', 'mandiri_bill'])): ?>
                        <div class="text-center">
                            <p class="text-gray-600 mb-2">Nomor Virtual Account:</p>
                            <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl flex justify-between items-center">
                                <span class="font-mono text-2xl font-bold text-emerald-700 tracking-wider">
                                    <?php echo e($transaksi->payment_code); ?>

                                </span>
                                <button onclick="navigator.clipboard.writeText('<?php echo e($transaksi->payment_code); ?>')" class="text-emerald-600 hover:text-emerald-800 text-sm font-bold">
                                    Salin
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 mt-4">Silakan transfer ke nomor di atas sebelum 24 jam.</p>
                        </div>
                    
                    <?php elseif($transaksi->payment_type == 'qris'): ?>
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">Scan QRIS di bawah ini:</p>
                            <div class="inline-block p-2 border rounded-lg">
                                <img src="<?php echo e($transaksi->payment_url); ?>" alt="QRIS Code" class="w-64 h-64">
                            </div>
                            <p class="text-xs text-gray-400 mt-4">Dapat discan menggunakan GoPay, OVO, Dana, LinkAja, dll.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="p-4 bg-gray-50 border-t text-center">
                    <a href="<?php echo e(route('dashboard')); ?>" class="text-sm text-gray-500 hover:text-gray-900 font-medium">
                        &larr; Kembali ke Dashboard
                    </a>
                </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/ppdb/instruksi.blade.php ENDPATH**/ ?>