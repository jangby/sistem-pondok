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
            <?php echo e(__('Detail Perpulangan')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500 relative">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800"><?php echo e($event->judul); ?></h2>
                        <div class="mt-2 text-gray-600 text-sm space-y-1">
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="font-semibold">Mulai:</span> <?php echo e(\Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y')); ?>

                            </p>
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-semibold">Batas Kembali:</span> <?php echo e(\Carbon\Carbon::parse($event->tanggal_akhir)->format('d M Y H:i')); ?>

                            </p>
                        </div>
                        <div class="mt-3">
                            <span class="px-3 py-1 text-xs font-bold rounded-full <?php echo e($event->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($event->is_active ? 'Status: Aktif' : 'Status: Selesai'); ?>

                            </span>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 w-full md:w-auto">
                         <a href="<?php echo e(route('pengurus.perpulangan.index')); ?>" class="flex-1 md:flex-none justify-center px-4 py-2 bg-gray-500 text-white rounded-lg text-sm font-bold hover:bg-gray-600 flex items-center gap-2 transition">
                            &laquo; Kembali
                        </a>
                        <a href="<?php echo e(route('pengurus.perpulangan.scan')); ?>" class="flex-1 md:flex-none justify-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 flex items-center gap-2 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            Scan QR
                        </a>
                    </div>
                </div>
            </div>

            <?php
                // Ambil semua records sekali saja untuk perhitungan statistik
                // Menggunakan lazy loading dari relasi event untuk mendapatkan TOTAL data, bukan hanya halaman ini
                $allRecords = $event->records; 
                $batasAkhir = \Carbon\Carbon::parse($event->tanggal_akhir)->endOfDay();
                $sekarang = \Carbon\Carbon::now();
            ?>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-xl shadow-sm border-t-4 border-gray-400 flex flex-col items-center justify-center text-center">
                    <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Santri</div>
                    <div class="text-3xl font-extrabold text-gray-800 mt-1"><?php echo e($allRecords->count()); ?></div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-sm border-t-4 border-blue-400 flex flex-col items-center justify-center text-center">
                    <div class="text-blue-500 text-xs font-bold uppercase tracking-wider">Sedang Pulang</div>
                    <div class="text-3xl font-extrabold text-blue-800 mt-1">
                        <?php echo e($allRecords->where('status', 1)->count()); ?>

                    </div>
                    <span class="text-[10px] text-gray-400 mt-1">Belum kembali</span>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-sm border-t-4 border-emerald-400 flex flex-col items-center justify-center text-center">
                    <div class="text-emerald-500 text-xs font-bold uppercase tracking-wider">Sudah Kembali</div>
                    <div class="text-3xl font-extrabold text-emerald-800 mt-1">
                        <?php echo e($allRecords->where('status', 2)->count()); ?>

                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl shadow-sm border-t-4 border-red-400 flex flex-col items-center justify-center text-center">
                    <div class="text-red-500 text-xs font-bold uppercase tracking-wider">Terlambat</div>
                    <div class="text-3xl font-extrabold text-red-800 mt-1">
                        <?php echo e($allRecords->filter(function($r) use ($batasAkhir, $sekarang) {
                            // Logic: Sudah kembali tapi telat OR Belum kembali dan waktu habis
                            if ($r->waktu_kembali) {
                                return \Carbon\Carbon::parse($r->waktu_kembali)->gt($batasAkhir);
                            }
                            return $r->status != 2 && $sekarang->gt($batasAkhir);
                        })->count()); ?>

                    </div>
                    <span class="text-[10px] text-gray-400 mt-1">Melebihi Batas</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50">
                    <form action="" method="GET" class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                        <div class="relative w-full md:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </span>
                            <input type="text" name="search" placeholder="Cari Nama/NISM..." value="<?php echo e(request('search')); ?>" class="pl-10 w-full rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        <div class="flex w-full md:w-auto gap-2">
                            <select name="status_filter" class="w-full md:w-auto rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Semua Status</option>
                                <option value="sedang_pulang" <?php echo e(request('status_filter') == 'sedang_pulang' ? 'selected' : ''); ?>>Sedang Pulang</option>
                                <option value="sudah_kembali" <?php echo e(request('status_filter') == 'sudah_kembali' ? 'selected' : ''); ?>>Sudah Kembali</option>
                            </select>
                            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                                Filter
                            </button>
                        </div>
                    </form>

                    <button onclick="document.getElementById('downloadModal').classList.remove('hidden')" 
                            class="w-full md:w-auto bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center justify-center gap-2 hover:bg-emerald-700 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Data
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 whitespace-nowrap">No</th>
                                <th class="px-6 py-3 whitespace-nowrap">Santri</th>
                                <th class="px-6 py-3 whitespace-nowrap">Kelas</th>
                                <th class="px-6 py-3 whitespace-nowrap">Status</th>
                                <th class="px-6 py-3 whitespace-nowrap">Waktu Kembali</th>
                                <th class="px-6 py-3 whitespace-nowrap">Keterlambatan</th>
                                <th class="px-6 py-3 text-center whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="bg-white border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php echo e($records->firstItem() + $index); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900"><?php echo e($record->santri->full_name); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($record->santri->nis); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo e($record->santri->kelas->nama_kelas ?? '-'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($record->status == 2): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            ✅ Sudah Kembali
                                        </span>
                                    <?php elseif($record->status == 1): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            🚚 Sedang Pulang
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            🏠 Belum Jalan
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php echo e($record->waktu_kembali ? \Carbon\Carbon::parse($record->waktu_kembali)->format('d M H:i') : '-'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                        $isLate = false;
                                        $diff = 0;
                                        // Gunakan batas akhir dari event
                                        $batas = \Carbon\Carbon::parse($event->tanggal_akhir)->endOfDay();

                                        if ($record->waktu_kembali) {
                                            $kembali = \Carbon\Carbon::parse($record->waktu_kembali);
                                            if ($kembali->gt($batas)) {
                                                $isLate = true;
                                                // PERBAIKAN: Cast ke int agar bulat
                                                $diff = (int) $batas->diffInDays($kembali);
                                            }
                                        } elseif ($record->status != 2 && \Carbon\Carbon::now()->gt($batas)) {
                                            // Belum kembali dan waktu habis
                                            $isLate = true;
                                            // PERBAIKAN: Cast ke int agar bulat
                                            $diff = (int) $batas->diffInDays(\Carbon\Carbon::now());
                                        }
                                    ?>

                                    <?php if($isLate): ?>
                                        <span class="text-red-600 font-bold text-xs bg-red-50 px-2 py-1 rounded">
                                            +<?php echo e($diff); ?> Hari
                                        </span>
                                    <?php else: ?>
                                        <span class="text-emerald-600 text-xs font-semibold">Tepat Waktu</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <a href="<?php echo e(route('pengurus.perpulangan.edit', $record->id)); ?>" class="text-blue-600 hover:text-blue-900 text-xs font-bold border border-blue-200 px-3 py-1 rounded hover:bg-blue-50 transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        <p>Tidak ada data santri ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if($records instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                <div class="p-4 border-t border-gray-100 bg-gray-50">
                    <?php echo e($records->withQueryString()->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="downloadModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all scale-100">
            <div class="bg-emerald-600 p-4 flex justify-between items-center">
                <h3 class="text-white font-bold text-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Unduh Data Perpulangan
                </h3>
                <button onclick="document.getElementById('downloadModal').classList.add('hidden')" class="text-emerald-100 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form action="<?php echo e(route('pengurus.perpulangan.download', $event->id)); ?>" method="GET" class="p-6">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Kategori Data</label>
                        <div class="relative">
                            <select name="status" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2.5 shadow-sm">
                                <option value="all">📥 Semua Data (Lengkap)</option>
                                <option value="belum_jalan">🏠 Belum Jalan</option>
                                <option value="sedang_pulang">🚚 Sedang Pulang / Di Rumah</option>
                                <option value="sudah_kembali">✅ Sudah Kembali</option>
                                <option value="terlambat">⚠️ Terlambat (Melebihi Batas)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Format File</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="border rounded-lg p-3 flex items-center gap-3 cursor-pointer hover:bg-emerald-50 hover:border-emerald-200 transition group ring-1 ring-transparent has-[:checked]:ring-emerald-500 has-[:checked]:bg-emerald-50">
                                <div class="bg-green-100 p-2 rounded-full group-hover:bg-green-200">
                                    <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <input type="radio" name="format" value="excel" checked class="text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-sm font-bold text-gray-700 ml-1">Excel</span>
                                </div>
                            </label>
                            
                            <label class="border rounded-lg p-3 flex items-center gap-3 cursor-pointer hover:bg-red-50 hover:border-red-200 transition group ring-1 ring-transparent has-[:checked]:ring-red-500 has-[:checked]:bg-red-50">
                                <div class="bg-red-100 p-2 rounded-full group-hover:bg-red-200">
                                    <svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <input type="radio" name="format" value="pdf" class="text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-sm font-bold text-gray-700 ml-1">PDF</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('downloadModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit" onclick="document.getElementById('downloadModal').classList.add('hidden')" class="px-5 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold hover:bg-emerald-700 transition shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh File
                    </button>
                </div>
            </form>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/perpulangan/show.blade.php ENDPATH**/ ?>