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
    
    <?php $__env->startPush('styles'); ?>
    <style>
        .config-card { transition: transform 0.2s, box-shadow 0.2s; }
        .config-card:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
        
        /* Custom Scrollbar untuk Card Modul */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
    <?php $__env->stopPush(); ?>

     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                    <span class="text-3xl">⚙️</span> Konfigurasi Absensi
                </h2>
                <p class="text-sm text-gray-500 mt-1">Atur jam kerja, lokasi, jaringan, dan kalender libur sekolah.</p>
            </div>
            
            
            <a href="<?php echo e(route('sekolah.admin.konfigurasi.kios.show')); ?>" target="_blank" class="group flex items-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:scale-105">
                <div class="p-1.5 bg-white/20 rounded-lg group-hover:rotate-12 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div class="text-left">
                    <div class="text-[10px] font-medium text-indigo-200 uppercase tracking-wider">Mode Perangkat</div>
                    <div class="text-sm font-bold">Buka Kios Absensi &rarr;</div>
                </div>
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            
            <?php if(session('success')): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm flex items-center justify-between" role="alert">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium"><?php echo e(session('success')); ?></span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">&times;</button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm">
                    <p class="font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Terjadi Kesalahan:
                    </p>
                    <ul class="list-disc list-inside text-sm mt-1 ml-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            
            <form method="POST" action="<?php echo e(route('sekolah.admin.konfigurasi.settings.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                            Waktu Operasional
                        </h3>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                    
                    <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-2 gap-10">
                        
                        <div class="space-y-6">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b pb-2">Aturan Jam (Format 24 Jam)</h4>
                            
                            <div class="grid grid-cols-2 gap-6">
                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Jam Masuk</label>
                                    <div class="relative group">
                                        <input type="time" name="jam_masuk" value="<?php echo e(old('jam_masuk', $absensiSettings->jam_masuk ?? '07:00')); ?>" 
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 font-mono font-bold text-gray-800 transition-shadow group-hover:shadow-sm">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Waktu mulai check-in.</p>
                                </div>

                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Batas Terlambat</label>
                                    <div class="relative group">
                                        <input type="time" name="batas_telat" value="<?php echo e(old('batas_telat', $absensiSettings->batas_telat ?? '07:15')); ?>" 
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-xl focus:ring-amber-500 focus:border-amber-500 font-mono font-bold text-gray-800 transition-shadow group-hover:shadow-sm">
                                        <svg class="w-5 h-5 text-amber-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Lewat ini dihitung telat.</p>
                                </div>

                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Pulang Awal</label>
                                    <div class="relative group">
                                        <input type="time" name="jam_pulang_awal" value="<?php echo e(old('jam_pulang_awal', $absensiSettings->jam_pulang_awal ?? '14:00')); ?>" 
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 font-mono font-bold text-gray-800 transition-shadow group-hover:shadow-sm">
                                        <svg class="w-5 h-5 text-emerald-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Mulai bisa check-out.</p>
                                </div>

                                
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Batas Akhir Scan</label>
                                    <div class="relative group">
                                        <input type="time" name="jam_pulang_akhir" value="<?php echo e(old('jam_pulang_akhir', $absensiSettings->jam_pulang_akhir ?? '17:00')); ?>" 
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 font-mono font-bold text-gray-800 transition-shadow group-hover:shadow-sm">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Batas mesin menerima absen.</p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="space-y-6">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b pb-2">Hari Sekolah Aktif</h4>
                            
                            <?php
                                // Logic untuk memastikan data hari kerja ter-handle dengan benar
                                $defaultDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                                $savedDays = $absensiSettings->hari_kerja ?? $defaultDays;
                                
                                // Jika tersimpan sebagai JSON string di database, decode dulu
                                if (is_string($savedDays)) {
                                    $savedDays = json_decode($savedDays, true) ?? [];
                                }
                                
                                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                            ?>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="relative cursor-pointer group select-none">
                                        
                                        <input type="checkbox" 
                                               name="hari_kerja[]" 
                                               value="<?php echo e($hari); ?>" 
                                               class="peer sr-only" 
                                               <?php if(in_array($hari, $savedDays)): echo 'checked'; endif; ?>>
                                        
                                        
                                        <div class="flex items-center justify-between w-full px-3 py-2.5 rounded-xl border transition-all duration-200 ease-in-out
                                                    bg-gray-50 border-gray-200 text-gray-500 
                                                    group-hover:border-blue-300 group-hover:bg-white group-hover:shadow-sm
                                                    peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white peer-checked:shadow-md">
                                            
                                            <span class="font-bold text-sm"><?php echo e($hari); ?></span>
                                            
                                            
                                            <div class="w-4 h-4 rounded-full border border-gray-300 bg-white/50 flex items-center justify-center
                                                        peer-checked:border-white peer-checked:bg-white/20">
                                                <svg class="w-2.5 h-2.5 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="bg-blue-50 text-blue-700 text-xs p-3 rounded-lg flex gap-2 items-start">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p>Hari yang <b>tidak dipilih</b> (abu-abu) akan dianggap libur rutin (Check-in ditolak).</p>
                            </div>
                            
                            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('hari_kerja'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('hari_kerja')),'class' => 'mt-2']); ?>
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
                    </div>
                </div>
            </form>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col h-[500px] config-card">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-red-50/50 rounded-t-2xl">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></span>
                            Hari Libur
                        </h3>
                        <button onclick="openModal('libur')" class="text-xs bg-white border border-gray-200 hover:border-red-300 text-gray-600 hover:text-red-600 px-3 py-1.5 rounded-lg transition font-bold shadow-sm flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scroll">
                        <?php $__empty_1 = true; $__currentLoopData = $hariLiburList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $libur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-red-50/30 transition group">
                                <div>
                                    <div class="text-xs font-bold text-red-500"><?php echo e(\Carbon\Carbon::parse($libur->tanggal)->translatedFormat('d F Y')); ?></div>
                                    <div class="text-sm font-bold text-gray-700"><?php echo e($libur->keterangan); ?></div>
                                </div>
                                <form action="<?php echo e(route('sekolah.admin.konfigurasi.hari-libur.destroy', $libur->id)); ?>" method="POST" class="delete-form">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="button" class="btn-delete text-gray-300 hover:bg-red-100 group-hover:text-red-500 transition p-2 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-10">
                                <div class="bg-gray-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="text-gray-400 text-sm">Belum ada hari libur.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col h-[500px] config-card">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-emerald-50/50 rounded-t-2xl">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg></span>
                            Jaringan WiFi
                        </h3>
                        <button onclick="openModal('wifi')" class="text-xs bg-white border border-gray-200 hover:border-emerald-300 text-gray-600 hover:text-emerald-600 px-3 py-1.5 rounded-lg transition font-bold shadow-sm flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scroll">
                        <?php $__empty_1 = true; $__currentLoopData = $wifiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wifi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-emerald-50/30 transition group">
                                <div>
                                    <div class="text-sm font-bold text-gray-800"><?php echo e($wifi->nama_wifi_ssid); ?></div>
                                    <div class="text-xs font-mono text-gray-400 mt-0.5 bg-gray-100 px-2 py-0.5 rounded inline-block"><?php echo e($wifi->bssid ?? 'Any BSSID'); ?></div>
                                </div>
                                <form action="<?php echo e(route('sekolah.admin.konfigurasi.wifi.destroy', $wifi->id)); ?>" method="POST" class="delete-form">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="button" class="btn-delete text-gray-300 hover:bg-red-100 group-hover:text-red-500 transition p-2 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-10">
                                <div class="bg-gray-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                                </div>
                                <span class="text-gray-400 text-sm">Belum ada WiFi terdaftar.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col h-[500px] config-card">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-purple-50/50 rounded-t-2xl">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></span>
                            Lokasi (GPS)
                        </h3>
                        <button onclick="openModal('geofence')" class="text-xs bg-white border border-gray-200 hover:border-purple-300 text-gray-600 hover:text-purple-600 px-3 py-1.5 rounded-lg transition font-bold shadow-sm flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scroll">
                        <?php $__empty_1 = true; $__currentLoopData = $geofenceList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $geo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-purple-50/30 transition group">
                                <div>
                                    <div class="text-sm font-bold text-gray-800"><?php echo e($geo->nama_lokasi); ?></div>
                                    <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Radius: <?php echo e($geo->radius); ?>m
                                    </div>
                                    <div class="text-[10px] font-mono text-gray-400 mt-1"><?php echo e(number_format($geo->latitude, 6)); ?>, <?php echo e(number_format($geo->longitude, 6)); ?></div>
                                </div>
                                <form action="<?php echo e(route('sekolah.admin.konfigurasi.geofence.destroy', $geo->id)); ?>" method="POST" class="delete-form">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="button" class="btn-delete text-gray-300 hover:bg-red-100 group-hover:text-red-500 transition p-2 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-10">
                                <div class="bg-gray-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <span class="text-gray-400 text-sm">Belum ada lokasi terdaftar.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    
    <div id="configModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95" id="modalPanel">
                
                <div class="bg-white px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-black text-gray-800" id="modalTitle">Tambah Data</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="px-6 py-6">
                    
                    <form id="formLibur" action="<?php echo e(route('sekolah.admin.konfigurasi.hari-libur.store')); ?>" method="POST" class="hidden space-y-5 modal-form">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Libur</label>
                            <input type="date" name="tanggal" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm py-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Keterangan</label>
                            <input type="text" name="keterangan" placeholder="Cth: Hari Kemerdekaan" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm py-2.5">
                        </div>
                        <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 shadow-md transition transform active:scale-95">Simpan Hari Libur</button>
                    </form>

                    
                    <form id="formWifi" action="<?php echo e(route('sekolah.admin.konfigurasi.wifi.store')); ?>" method="POST" class="hidden space-y-5 modal-form">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama WiFi (SSID)</label>
                            <input type="text" name="nama_wifi_ssid" placeholder="Cth: Sekolah_Official" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">MAC Address (BSSID) - Opsional</label>
                            <input type="text" name="bssid" placeholder="AA:BB:CC:DD:EE:FF" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2.5">
                            <p class="text-xs text-gray-400 mt-1">Kosongkan jika ingin mengizinkan semua access point dengan nama SSID di atas.</p>
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl hover:bg-emerald-700 shadow-md transition transform active:scale-95">Simpan WiFi</button>
                    </form>

                    
                    <form id="formGeofence" action="<?php echo e(route('sekolah.admin.konfigurasi.geofence.store')); ?>" method="POST" class="hidden space-y-5 modal-form">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lokasi</label>
                            <input type="text" name="nama_lokasi" placeholder="Cth: Gerbang Utama" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm py-2.5">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Latitude</label>
                                <input type="text" name="latitude" placeholder="-6.12345" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm py-2.5">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Longitude</label>
                                <input type="text" name="longitude" placeholder="106.12345" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm py-2.5">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Radius (Meter)</label>
                            <input type="number" name="radius" value="50" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm py-2.5">
                        </div>
                        <button type="submit" class="w-full bg-purple-600 text-white font-bold py-3 rounded-xl hover:bg-purple-700 shadow-md transition transform active:scale-95">Simpan Lokasi</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const modal = document.getElementById('configModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        const modalTitle = document.getElementById('modalTitle');
        
        function openModal(type) {
            // Hide all forms first
            document.querySelectorAll('.modal-form').forEach(el => el.classList.add('hidden'));
            
            if (type === 'libur') {
                modalTitle.innerText = 'Tambah Hari Libur';
                document.getElementById('formLibur').classList.remove('hidden');
            } else if (type === 'wifi') {
                modalTitle.innerText = 'Tambah Jaringan WiFi';
                document.getElementById('formWifi').classList.remove('hidden');
            } else if (type === 'geofence') {
                modalTitle.innerText = 'Tambah Lokasi Geofence';
                document.getElementById('formGeofence').classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'scale-95');
                panel.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeModal() {
            backdrop.classList.add('opacity-0');
            panel.classList.remove('opacity-100', 'scale-100');
            panel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        // SweetAlert Delete
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });

        // Auto Open Modal on Error
        <?php if($errors->has('tanggal') || $errors->has('keterangan')): ?>
            openModal('libur');
        <?php elseif($errors->has('nama_wifi_ssid') || $errors->has('bssid')): ?>
            openModal('wifi');
        <?php elseif($errors->has('nama_lokasi') || $errors->has('latitude')): ?>
            openModal('geofence');
        <?php endif; ?>
    </script>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/konfigurasi/index.blade.php ENDPATH**/ ?>