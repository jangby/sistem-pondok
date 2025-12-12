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

    <div class="min-h-screen bg-gray-50 pb-24 relative">
        
        
        <div class="h-48 bg-emerald-600 rounded-b-[40px]">
            <div class="flex justify-between items-start p-6 text-white">
                <a href="<?php echo e(route('pengurus.santri.index')); ?>" class="bg-white/20 p-2 rounded-xl backdrop-blur-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <a href="<?php echo e(route('pengurus.santri.edit', $santri->id)); ?>" class="bg-white/20 px-4 py-2 rounded-xl backdrop-blur-md text-xs font-bold hover:bg-white/30 transition">
                    Edit Data
                </a>
            </div>
        </div>

        
        <div class="px-6 mt-6">
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-4 text-white shadow-lg shadow-emerald-200 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10"></div>
                
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest opacity-80">Kartu Santri</p>
                        <h2 class="text-lg font-bold mt-1"><?php echo e($santri->full_name ?? '-'); ?></h2>
                        <p class="text-xs opacity-90 font-mono mt-0.5"><?php echo e($santri->nis ?? '-'); ?></p>
                    </div>
                    
                    <div class="bg-white p-1.5 rounded-lg shadow-sm">
                         <?php if($santri->qrcode_token): ?>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo e($santri->qrcode_token); ?>" class="w-14 h-14">
                        <?php else: ?>
                            <div class="w-14 h-14 bg-gray-100 flex items-center justify-center text-xs text-gray-400 text-center">No QR</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-6 flex justify-between items-end">
                    <div>
                        <p class="text-[9px] opacity-70 uppercase">RFID UID</p>
                        <p class="font-mono text-sm font-bold tracking-wider"><?php echo e($santri->rfid_uid ?? 'BELUM ADA KARTU'); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] opacity-70">Status</p>
                        <p class="text-xs font-bold flex items-center gap-1 justify-end">
                            <?php if($santri->status == 'active'): ?>
                                <span class="w-2 h-2 rounded-full bg-green-300 animate-pulse"></span> Aktif
                            <?php else: ?>
                                <span class="w-2 h-2 rounded-full bg-red-300"></span> Nonaktif
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="px-6 mt-6 space-y-4">
            
            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Informasi Pribadi
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Kelas</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->kelas->nama_kelas ?? 'Belum ditentukan'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Tahun Masuk</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->tahun_masuk ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Jenis Kelamin</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->jenis_kelamin ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Tempat/Tgl Lahir</span>
                        <span class="font-medium text-gray-800">
                            <?php echo e($santri->tempat_lahir ?? '-'); ?>, 
                            <?php echo e($santri->tanggal_lahir ? $santri->tanggal_lahir->format('d M Y') : '-'); ?>

                        </span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Golongan Darah</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->golongan_darah ?? '-'); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Alamat & Domisili
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="bg-gray-50 p-3 rounded-xl text-gray-700 text-xs mb-3">
                        <?php echo e($santri->alamat ?? 'Alamat jalan belum diisi.'); ?>

                    </div>
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
                            <span class="text-xs text-gray-400 block">Desa/Kel</span>
                            <span class="font-medium text-gray-800"><?php echo e($santri->desa ?? '-'); ?></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">Kecamatan</span>
                            <span class="font-medium text-gray-800"><?php echo e($santri->kecamatan ?? '-'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-camera mr-2"></i> Pendaftaran Wajah (Face Recognition)
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 p-4 rounded-lg bg-gray-50">
                <div id="my_camera" class="w-full h-64 bg-black rounded-lg mb-4 overflow-hidden shadow-inner"></div>
                
                <div class="flex space-x-2">
                    <button type="button" onclick="setupCamera()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        <i class="fas fa-power-off mr-1"></i> Buka Kamera
                    </button>
                    <button type="button" onclick="takeSnapshot()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition" id="btn-snap" style="display:none;">
                        <i class="fas fa-camera mr-1"></i> Ambil Foto
                    </button>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center p-4">
                <div id="results" class="w-full h-48 flex items-center justify-center bg-gray-100 border border-gray-200 rounded-lg mb-4 text-gray-400">
                    <span class="text-sm">Preview foto akan muncul di sini</span>
                </div>

                <div class="w-full">
                    <div id="status-message" class="hidden mb-4 p-3 rounded text-sm text-center"></div>
                    
                    <button type="button" onclick="saveFace()" id="btn-save" class="w-full px-4 py-3 bg-indigo-600 text-white font-bold rounded shadow hover:bg-indigo-700 disabled:opacity-50 transition" disabled>
                        <i class="fas fa-save mr-2"></i> SIMPAN WAJAH KE DATABASE
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    let tempImage = '';

    function setupCamera() {
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera');
        
        // Tampilkan tombol snap, sembunyikan tombol buka kamera (opsional)
        document.getElementById('btn-snap').style.display = 'inline-block';
    }

    function takeSnapshot() {
        Webcam.snap(function(data_uri) {
            tempImage = data_uri;
            document.getElementById('results').innerHTML = 
                '<img src="'+data_uri+'" class="rounded shadow-sm border border-gray-300" style="max-height: 100%;"/>';
            
            // Aktifkan tombol simpan
            document.getElementById('btn-save').disabled = false;
        });
    }

    function saveFace() {
        if (!tempImage) return alert('Silakan ambil foto terlebih dahulu!');

        let btn = document.getElementById('btn-save');
        let statusDiv = document.getElementById('status-message');
        
        // UI Loading State
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses AI...';
        btn.disabled = true;
        statusDiv.className = 'hidden mb-4 p-3 rounded text-sm text-center'; // Reset status

        $.ajax({
            url: "<?php echo e(route('pengurus.santri.store_face')); ?>",
            type: "POST",
            data: {
                _token: "<?php echo e(csrf_token()); ?>",
                santri_id: "<?php echo e($santri->id); ?>", // ID Santri dari Controller
                image: tempImage
            },
            success: function(response) {
                if (response.status == 'success') {
                    statusDiv.innerHTML = response.message;
                    statusDiv.className = 'mb-4 p-3 rounded text-sm text-center bg-green-100 text-green-700 border border-green-200';
                    btn.innerHTML = '<i class="fas fa-check mr-2"></i> Tersimpan';
                } else {
                    statusDiv.innerHTML = response.message;
                    statusDiv.className = 'mb-4 p-3 rounded text-sm text-center bg-red-100 text-red-700 border border-red-200';
                    btn.innerHTML = '<i class="fas fa-save mr-2"></i> Coba Lagi';
                    btn.disabled = false;
                }
                // Tampilkan status div
                statusDiv.classList.remove('hidden');
            },
            error: function(xhr) {
                console.log(xhr);
                statusDiv.innerHTML = "Terjadi kesalahan server/koneksi.";
                statusDiv.className = 'mb-4 p-3 rounded text-sm text-center bg-red-100 text-red-700 border border-red-200';
                statusDiv.classList.remove('hidden');
                
                btn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Wajah';
                btn.disabled = false;
            }
        });
    }
</script>

            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Data Ayah
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Nama Lengkap</span>
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
                        <span class="text-gray-500">Pendidikan</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->pendidikan_ayah ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Pekerjaan</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->pekerjaan_ayah ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Penghasilan</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->penghasilan_ayah ?? '-'); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Data Ibu
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Nama Lengkap</span>
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
                        <span class="text-gray-500">Pendidikan</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->pendidikan_ibu ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Pekerjaan</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->pekerjaan_ibu ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 pb-2">
                        <span class="text-gray-500">Penghasilan</span>
                        <span class="font-medium text-gray-800"><?php echo e($santri->penghasilan_ibu ?? '-'); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Kesehatan
                </h3>
                <p class="text-sm text-gray-600 bg-red-50 p-3 rounded-xl border border-red-100">
                    <?php echo e($santri->riwayat_penyakit ?? 'Tidak ada riwayat penyakit khusus.'); ?>

                </p>
            </div>

            
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Akun Aplikasi Wali
                </h3>
                <div class="text-sm">
                    
                    <?php if($santri->orangTua): ?>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                <?php echo e(substr($santri->orangTua->name, 0, 1)); ?>

                            </div>
                            <div>
                                <p class="font-bold text-gray-800"><?php echo e($santri->orangTua->name); ?></p>
                                <p class="text-gray-500 text-xs"><?php echo e($santri->orangTua->phone); ?></p>
                            </div>
                        </div>
                        
                        <a href="https://wa.me/<?php echo e(preg_replace('/^0/', '62', $santri->orangTua->phone)); ?>" target="_blank" class="block text-center bg-emerald-50 text-emerald-700 py-2.5 rounded-xl font-bold text-xs hover:bg-emerald-100 transition border border-emerald-100 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-8.68-2.031-.967-.272-.297-.471-.446-.966-.595-.496-.149-1.711.149-1.909.248-.198.099-1.091 1.338-1.289 1.586-.198.248-.397.297-.694.149-.297-.149-1.254-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            Hubungi via WhatsApp
                        </a>
                    <?php else: ?>
                        
                        <div class="text-center py-4 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            <p class="text-gray-500 mb-1">Belum ada akun wali terhubung.</p>
                            <p class="text-xs text-gray-400">Silakan edit data untuk menautkan akun.</p>
                        </div>
                    <?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/santri/show.blade.php ENDPATH**/ ?>