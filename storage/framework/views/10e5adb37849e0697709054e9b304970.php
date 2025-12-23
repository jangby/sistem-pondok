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
            Pembayaran PPDB
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                
                
                <div class="text-center mb-8">
                    <p class="text-gray-500 text-sm">Sisa Tagihan Anda</p>
                    <h1 class="text-4xl font-extrabold text-gray-900 mt-2">
                        Rp <?php echo e(number_format($calonSantri->sisa_tagihan, 0, ',', '.')); ?>

                    </h1>
                </div>

                <hr class="border-gray-100 mb-6">

                
                <form action="<?php echo e(route('ppdb.payment.process')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-6">
                        <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'nominal_bayar','value' => 'Masukkan Nominal Pembayaran']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'nominal_bayar','value' => 'Masukkan Nominal Pembayaran']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm font-bold">Rp</span>
                            </div>
                            <input type="number" name="nominal_bayar" id="nominal_bayar" 
                                class="block w-full rounded-md border-gray-300 pl-10 focus:border-emerald-500 focus:ring-emerald-500 sm:text-lg font-bold py-3" 
                                placeholder="0"
                                min="10000"
                                max="<?php echo e($calonSantri->sisa_tagihan); ?>"
                                required
                                value="<?php echo e(old('nominal_bayar', $calonSantri->sisa_tagihan)); ?>">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gray-900 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-gray-800 transition transform hover:-translate-y-0.5">
                        Lanjut ke Pembayaran &rarr;
                    </button>
                    
                    <div class="mt-4 text-center">
                        <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Batal / Kembali</a>
                    </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/ppdb/payment.blade.php ENDPATH**/ ?>