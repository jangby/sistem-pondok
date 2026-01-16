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
    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-4 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800"><?php echo e($event->judul); ?></h2>
                        <p class="text-gray-600 mt-1">
                            <span class="font-semibold">Waktu Mulai:</span> <?php echo e(\Carbon\Carbon::parse($event->tanggal_mulai)->format('d M Y H:i')); ?> <br>
                            <span class="font-semibold">Batas Kembali:</span> <?php echo e(\Carbon\Carbon::parse($event->tanggal_akhir)->format('d M Y H:i')); ?>

                        </p>
                        <div class="mt-3">
                            <span class="px-3 py-1 text-xs font-bold rounded-full <?php echo e($event->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($event->is_active ? 'Aktif' : 'Selesai'); ?>

                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                         <a href="<?php echo e(route('pengurus.perpulangan.index')); ?>" class="px-4 py-2 bg-gray-500 text-white rounded-lg text-sm hover:bg-gray-600 text-center">
                            &laquo; Kembali
                        </a>
                        <a href="<?php echo e(route('pengurus.perpulangan.scan')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 flex items-center gap-2 justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            Scan QR
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-gray-400">
                <div class="text-gray-500 text-xs font-bold uppercase">Total Santri</div>
                <div class="text-3xl font-bold text-gray-800 mt-1"><?php echo e($records->count()); ?></div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-blue-400">
                <div class="text-blue-500 text-xs font-bold uppercase">Sedang Pulang</div>
                <div class="text-3xl font-bold text-blue-800 mt-1">
                    <?php echo e($records->whereIn('status', ['sedang_pulang', 'izin', 'sakit'])->count()); ?>

                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-emerald-400">
                <div class="text-emerald-500 text-xs font-bold uppercase">Sudah Kembali</div>
                <div class="text-3xl font-bold text-emerald-800 mt-1">
                    <?php echo e($records->where('status', 'sudah_kembali')->count()); ?>

                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border-t-4 border-red-400">
                <div class="text-red-500 text-xs font-bold uppercase">Terlambat</div>
                <div class="text-3xl font-bold text-red-800 mt-1">
                    <?php echo e($records->filter(function($r) use ($event) {
                        return $r->status == 'sedang_pulang' && \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($event->tanggal_akhir));
                    })->count()); ?>

                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50">
                <form action="" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                    <input type="text" name="search" placeholder="Cari Nama/NISM..." value="<?php echo e(request('search')); ?>" class="rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500 w-full md:w-64">
                    <select name="status_filter" class="rounded-lg border-gray-300 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Semua Status</option>
                        <option value="sedang_pulang" <?php echo e(request('status_filter') == 'sedang_pulang' ? 'selected' : ''); ?>>Sedang Pulang</option>
                        <option value="sudah_kembali" <?php echo e(request('status_filter') == 'sudah_kembali' ? 'selected' : ''); ?>>Sudah Kembali</option>
                    </select>
                    <button type="submit" class="bg-gray-800 text-white px-3 py-2 rounded-lg hover:bg-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
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
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Santri</th>
                            <th class="px-6 py-3">Kelas</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Waktu Kembali</th>
                            <th class="px-6 py-3">Keterlambatan</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo e($index + 1); ?></td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo e($record->santri->full_name); ?></div>
                                <div class="text-xs"><?php echo e($record->santri->nis); ?></div>
                            </td>
                            <td class="px-6 py-4"><?php echo e($record->santri->kelas->nama_kelas ?? '-'); ?></td>
                            <td class="px-6 py-4">
                                <?php if($record->status == 'sudah_kembali'): ?>
                                    <span class="px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-800">Sudah Kembali</span>
                                <?php elseif($record->status == 'sedang_pulang'): ?>
                                    <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Sedang Pulang</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800"><?php echo e(ucfirst(str_replace('_', ' ', $record->status))); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo e($record->waktu_kembali ? \Carbon\Carbon::parse($record->waktu_kembali)->format('d M H:i') : '-'); ?>

                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $isLate = false;
                                    $diff = 0;
                                    if ($record->waktu_kembali) {
                                        $kembali = \Carbon\Carbon::parse($record->waktu_kembali);
                                        $batas = \Carbon\Carbon::parse($event->tanggal_akhir);
                                        if ($kembali->gt($batas)) {
                                            $isLate = true;
                                            $diff = $batas->diffInDays($kembali);
                                        }
                                    } elseif ($record->status == 'sedang_pulang' && \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($event->tanggal_akhir))) {
                                        $isLate = true;
                                        $diff = \Carbon\Carbon::parse($event->tanggal_akhir)->diffInDays(\Carbon\Carbon::now());
                                    }
                                ?>

                                <?php if($isLate): ?>
                                    <span class="text-red-600 font-bold text-xs">+<?php echo e($diff); ?> Hari</span>
                                <?php else: ?>
                                    <span class="text-emerald-600 text-xs">Tepat Waktu</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="<?php echo e(route('pengurus.perpulangan.edit', $record->id)); ?>" class="text-blue-600 hover:text-blue-900 text-xs font-bold">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                Tidak ada data santri untuk event ini.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($records instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
            <div class="p-4">
                <?php echo e($records->withQueryString()->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="downloadModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all animate-fade-in-down">
        <div class="bg-emerald-600 p-4 flex justify-between items-center">
            <h3 class="text-white font-bold text-lg">Unduh Data Perpulangan</h3>
            <button onclick="document.getElementById('downloadModal').classList.add('hidden')" class="text-emerald-100 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="<?php echo e(route('pengurus.perpulangan.download', $event->id)); ?>" method="GET" class="p-6">
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kategori Data</label>
                    <div class="relative">
                        <select name="status" class="block w-full rounded-lg border-gray-300 bg-gray-50 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm p-2.5">
                            <option value="all">📥 Semua Data (Lengkap)</option>
                            <option value="belum_jalan">🏠 Belum Jalan</option>
                            <option value="sedang_pulang">🚚 Sedang Pulang / Di Rumah</option>
                            <option value="sudah_kembali">✅ Sudah Kembali</option>
                            <option value="terlambat">⚠️ Terlambat (Melebihi Batas)</option>
                        </select>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Pilih jenis data santri yang ingin Anda laporan.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Format File</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="border rounded-lg p-3 flex items-center gap-3 cursor-pointer hover:bg-emerald-50 hover:border-emerald-200 transition group">
                            <div class="bg-green-100 p-2 rounded-full group-hover:bg-green-200">
                                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <input type="radio" name="format" value="excel" checked class="text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm font-bold text-gray-700 ml-1">Excel</span>
                            </div>
                        </label>
                        
                        <label class="border rounded-lg p-3 flex items-center gap-3 cursor-pointer hover:bg-red-50 hover:border-red-200 transition group">
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

<style>
    @keyframes fade-in-down {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-down {
        animation: fade-in-down 0.3s ease-out;
    }
</style>
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