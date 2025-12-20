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

    
    <?php
        // 1. Hitung Persentase Kelengkapan
        $fields = [
            'full_name', 'nis', 'nik', 'no_kk', 'tempat_lahir', 'tanggal_lahir', 
            'nama_ayah', 'nama_ibu', 'alamat', 'asrama_id'
        ];
        $filled = 0;
        foreach ($fields as $field) {
            if (!empty($santri->$field)) $filled++;
        }
        $persentase = count($fields) > 0 ? round(($filled / count($fields)) * 100) : 0;
        
        // 2. Logic Jenis Kelamin (Support 'L', 'male', 'laki-laki')
        $jkDb = strtolower($santri->jenis_kelamin); // ubah ke huruf kecil semua dulu
        $isLaki = in_array($jkDb, ['l', 'male', 'laki-laki', 'laki']);
        $labelJk = $isLaki ? 'Laki-laki' : 'Perempuan';
    ?>

    <div class="min-h-screen bg-gray-50 pb-24 relative">
        
        
        <div class="h-48 bg-emerald-600 rounded-b-[40px]">
            <div class="flex justify-between items-start p-6 text-white">
                <a href="<?php echo e(route('pengurus.santri.index')); ?>" class="bg-white/20 p-2 rounded-xl backdrop-blur-md hover:bg-white/30 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <a href="<?php echo e(route('pengurus.santri.edit', $santri->id)); ?>" class="bg-white/20 px-4 py-2 rounded-xl backdrop-blur-md text-xs font-bold hover:bg-white/30 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Edit Data
                </a>
            </div>
        </div>

        
        <div class="px-6 -mt-20">
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-5 text-white shadow-xl shadow-emerald-200/50 relative overflow-hidden">
                
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10"></div>
                
                <div class="flex justify-between items-start relative z-10">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest opacity-80 mb-1">Kartu Identitas Santri</p>
                        <h2 class="text-xl font-bold leading-tight"><?php echo e($santri->full_name ?? '-'); ?></h2>
                        <p class="text-sm opacity-90 font-mono mt-1"><?php echo e($santri->nis ?? '-'); ?></p>
                        
                        
                        <div class="mt-3 flex items-center gap-2">
                            <?php if($santri->status == 'active'): ?>
                                <span class="px-2 py-1 bg-white/20 backdrop-blur rounded text-[10px] font-bold border border-white/20 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-300 animate-pulse"></span> Aktif
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-red-500/20 backdrop-blur rounded text-[10px] font-bold border border-red-200/20 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-300"></span> Nonaktif
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    
<div class="flex flex-col items-center gap-2 shrink-0">
    
    <div class="bg-white p-1.5 rounded-lg shadow-sm">
        <?php if($santri->qrcode_token): ?>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo e($santri->qrcode_token); ?>" class="w-16 h-16">
        <?php else: ?>
            <div class="w-16 h-16 bg-gray-100 flex items-center justify-center text-[10px] text-gray-400 text-center font-bold">
                No QR
            </div>
        <?php endif; ?>
    </div>

    
    <form action="<?php echo e(route('pengurus.santri.regenerate-qr', $santri->id)); ?>" method="POST" id="form-regen-qr">
        <?php echo csrf_field(); ?>
        
        <button type="button" onclick="confirmRegenerate()" 
            class="px-2 py-1 bg-white/20 hover:bg-white/30 text-white rounded-md text-[10px] font-bold border border-white/20 backdrop-blur transition flex items-center gap-1 shadow-sm group">
            <svg class="w-3 h-3 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            Reset QR
        </button>
    </form>
