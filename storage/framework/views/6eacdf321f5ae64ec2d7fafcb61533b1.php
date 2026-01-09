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
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('adminpondok.ppdb.distribusi.index')); ?>" class="bg-white p-2 rounded-full shadow-sm hover:bg-gray-50 border border-gray-200 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800"><?php echo e($title); ?></h2>
                    <p class="text-sm text-gray-500">Tahun Ajaran <?php echo e($setting->tahun_ajaran); ?></p>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                
                <div class="lg:col-span-2 space-y-6">
                    
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="font-bold text-gray-700">Riwayat Pengeluaran Dana</h3>
                            <span class="px-3 py-1 bg-white border rounded-full text-xs font-medium text-gray-500 shadow-sm">
                                <?php echo e($riwayat->count()); ?> Transaksi
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                                    <tr>
                                        <th class="px-6 py-3">Tanggal</th>
                                        <th class="px-6 py-3">Keterangan</th>
                                        <th class="px-6 py-3">Jenis</th>
                                        <th class="px-6 py-3 text-right">Nominal</th>
                                        <th class="px-6 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                            <?php echo e(\Carbon\Carbon::parse($item->tanggal)->format('d M Y')); ?>

                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-800"><?php echo e($item->keterangan); ?></p>
                                            <p class="text-xs text-gray-400">Oleh: <?php echo e($item->user->name ?? '-'); ?></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if($item->jenis == 'setoran'): ?>
                                                <span class="px-2 py-1 rounded text-xs font-bold bg-blue-100 text-blue-700">SETORAN</span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 rounded text-xs font-bold bg-orange-100 text-orange-700">BELANJA</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-red-600">
                                            - Rp <?php echo e(number_format($item->nominal, 0, ',', '.')); ?>

                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="<?php echo e(route('adminpondok.ppdb.distribusi.print', $item->id)); ?>" target="_blank" class="text-gray-400 hover:text-gray-600" title="Cetak Bukti">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                                            Belum ada transaksi pengeluaran/setoran untuk pos ini.
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
                <div class="space-y-6">

                    
                    <div class="bg-emerald-600 rounded-xl shadow-lg text-white p-6 relative overflow-hidden">
                        <div class="relative z-10">
                            <p class="text-emerald-100 text-sm font-medium mb-1">Sisa Saldo (Cash on Hand)</p>
                            <h3 class="text-3xl font-bold">Rp <?php echo e(number_format($saldo, 0, ',', '.')); ?></h3>
                            
                            <div class="mt-4 pt-4 border-t border-emerald-500/50 flex justify-between text-sm">
                                <div>
                                    <span class="block text-emerald-200 text-xs">Total Masuk</span>
                                    <span class="font-semibold">Rp <?php echo e(number_format($totalMasuk, 0, ',', '.')); ?></span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-emerald-200 text-xs">Total Keluar</span>
                                    <span class="font-semibold">Rp <?php echo e(number_format($totalKeluar, 0, ',', '.')); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                    </div>

                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Input Transaksi Baru
                        </h3>
                        
                        <form action="<?php echo e(route('adminpondok.ppdb.distribusi.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="ppdb_setting_id" value="<?php echo e($setting->id); ?>">
                            <input type="hidden" name="kategori" value="<?php echo e($kategori); ?>">

                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Transaksi</label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="jenis" value="setoran" class="text-emerald-600 focus:ring-emerald-500" <?php echo e($kategori != 'panitia' ? 'checked' : ''); ?>>
                                        <span class="text-sm font-medium">Setoran Dana</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="jenis" value="belanja" class="text-emerald-600 focus:ring-emerald-500" <?php echo e($kategori == 'panitia' ? 'checked' : ''); ?>>
                                        <span class="text-sm font-medium">Belanja Barang</span>
                                    </label>
                                </div>
                            </div>

                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Nominal (Rp)</label>
                                <input type="number" name="nominal" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="0" required max="<?php echo e($saldo); ?>">
                                <p class="text-xs text-red-500 mt-1">* Maksimal Rp <?php echo e(number_format($saldo, 0, ',', '.')); ?></p>
                            </div>

                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal</label>
                                <input type="date" name="tanggal" value="<?php echo e(date('Y-m-d')); ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            </div>

                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Keterangan / Keperluan</label>
                                <textarea name="keterangan" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Contoh: Setoran ke Bendahara Yayasan Tahap 1..." required></textarea>
                            </div>

                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Bukti Foto / Nota (Opsional)</label>
                                <input type="file" name="bukti_foto" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            </div>

                            
                            <div class="border-t pt-4 mt-4">
                                <label class="block text-xs font-bold text-gray-700 mb-2">
                                    Lampiran Santri (Opsional)
                                    <span class="block text-gray-400 font-normal">Pilih santri yang uangnya disetorkan dalam transaksi ini.</span>
                                </label>
                                
                                <div class="h-40 overflow-y-auto border rounded-md p-2 bg-gray-50 space-y-2">
                                    <?php $__currentLoopData = $listSantri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-100 p-1 rounded">
                                        <input type="checkbox" name="list_santri_id[]" value="<?php echo e($s->id); ?>" class="rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                        <div class="text-xs">
                                            <span class="font-bold text-gray-700"><?php echo e($s->full_name); ?></span>
                                            <span class="text-gray-400">(<?php echo e($s->jenjang); ?>)</span>
                                        </div>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <?php if($listSantri->isEmpty()): ?>
                                        <p class="text-xs text-center text-gray-400 py-4">Belum ada data pembayaran masuk.</p>
                                    <?php endif; ?>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1">*Nama yang dicentang akan muncul di lampiran PDF.</p>
                            </div>

                            <button type="submit" class="w-full bg-gray-900 text-white py-2 rounded-lg font-bold hover:bg-black transition">
                                Simpan Transaksi
                            </button>
                        </form>
                    </div>

                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wide border-b pb-2">Rincian Sumber Dana</h3>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $breakdownMasuk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namaBiaya => $nilai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($nilai > 0): ?>
                                <a href="<?php echo e(route('adminpondok.ppdb.distribusi.detail_item', ['kategori' => $kategori, 'nama_biaya' => $namaBiaya, 'gelombang_id' => $setting->id])); ?>" 
   class="flex justify-between items-center group cursor-pointer hover:bg-gray-50 p-2 -mx-2 rounded transition">
    <div class="flex items-center gap-2">
        
        <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-sm text-gray-600 group-hover:text-emerald-700 font-medium"><?php echo e($namaBiaya); ?></span>
    </div>
    <span class="text-sm font-bold text-gray-800 group-hover:text-emerald-700">
        Rp <?php echo e(number_format($nilai, 0, ',', '.')); ?>

    </span>
</a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <?php if(count($breakdownMasuk) == 0): ?>
                                <p class="text-sm text-gray-400 italic">Belum ada pemasukan.</p>
                            <?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/adminpondok/ppdb/distribusi/show.blade.php ENDPATH**/ ?>