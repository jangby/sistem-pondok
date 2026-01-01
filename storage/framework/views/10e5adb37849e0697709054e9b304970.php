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
            Pilih Metode Pembayaran
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12" x-data="{ 
        nominal: <?php echo e($calonSantri->sisa_tagihan); ?>,
        biayaAdmin: 5000,
        metode: '',
        get total() { return parseInt(this.nominal) + this.biayaAdmin; }
    }">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="<?php echo e(route('ppdb.payment.process')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 mb-6">
                    <h3 class="font-bold text-gray-800 mb-4">1. Nominal Pembayaran</h3>
                    
                    <div class="mb-4">
                        <label class="block text-gray-500 text-sm mb-2">Jumlah Tagihan (Sisa: Rp <?php echo e(number_format($calonSantri->sisa_tagihan,0,',','.')); ?>)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 font-bold text-gray-500">Rp</span>
                            <input type="number" name="nominal_bayar" x-model="nominal"
                                class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl font-bold text-lg focus:ring-emerald-500 focus:border-emerald-500"
                                min="10000" max="<?php echo e($calonSantri->sisa_tagihan); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 mb-6">
                    <h3 class="font-bold text-gray-800 mb-4">2. Pilih Metode Bayar</h3>

                    <div class="space-y-3">
                        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-emerald-50 transition"
                            :class="metode === 'bca_va' ? 'border-emerald-500 bg-emerald-50 ring-1 ring-emerald-500' : 'border-gray-200'">
                            <input type="radio" name="payment_method" value="bca_va" class="sr-only" x-model="metode" required>
                            <div class="w-12 h-8 bg-blue-700 rounded flex items-center justify-center text-white text-xs font-bold mr-4">BCA</div>
                            <span class="font-medium text-gray-700">BCA Virtual Account</span>
                        </label>

                        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-emerald-50 transition"
                            :class="metode === 'bni_va' ? 'border-emerald-500 bg-emerald-50 ring-1 ring-emerald-500' : 'border-gray-200'">
                            <input type="radio" name="payment_method" value="bni_va" class="sr-only" x-model="metode">
                            <div class="w-12 h-8 bg-orange-600 rounded flex items-center justify-center text-white text-xs font-bold mr-4">BNI</div>
                            <span class="font-medium text-gray-700">BNI Virtual Account</span>
                        </label>

                        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-emerald-50 transition"
                            :class="metode === 'qris' ? 'border-emerald-500 bg-emerald-50 ring-1 ring-emerald-500' : 'border-gray-200'">
                            <input type="radio" name="payment_method" value="qris" class="sr-only" x-model="metode">
                            <div class="w-12 h-8 bg-gray-800 rounded flex items-center justify-center text-white text-xs font-bold mr-4">QRIS</div>
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-700">QRIS (Gopay/Dana/Shopee)</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-lg sticky bottom-4">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-500">Total (+Admin 5rb)</span>
                        <span class="text-2xl font-bold text-emerald-600" x-text="'Rp ' + (total).toLocaleString('id-ID')"></span>
                    </div>
                    <button type="submit" 
                        :disabled="!metode"
                        class="w-full bg-emerald-600 text-white font-bold py-3.5 rounded-xl hover:bg-emerald-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                        Bayar Sekarang
                    </button>
                </div>

            </form>
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