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

    <div class="min-h-screen bg-gray-50 pb-32">
        
        
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="<?php echo e(route('orangtua.tagihan.index')); ?>" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Detail Tagihan</h1>
            </div>
        </div>

        
        <div class="px-5 -mt-16 relative z-20">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                
                
                <div class="p-6 border-b border-gray-50 text-center relative bg-white">
                    
                    <div class="absolute top-4 right-4">
                        <?php if($tagihan->status == 'paid'): ?>
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase tracking-wider border border-emerald-200">Lunas</span>
                        <?php elseif($tagihan->status == 'partial'): ?>
                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded-full uppercase tracking-wider border border-yellow-200">Dicicil</span>
                        <?php else: ?>
                            <span class="px-2.5 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-full uppercase tracking-wider border border-red-100">Belum Lunas</span>
                        <?php endif; ?>
                    </div>

                    <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-2 mt-4">Total Tagihan</p>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">
                        Rp <?php echo e(number_format($tagihan->nominal_tagihan, 0, ',', '.')); ?>

                    </h2>
                    <p class="text-xs text-gray-400 mt-2 font-mono bg-gray-50 inline-block px-2 py-1 rounded">
                        #<?php echo e($tagihan->invoice_number); ?>

                    </p>
                </div>

                
                <div class="bg-gray-50/80 p-5 grid grid-cols-2 gap-y-4 gap-x-2 text-sm border-b border-gray-100">
                    <div>
                        <p class="text-gray-400 text-[10px] uppercase font-bold">Nama Santri</p>
                        <p class="font-bold text-gray-800 truncate"><?php echo e($tagihan->santri->full_name); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-400 text-[10px] uppercase font-bold">Jatuh Tempo</p>
                        <p class="font-bold text-gray-800"><?php echo e(\Carbon\Carbon::parse($tagihan->due_date)->format('d M Y')); ?></p>
                    </div>
                    <div class="col-span-2 pt-2 border-t border-gray-200/50">
                        <p class="text-gray-400 text-[10px] uppercase font-bold">Kategori Pembayaran</p>
                        <p class="font-bold text-gray-800"><?php echo e($tagihan->jenisPembayaran->name); ?></p>
                        <?php if($tagihan->periode_bulan): ?>
                            <p class="text-xs text-emerald-600 font-medium mt-0.5">
                                Periode: <?php echo e(\Carbon\Carbon::create(null, $tagihan->periode_bulan, 1)->format('F')); ?> <?php echo e($tagihan->periode_tahun); ?>

                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="p-5">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-medium">Rp <?php echo e(number_format($tagihan->nominal_asli, 0, ',', '.')); ?></span>
                        </div>
                        <?php if($tagihan->nominal_keringanan > 0): ?>
                            <div class="flex justify-between items-center text-sm text-green-600">
                                <span>Keringanan / Beasiswa</span>
                                <span>- Rp <?php echo e(number_format($tagihan->nominal_keringanan, 0, ',', '.')); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                            $totalSisa = $tagihan->tagihanDetails->sum('sisa_tagihan_item');
                            $totalTerbayar = $tagihan->nominal_tagihan - $totalSisa;
                        ?>

                        <?php if($totalTerbayar > 0): ?>
                            <div class="flex justify-between items-center text-sm text-emerald-600">
                                <span>Sudah Dibayar</span>
                                <span>- Rp <?php echo e(number_format($totalTerbayar, 0, ',', '.')); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="flex justify-between items-center pt-3 mt-3 border-t border-dashed border-gray-200">
                            <span class="text-sm font-bold text-gray-800">Sisa Tagihan</span>
                            <span class="text-xl font-bold text-red-600">
                                Rp <?php echo e(number_format($totalSisa, 0, ',', '.')); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="px-5 mt-8">
            <h3 class="text-gray-800 font-bold text-base mb-4 ml-1">Riwayat Transaksi</h3>
            
            <div class="space-y-3">
                <?php if(isset($tagihan->transaksis) && $tagihan->transaksis->count() > 0): ?>
                    <?php $__currentLoopData = $tagihan->transaksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-0.5"><?php echo e($trx->created_at->format('d M Y H:i')); ?></p>
                                    <p class="text-xs font-bold text-gray-700 capitalize"><?php echo e(str_replace('_', ' ', $trx->metode_pembayaran)); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-emerald-600">+ <?php echo e(number_format($trx->total_bayar, 0, ',', '.')); ?></p>
                                <?php if($trx->status_verifikasi == 'verified'): ?>
                                    <span class="text-[10px] text-emerald-500">Berhasil</span>
                                <?php elseif($trx->status_verifikasi == 'pending'): ?>
                                    <span class="text-[10px] text-yellow-500">Menunggu</span>
                                <?php else: ?>
                                    <span class="text-[10px] text-red-500">Gagal</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center py-8">
                        <p class="text-gray-400 text-xs italic">Belum ada riwayat pembayaran untuk tagihan ini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    
    <?php if($totalSisa > 0): ?>
        <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-100 p-4 pb-6 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] z-50">
            <div class="max-w-3xl mx-auto flex items-center justify-between gap-4">
                <div class="hidden sm:block">
                    <p class="text-[10px] text-gray-400 uppercase font-bold">Total Pembayaran</p>
                    <p class="text-lg font-bold text-gray-900">Rp <?php echo e(number_format($totalSisa, 0, ',', '.')); ?></p>
                </div>
                <a href="<?php echo e(route('orangtua.tagihan.pilih-metode', $tagihan->id)); ?>" class="flex-1 sm:flex-none bg-emerald-600 text-white font-bold text-sm text-center py-3.5 px-6 rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition active:scale-[0.98] flex items-center justify-center gap-2">
                    <span>Bayar Sekarang</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </a>
            </div>
        </div>
    <?php endif; ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/orangtua/tagihan/show.blade.php ENDPATH**/ ?>