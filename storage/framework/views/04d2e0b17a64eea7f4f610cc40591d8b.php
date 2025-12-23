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
            <?php echo e(__('Dashboard Calon Santri')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        
            
            
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r shadow-sm flex justify-between items-start">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-emerald-700 font-bold">Berhasil!</p>
                            <p class="text-sm text-emerald-600"><?php echo e(session('success')); ?></p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div x-data="{ show: true }" x-show="show" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm flex justify-between items-start">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-bold">Gagal!</p>
                            <p class="text-sm text-red-600"><?php echo e(session('error')); ?></p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-red-400 hover:text-red-600">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            <?php endif; ?>

            
            <div class="bg-gradient-to-r from-emerald-700 to-teal-600 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
                <div class="absolute right-0 top-0 w-40 h-40 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-3xl"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-emerald-100 font-medium text-sm uppercase tracking-wider">Ahlan Wa Sahlan,</h3>
                        <h1 class="text-3xl font-bold mt-1"><?php echo e($calonSantri->nama_lengkap); ?></h1>
                        <div class="mt-3 flex items-center gap-3">
                            <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-mono backdrop-blur-sm border border-white/10">
                                <?php echo e($calonSantri->no_pendaftaran); ?>

                            </span>
                            <span class="text-sm text-emerald-100">
                                Gelombang: <?php echo e($calonSantri->ppdbSetting->nama_gelombang ?? '-'); ?>

                            </span>
                        </div>
                    </div>
                    
                    
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 border border-white/10 min-w-[140px] text-center">
                        <p class="text-xs text-emerald-200">Status Pendaftaran</p>
                        <?php if($calonSantri->status_pendaftaran == 'diterima'): ?>
                            <p class="text-lg font-bold text-white">DITERIMA 🎉</p>
                        <?php elseif($calonSantri->status_pendaftaran == 'menunggu_verifikasi'): ?>
                            <p class="text-lg font-bold text-yellow-300">VERIFIKASI</p>
                        <?php else: ?>
                            <p class="text-lg font-bold text-gray-200">DRAFT</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-semibold text-gray-600">Progres Pendaftaran</span>
                    <?php if($calonSantri->status_pendaftaran == 'diterima'): ?>
                        <span class="text-emerald-600 font-bold">100% Selesai</span>
                    <?php else: ?>
                        <span class="text-blue-600 font-bold">Proses Berjalan</span>
                    <?php endif; ?>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-1000" 
                         style="width: <?php echo e($calonSantri->status_pendaftaran == 'diterima' ? '100%' : ($calonSantri->status_pendaftaran == 'menunggu_verifikasi' ? '80%' : '30%')); ?>"></div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                    <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 text-xs font-bold">1</span>
                            Pembayaran (<?php echo e($calonSantri->jenjang); ?>)
                        </h3>
                        <?php if($calonSantri->status_pembayaran == 'lunas'): ?>
                            <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded-lg">LUNAS</span>
                        <?php else: ?>
                            <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2 py-1 rounded-lg">BELUM LUNAS</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-6 flex-grow flex flex-col">
                        
                        <div class="mb-6 bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Rincian Tagihan</p>
                            <ul class="space-y-2 text-sm text-gray-700">
                                <?php $__empty_1 = true; $__currentLoopData = $calonSantri->rincian_biaya; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <li class="flex justify-between">
                                        <span><?php echo e($item->nama_biaya); ?></span>
                                        <span class="font-mono font-medium">Rp <?php echo e(number_format($item->nominal, 0, ',', '.')); ?></span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <li class="text-center text-gray-400 italic">Biaya belum diatur oleh admin.</li>
                                <?php endif; ?>
                                <li class="border-t border-dashed border-gray-300 pt-2 flex justify-between font-bold text-gray-900 mt-2">
                                    <span>Total Total</span>
                                    <span>Rp <?php echo e(number_format($calonSantri->total_biaya, 0, ',', '.')); ?></span>
                                </li>
                            </ul>
                        </div>

                        <div class="text-center mb-6">
                            <p class="text-sm text-gray-500 mb-1">Sisa Yang Harus Dibayar</p>
                            <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                                Rp <?php echo e(number_format($calonSantri->sisa_tagihan, 0, ',', '.')); ?>

                            </h2>
                        </div>

                        
                        <div class="mb-6">
                            <div class="flex justify-between text-xs mb-1 text-gray-500">
                                <span>Terbayar: Rp <?php echo e(number_format($calonSantri->total_sudah_bayar, 0, ',', '.')); ?></span>
                                <span><?php echo e($calonSantri->persentase_pembayaran); ?>%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-3 rounded-full transition-all duration-1000" 
                                     style="width: <?php echo e($calonSantri->persentase_pembayaran); ?>%"></div>
                            </div>
                        </div>

                        
                        <?php if($calonSantri->sisa_tagihan > 0): ?>
                            <div class="mt-auto">
    <a href="<?php echo e(route('ppdb.payment')); ?>" class="w-full py-3 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        <?php echo e($calonSantri->total_sudah_bayar > 0 ? 'Bayar Cicilan Lagi' : 'Bayar Sekarang'); ?>

    </a>
    <p class="text-center text-xs text-gray-400 mt-3">
        *Pembayaran dapat dicicil beberapa kali.
    </p>
</div>
                        <?php else: ?>
                            <div class="text-center p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                                <p class="text-emerald-700 font-bold text-sm">Alhamdulillah, pembayaran telah lunas.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                    <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold">2</span>
                            Biodata & Berkas
                        </h3>
                        
                        <?php if($calonSantri->status_pendaftaran == 'diterima' || $calonSantri->status_pendaftaran == 'menunggu_verifikasi'): ?>
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded-lg">TERKIRIM</span>
                        <?php else: ?>
                            <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-1 rounded-lg">BELUM LENGKAP</span>
                        <?php endif; ?>
                    </div>

                    <div class="p-6 flex-grow">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="p-3 bg-blue-50 rounded-xl text-blue-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414.586l5.414 5.414a1 1 0 01.586 1.414V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Kelengkapan Data</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    Silakan lengkapi data diri santri, data orang tua, dan upload berkas pendukung (KK, Akta, dll).
                                </p>
                                <p class="text-xs text-blue-600 font-medium mt-2">
                                    💡 Data bisa disimpan sementara (dicicil) dan dilanjutkan nanti.
                                </p>
                            </div>
                        </div>

                        
                        <div class="mt-4">
                            <?php if($calonSantri->status_pendaftaran == 'diterima'): ?>
                                <button disabled class="w-full py-3 bg-gray-100 text-gray-400 font-bold rounded-xl cursor-not-allowed">
                                    Data Telah Divalidasi (Terkunci)
                                </button>
                            <?php else: ?>
                                <a href="<?php echo e(route('ppdb.biodata')); ?>" class="block w-full py-3 text-center bg-white border-2 border-gray-900 text-gray-900 hover:bg-gray-50 font-bold rounded-xl transition shadow-sm hover:shadow-md">
                                    <?php echo e($calonSantri->status_pendaftaran == 'draft' ? 'Mulai Isi Biodata' : 'Lanjutkan Pengisian / Edit'); ?>

                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

            
            <?php if($calonSantri->status_pendaftaran == 'diterima'): ?>
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 flex flex-col md:flex-row items-center gap-6">
                    <div class="shrink-0 p-4 bg-emerald-100 rounded-full text-emerald-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-emerald-900">Selamat! Anda Diterima</h3>
                        <p class="text-emerald-700 mt-1">
                            Berkas dan pembayaran Anda telah divalidasi oleh panitia. Silakan unduh kartu bukti pendaftaran di bawah ini dan bawa saat daftar ulang.
                        </p>
                        <button class="mt-4 px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg shadow-lg transition">
                            Download Bukti Penerimaan
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="text-center pt-8">
                <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center gap-2 text-gray-500 hover:text-emerald-600 transition font-medium">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    Bantuan WhatsApp Panitia
                </a>
            </div>

        </div>
    </div>

    
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek jika ada session sukses dari Controller
            <?php if(session('success')): ?>
                Swal.fire({
                    title: 'Berhasil!',
                    text: "<?php echo e(session('success')); ?>",
                    icon: 'success',
                    confirmButtonText: 'Mantap',
                    confirmButtonColor: '#10B981', // Warna Emerald
                    timer: 3000, // Otomatis tutup dalam 3 detik
                    timerProgressBar: true
                });
            <?php endif; ?>

            // Cek jika ada session error
            <?php if(session('error')): ?>
                Swal.fire({
                    title: 'Gagal!',
                    text: "<?php echo e(session('error')); ?>",
                    icon: 'error',
                    confirmButtonText: 'Cek Kembali',
                    confirmButtonColor: '#EF4444', // Warna Merah
                });
            <?php endif; ?>
        });
    </script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/ppdb/dashboard.blade.php ENDPATH**/ ?>