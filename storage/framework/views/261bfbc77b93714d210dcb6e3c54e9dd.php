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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pendaftar: <?php echo e($calon->full_name); ?></h2>
            <div class="space-x-2">
                <?php if($calon->status_pendaftaran != 'diterima'): ?>
                    <form action="<?php echo e(route('adminpondok.ppdb.pendaftar.approve', $calon->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Yakin terima santri ini? Data akan dipindahkan ke database utama.')">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-md font-bold hover:bg-emerald-500 shadow">
                            ✓ TERIMA SANTRI
                        </button>
                    </form>
                    <form action="<?php echo e(route('adminpondok.ppdb.pendaftar.reject', $calon->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Tolak pendaftaran?')">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md font-bold hover:bg-red-500 shadow">
                            X TOLAK
                        </button>
                    </form>
                <?php else: ?>
                    <span class="bg-emerald-100 text-emerald-800 px-4 py-2 rounded-md font-bold border border-emerald-300">
                        SUDAH DITERIMA / AKTIF
                    </span>
                <?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            
            <div class="bg-white p-6 rounded-lg shadow flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-700">Status Pembayaran</h3>
                    <p class="text-sm text-gray-500">Total Tagihan: Rp <?php echo e(number_format($calon->total_biaya, 0, ',', '.')); ?></p>
                    <p class="text-sm text-gray-500">Sudah Bayar: <span class="font-bold text-gray-800">Rp <?php echo e(number_format($calon->total_sudah_bayar, 0, ',', '.')); ?></span></p>
                </div>
                <div>
                    <?php if($calon->status_pembayaran == 'lunas'): ?>
                        <span class="text-xl font-bold text-emerald-600 border-2 border-emerald-600 px-4 py-1 rounded uppercase">LUNAS</span>
                    <?php else: ?>
                        <form action="<?php echo e(route('adminpondok.ppdb.pendaftar.payment.confirm', $calon->id)); ?>" method="POST" onsubmit="return confirm('Konfirmasi lunas manual?')">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 text-sm">
                                Konfirmasi Lunas Manual
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-2 bg-white p-6 rounded-lg shadow">
                    <h3 class="font-bold border-b pb-2 mb-4">Biodata Santri</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><span class="text-gray-500">NIK:</span><br><?php echo e($calon->nik); ?></div>
                        <div><span class="text-gray-500">KK:</span><br><?php echo e($calon->no_kk); ?></div>
                        <div><span class="text-gray-500">TTL:</span><br><?php echo e($calon->tempat_lahir); ?>, <?php echo e($calon->tanggal_lahir->format('d-m-Y')); ?></div>
                        <div><span class="text-gray-500">Alamat:</span><br><?php echo e($calon->alamat); ?>, <?php echo e($calon->desa); ?>, <?php echo e($calon->kecamatan); ?></div>
                        <div class="col-span-2"><span class="text-gray-500">Sekolah Asal:</span><br><?php echo e($calon->sekolah_asal); ?></div>
                        <div class="col-span-2 mt-2 font-bold border-t pt-2">Data Orang Tua</div>
                        <div><span class="text-gray-500">Ayah:</span><br><?php echo e($calon->nama_ayah); ?> (<?php echo e($calon->no_hp_ayah); ?>)</div>
                        <div><span class="text-gray-500">Ibu:</span><br><?php echo e($calon->nama_ibu); ?> (<?php echo e($calon->no_hp_ibu); ?>)</div>
                    </div>
                </div>

                
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-bold border-b pb-2 mb-4">Berkas Upload</h3>
                    <ul class="space-y-3 text-sm">
                        <?php
                            $files = [
                                'foto_santri' => 'Pas Foto',
                                'file_kk' => 'Kartu Keluarga',
                                'file_akta' => 'Akta Kelahiran',
                                'file_ijazah' => 'Ijazah',
                                'file_skl' => 'SKL',
                                'file_kip' => 'KIP',
                            ];
                        ?>

                        <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="flex justify-between items-center">
                                <span><?php echo e($label); ?></span>
                                <?php if($calon->$field): ?>
                                    <a href="<?php echo e(asset('storage/' . $calon->$field)); ?>" target="_blank" class="text-blue-600 hover:underline font-bold">
                                        Lihat
                                    </a>
                                <?php else: ?>
                                    <span class="text-red-400 text-xs italic">Belum ada</span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/adminpondok/ppdb/pendaftar/show.blade.php ENDPATH**/ ?>