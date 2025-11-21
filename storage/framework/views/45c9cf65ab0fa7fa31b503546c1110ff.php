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
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Monitoring Absensi Diniyah')); ?>

            </h2>
            
            
            <form method="GET" action="<?php echo e(route('pendidikan.admin.absensi.rekap')); ?>" class="flex flex-wrap items-center gap-3">
                
                
                <div class="relative">
                    <input type="date" name="tanggal" value="<?php echo e($tanggal); ?>" 
                        class="rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm pl-3 pr-8"
                        onchange="this.form.submit()">
                </div>

                
                <select name="mustawa_id" class="rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    <?php $__currentLoopData = $mustawas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m->id); ?>" <?php echo e(request('mustawa_id') == $m->id ? 'selected' : ''); ?>>
                            <?php echo e($m->nama); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <a href="<?php echo e(route('pendidikan.admin.absensi.rekap')); ?>" class="text-sm text-gray-500 hover:text-gray-700 underline">Reset</a>
            </form>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-emerald-500 p-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Hadir</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1"><?php echo e($hadir); ?></p>
                        <p class="text-[10px] text-emerald-600 font-medium mt-1">
                            <?php echo e($persentase); ?>% Kehadiran
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-full text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-blue-500 p-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Izin</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1"><?php echo e($izin); ?></p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-orange-500 p-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Sakit</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1"><?php echo e($sakit); ?></p>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-full text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-red-500 p-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase">Alpha / Bolos</p>
                        <p class="text-2xl font-extrabold text-gray-800 mt-1"><?php echo e($alpha); ?></p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-full text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-800">Log Absensi Masuk</h3>
                        <p class="text-xs text-gray-500 mt-1">Data direkam secara real-time dari input ustadz.</p>
                    </div>
                    
                    <button type="button" disabled class="text-xs bg-gray-200 text-gray-400 px-3 py-2 rounded-lg cursor-not-allowed font-bold flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export PDF
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Santri</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas & Mapel</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pengajar</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Metode</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($log->waktu_scan ? \Carbon\Carbon::parse($log->waktu_scan)->format('H:i') : '-'); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-800"><?php echo e($log->santri->full_name); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo e($log->santri->nis); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-700"><?php echo e($log->jadwal->mustawa->nama ?? '-'); ?></div>
                                        <div class="text-xs text-emerald-600"><?php echo e($log->jadwal->mapel->nama_mapel ?? '-'); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <?php echo e($log->jadwal->ustadz->nama_lengkap ?? '-'); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if($log->status == 'H'): ?>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">Hadir</span>
                                        <?php elseif($log->status == 'I'): ?>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Izin</span>
                                        <?php elseif($log->status == 'S'): ?>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Sakit</span>
                                        <?php elseif($log->status == 'A'): ?>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Alpha</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php if($log->metode == 'rfid'): ?>
                                            <span class="flex items-center gap-1 text-emerald-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                RFID
                                            </span>
                                        <?php elseif($log->metode == 'qr'): ?>
                                            <span class="flex items-center gap-1 text-blue-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M9 17h.01M9 14h.01M3 21h18M4.5 4.5l15 15"></path></svg>
                                                QR
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400">Manual</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <p class="text-sm">Tidak ada data absensi pada tanggal ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100">
                    <?php echo e($logs->links()); ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/absensi/rekap.blade.php ENDPATH**/ ?>