</div>
                </div>

                
                <div class="mt-5 pt-4 border-t border-white/20">
                    <div class="flex justify-between items-end mb-3">
                        <div>
                            <p class="text-[9px] opacity-70 uppercase">RFID UID</p>
                            <p class="font-mono text-sm font-bold tracking-wider"><?php echo e($santri->rfid_uid ?? 'BELUM ADA KARTU'); ?></p>
                        </div>
                        <div class="text-right">
                             <p class="text-[9px] opacity-70 uppercase">Kelengkapan Data</p>
                             <p class="font-bold text-xs"><?php echo e($persentase); ?>%</p>
                        </div>
                    </div>
                    
                    
                    <div class="w-full bg-black/20 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-white h-1.5 rounded-full transition-all duration-500" style="width: <?php echo e($persentase); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="px-6 mt-6 space-y-4">
            
            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Biodata Santri
                </h3>
                <div class="space-y-3 text-sm">
                    
                    <?php if(empty($santri->nik)): ?>
                        <div class="bg-red-50 text-red-600 p-3 rounded-xl text-xs flex items-start gap-2 mb-3">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <div>
                                <strong>NIK Belum Diisi!</strong><br>
                                Data NIK wajib diisi untuk keperluan sinkronisasi PPDB dan Data Dapodik.
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Kelas</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->kelas->nama_kelas ?? 'Belum ditentukan'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Asrama</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->asrama->nama_asrama ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">NIK</span>
                        <span class="font-bold <?php echo e(empty($santri->nik) ? 'text-red-500' : 'text-gray-800'); ?>">
                            <?php echo e($santri->nik ?? 'KOSONG'); ?>

                        </span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">No. KK</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->no_kk ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Jenis Kelamin</span>
                        <span class="font-medium text-gray-800"><?php echo e($labelJk); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Tempat, Tgl Lahir</span>
                        <span class="font-medium text-gray-800 text-right">
                            <?php echo e($santri->tempat_lahir ?? '-'); ?>, 
                            <?php echo e($santri->tanggal_lahir ? $santri->tanggal_lahir->translatedFormat('d F Y') : '-'); ?>

                        </span>
                    </div>
                </div>
            </div>

            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Alamat Domisili
                </h3>
                <div class="text-sm">
                    <p class="text-gray-700 bg-gray-50 p-3 rounded-xl border border-gray-100 mb-3 leading-relaxed">
                        <?php echo e($santri->alamat ?? 'Alamat jalan belum diisi.'); ?>

                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-gray-400 block">RT / RW</span>
                            <span class="font-medium text-gray-800"><?php echo e($santri->rt ?? '-'); ?> / <?php echo e($santri->rw ?? '-'); ?></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Kode Pos</span>
                            <span class="font-medium text-gray-800"><?php echo e($santri->kode_pos ?? '-'); ?></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Desa/Kelurahan</span>
                            <span class="font-medium text-gray-800 truncate"><?php echo e($santri->desa ?? '-'); ?></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Kecamatan</span>
                            <span class="font-medium text-gray-800 truncate"><?php echo e($santri->kecamatan ?? '-'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100" x-data="{ activeTab: 'ayah' }">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-2">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Orang Tua
                    </h3>
                    
                    
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <button @click="activeTab = 'ayah'" :class="activeTab === 'ayah' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="px-3 py-1 text-[10px] font-bold rounded-md transition-all">AYAH</button>
                        <button @click="activeTab = 'ibu'" :class="activeTab === 'ibu' ? 'bg-white text-pink-500 shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="px-3 py-1 text-[10px] font-bold rounded-md transition-all">IBU</button>
                    </div>
                </div>

                
                <div x-show="activeTab === 'ayah'" class="space-y-3 text-sm animate-fade-in">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Nama Ayah</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->nama_ayah ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">NIK</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->nik_ayah ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Tahun Lahir</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->thn_lahir_ayah ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Pekerjaan</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->pekerjaan_ayah ?? '-'); ?></span>
                    </div>
                </div>

                
                <div x-show="activeTab === 'ibu'" style="display: none;" class="space-y-3 text-sm animate-fade-in">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Nama Ibu</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->nama_ibu ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">NIK</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->nik_ibu ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Tahun Lahir</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->thn_lahir_ibu ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Pekerjaan</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->pekerjaan_ibu ?? '-'); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-10">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Kontak & Akun Wali
                </h3>
                
                <?php if($santri->orangTua): ?>
                    <div class="flex items-center gap-3 mb-4 bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold shrink-0">
                            <?php echo e(substr($santri->orangTua->name ?? 'W', 0, 1)); ?>

                        </div>
                        <div class="overflow-hidden">
                            <p class="font-bold text-gray-800 text-sm truncate"><?php echo e($santri->orangTua->name); ?></p>
                            <p class="text-gray-500 text-xs truncate"><?php echo e($santri->orangTua->phone); ?></p>
                        </div>
                    </div>

                    <a href="https://wa.me/<?php echo e(preg_replace('/^0/', '62', $santri->orangTua->phone)); ?>" target="_blank" class="flex items-center justify-center gap-2 w-full bg-emerald-50 text-emerald-600 py-3 rounded-xl font-bold text-sm hover:bg-emerald-100 transition border border-emerald-100">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-8.68-2.031-.967-.272-.297-.471-.446-.966-.595-.496-.149-1.711.149-1.909.248-.198.099-1.091 1.338-1.289 1.586-.198.248-.397.297-.694.149-.297-.149-1.254-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Hubungi via WhatsApp
                    </a>
                <?php else: ?>
                    <div class="text-center py-6 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-gray-500 font-medium mb-1">Akun Wali Belum Terhubung</p>
                        <a href="<?php echo e(route('pengurus.santri.edit', $santri->id)); ?>" class="text-blue-600 text-xs font-bold underline">Hubungkan Sekarang</a>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    
    <script>
        function confirmRegenerate() {
            // Cek apakah Swal terbaca
            if (typeof Swal === 'undefined') {
                // Fallback jika SweetAlert gagal load: Pakai konfirmasi biasa
                if (confirm("Reset Kode QR? Kode lama akan hangus. Lanjutkan?")) {
                    document.getElementById('form-regen-qr').submit();
                }
                return;
            }

            // Tampilkan SweetAlert
            Swal.fire({
                title: 'Reset Kode QR?',
                text: "Kode QR lama akan hangus dan tidak bisa digunakan absen lagi. Sistem akan membuat kode baru.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669', 
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Reset Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-regen-qr').submit();
                }
            });
        }
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/santri/show.blade.php ENDPATH**/ ?>