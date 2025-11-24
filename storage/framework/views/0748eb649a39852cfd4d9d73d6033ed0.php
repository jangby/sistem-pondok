

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto p-6">
    <form action="<?php echo e(route('sekolah.superadmin.perpustakaan.sirkulasi.kembali.process', $peminjaman->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Konfirmasi Pengembalian</h2>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="border-r border-gray-200 pr-4">
                    <h3 class="text-lg font-semibold mb-4 text-blue-600">Info Peminjaman</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-500 text-sm block">Peminjam</span>
                            <span class="font-medium text-lg"><?php echo e($peminjaman->santri->name); ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm block">Buku</span>
                            <span class="font-medium"><?php echo e($peminjaman->buku->judul); ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm block">Tgl Wajib Kembali</span>
                            <span class="font-bold <?php echo e($terlambatHari > 0 ? 'text-red-600' : 'text-green-600'); ?>">
                                <?php echo e($peminjaman->tgl_wajib_kembali->format('d M Y')); ?>

                            </span>
                        </div>
                        <?php if($terlambatHari > 0): ?>
                        <div class="bg-red-50 p-3 rounded border border-red-200 mt-2">
                            <p class="text-red-800 font-bold">TERLAMBAT: <?php echo e($terlambatHari); ?> Hari</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4 text-orange-600">Cek Kondisi & Denda</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Kondisi Buku</label>
                        <select name="kondisi_kembali" id="kondisi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="baik">Baik (Normal)</option>
                            <option value="rusak_ringan">Rusak Ringan (Robek/Coret)</option>
                            <option value="rusak_berat">Rusak Berat (Basah/Hancur)</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Denda Keterlambatan (Rp)</label>
                        <input type="number" name="denda_keterlambatan" value="<?php echo e($dendaKeterlambatan); ?>" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Denda Kerusakan / Ganti Rugi (Rp)</label>
                        <input type="number" name="denda_kerusakan" value="0" min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">Isi manual sesuai tingkat kerusakan.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700">
                    SIMPAN PENGEMBALIAN
                </button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/superadmin/perpus/sirkulasi/return-form.blade.php ENDPATH**/ ?>