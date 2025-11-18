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

    <div class="min-h-screen bg-gray-50 pb-24">
        
        
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="<?php echo e(route('sekolah.guru.dashboard')); ?>" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Absensi Mengajar</h1>
            </div>
        </div>

        
        <div class="px-5 -mt-16 relative z-20">
            
            
            <?php if(session('error')): ?>
                <div class="mb-4 p-4 bg-red-50 text-red-600 border border-red-100 rounded-2xl text-xs font-bold flex items-start gap-2 shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>
            
            
            <?php if(session('success')): ?>
                <div class="mb-4 p-4 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl text-xs font-bold flex items-start gap-2 shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden relative">
                
                <div class="bg-gray-50 p-5 border-b border-gray-100">
                    <span class="inline-block px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-[10px] font-bold uppercase tracking-wider mb-2">
                        <?php echo e($jadwalPelajaran->kelas->nama_kelas); ?>

                    </span>
                    <h3 class="text-xl font-bold text-gray-800 leading-tight mb-1">
                        <?php echo e($jadwalPelajaran->mataPelajaran->nama_mapel); ?>

                    </h3>
                    <p class="text-xs text-gray-500 font-medium flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <?php echo e($jadwalPelajaran->hari); ?>, <?php echo e($jadwalPelajaran->jam_mulai); ?> - <?php echo e($jadwalPelajaran->jam_selesai); ?>

                    </p>
                </div>

                <div class="p-6">
                    
                    <?php if($absensiPelajaran): ?>
                        
                        
                        <div class="bg-green-50 border border-green-100 rounded-xl p-4 flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-green-800">Terkonfirmasi Hadir</h4>
                                <p class="text-[10px] text-green-600">
                                    Masuk pukul <?php echo e(\Carbon\Carbon::parse($absensiPelajaran->jam_guru_masuk_kelas)->format('H:i')); ?> WIB
                                </p>
                            </div>
                        </div>

                        
                        <form method="POST" action="<?php echo e(route('sekolah.guru.jadwal.absen.materi', $absensiPelajaran->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="mb-4">
                                <label for="materi_pembahasan" class="block text-xs font-bold text-gray-500 uppercase mb-2">
                                    Materi Pembahasan (Jurnal)
                                </label>
                                <textarea id="materi_pembahasan" name="materi_pembahasan" rows="3" 
                                          class="block w-full border-gray-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-300 bg-gray-50" 
                                          placeholder="Tulis ringkasan materi hari ini..."><?php echo e(old('materi_pembahasan', $absensiPelajaran->materi_pembahasan)); ?></textarea>
                                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('materi_pembahasan'),'class' => 'mt-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('materi_pembahasan')),'class' => 'mt-1']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-gray-700 transition shadow-sm">
                                    Simpan Jurnal
                                </button>
                            </div>
                        </form>

                        <hr class="my-6 border-gray-100 border-dashed">

                        
                        <a href="<?php echo e(route('sekolah.guru.absensi.siswa.index', $absensiPelajaran->id)); ?>" 
                           class="block w-full py-4 bg-emerald-600 text-white font-bold rounded-xl text-center shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition-transform flex items-center justify-center gap-2">
                            <span>Lanjut Absensi Siswa</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>

                    <?php else: ?>
                        
                        
                        <div class="text-center py-4">
                            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <p class="text-gray-500 text-sm px-4 mb-6">
                                Pastikan Anda sudah berada di dalam kelas. Sistem akan memverifikasi lokasi GPS Anda.
                            </p>

                            <form method="POST" action="<?php echo e(route('sekolah.guru.jadwal.absen')); ?>" id="form-absen-mengajar" class="hidden">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="jadwal_pelajaran_id" value="<?php echo e($jadwalPelajaran->id); ?>">
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                            </form>

                            <button id="btn-absen-mengajar"
                                    class="w-full py-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Mulai Mengajar</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <?php if(!$absensiPelajaran): ?>
    <script>
        const formAbsensi = document.getElementById('form-absen-mengajar');
        const inputLat = document.getElementById('latitude');
        const inputLon = document.getElementById('longitude');
        const btnAbsen = document.getElementById('btn-absen-mengajar');

        const handleAbsenMengajar = (event) => {
            // Visual Loading Awal
            Swal.fire({
                title: 'Mencari Lokasi...',
                text: 'Mohon izinkan akses GPS.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            if (!navigator.geolocation) {
                Swal.fire('Gagal', 'Browser tidak mendukung Geolocation.', 'error');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                // Sukses
                (position) => {
                    inputLat.value = position.coords.latitude;
                    inputLon.value = position.coords.longitude;
                    
                    // Update Loading -> Memproses
                    Swal.fire({
                        title: 'Memproses Absensi...',
                        text: 'Sedang memverifikasi data.',
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit Form
                    formAbsensi.submit();
                },
                // Gagal
                (error) => {
                    let msg = 'Terjadi kesalahan lokasi.';
                    if (error.code === error.PERMISSION_DENIED) msg = 'Izin lokasi ditolak. Aktifkan GPS.';
                    else if (error.code === error.POSITION_UNAVAILABLE) msg = 'Sinyal GPS tidak ditemukan.';
                    else if (error.code === error.TIMEOUT) msg = 'Waktu habis mencari lokasi.';
                    
                    Swal.fire('Gagal Lokasi', msg, 'error');
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        };

        if (btnAbsen) {
            btnAbsen.addEventListener('click', handleAbsenMengajar);
        }
    </script>
    <?php endif; ?>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/guru/jadwal/show.blade.php ENDPATH**/ ?>