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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Pembayaran PPDB')); ?> : <?php echo e($calonSantri->nama_lengkap); ?>

            </h2>
            
            <a href="<?php echo e(route('adminpondok.ppdb.pendaftar.show', $calonSantri->id)); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                &larr; Kembali ke Detail
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <?php if(session('print_url')): ?>
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex justify-between items-center">
                    <span>✅ Pembayaran berhasil disimpan!</span>
                    <a href="<?php echo e(session('print_url')); ?>" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        Cetak Struk Sekarang
                    </a>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Input Pembayaran Tunai</h3>
                        
                        
                        <div class="bg-emerald-50 rounded-lg p-4 mb-6 border border-emerald-100">
                            <p class="text-xs text-emerald-600 font-semibold uppercase">Sisa Tagihan</p>
                            <p class="text-3xl font-bold text-emerald-800">Rp <?php echo e(number_format($calonSantri->sisa_tagihan, 0, ',', '.')); ?></p>
                            <p class="text-xs text-gray-500 mt-1">Total Biaya: Rp <?php echo e(number_format($calonSantri->total_biaya, 0, ',', '.')); ?></p>
                        </div>

                        <?php if($calonSantri->sisa_tagihan > 0): ?>
                        
                        <form action="<?php echo e(route('adminpondok.ppdb.pendaftar.payment.store', $calonSantri->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Bayar (Rp)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-gray-500 font-bold">Rp</span>
                                    <input type="number" name="nominal" class="w-full pl-10 border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" 
                                           placeholder="0" min="1000" max="<?php echo e($calonSantri->sisa_tagihan); ?>" required>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">*Maksimal Rp <?php echo e(number_format($calonSantri->sisa_tagihan, 0, ',', '.')); ?></p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                                <textarea name="keterangan" rows="2" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: Pembayaran Cicilan 1"></textarea>
                            </div>

                            <button type="submit" onclick="return confirm('Pastikan uang tunai sudah diterima. Lanjutkan?')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg shadow-lg transition transform hover:-translate-y-0.5">
                                💵 Terima Pembayaran Tunai
                            </button>
                        </form>
                        <?php else: ?>
                            <div class="text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <span class="text-4xl">🎉</span>
                                <h3 class="mt-2 font-bold text-gray-800">LUNAS</h3>
                                <p class="text-sm text-gray-500">Tidak ada tagihan tersisa.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex justify-between items-center">
                            <span>Riwayat Pembayaran</span>
                            <span class="text-xs font-normal bg-blue-100 text-blue-700 px-2 py-1 rounded">Gabungan (Midtrans & Tunai)</span>
                        </h3>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                    <tr>
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3">Order ID</th>
                                        <th class="px-4 py-3">Metode</th>
                                        <th class="px-4 py-3 text-right">Nominal</th>
                                        <th class="px-4 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <?php echo e($trx->created_at->format('d M Y H:i')); ?>

                                        </td>
                                        <td class="px-4 py-3 font-mono text-xs">
                                            <?php echo e($trx->order_id); ?>

                                        </td>
                                        <td class="px-4 py-3">
                                            <?php if($trx->payment_type == 'cash'): ?>
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">TUNAI (Admin)</span>
                                            <?php else: ?>
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-200 uppercase"><?php echo e(str_replace('_', ' ', $trx->payment_type)); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-gray-800">
                                            Rp <?php echo e(number_format($trx->gross_amount, 0, ',', '.')); ?>

                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            
                                            <a href="<?php echo e(route('adminpondok.ppdb.pendaftar.payment.print', $trx->id)); ?>" target="_blank" class="text-gray-500 hover:text-gray-800" title="Cetak Struk">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                            Belum ada data pembayaran.
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot class="bg-gray-50 border-t font-bold text-gray-700">
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-right">Total Terbayar:</td>
                                        <td class="px-4 py-3 text-right text-emerald-600">Rp <?php echo e(number_format($calonSantri->total_sudah_bayar, 0, ',', '.')); ?></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/adminpondok/ppdb/pendaftar/payment.blade.php ENDPATH**/ ?>