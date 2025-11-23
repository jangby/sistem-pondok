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
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Anggota Kelas: <span class="text-emerald-600"><?php echo e($mustawa->nama); ?></span>
                <?php if($mustawa->gender): ?>
                    <span class="ml-2 text-xs px-2 py-1 rounded bg-gray-100 text-gray-600 border border-gray-200">
                        Khusus <?php echo e($mustawa->gender); ?>

                    </span>
                <?php endif; ?>
            </h2>
            <a href="<?php echo e(route('pendidikan.admin.anggota-kelas.index')); ?>" class="text-sm text-gray-600 hover:text-emerald-600 flex items-center transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"></path></svg>
                Kembali
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r shadow-sm flex justify-between items-center">
                    <span><?php echo e(session('success')); ?></span>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">&times;</button>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                
                
                <div class="lg:col-span-2 flex flex-col h-full order-2 lg:order-1">
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 h-full">
                        <div class="p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                Siswa Terdaftar
                            </h3>
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold"><?php echo e($members->count()); ?> Siswa</span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase w-10">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Identitas Santri</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase hidden sm:table-cell">Kelas Sekolah</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-gray-50 transition group">
                                            <td class="px-4 py-3 text-sm text-gray-500 text-center"><?php echo e($index + 1); ?></td>
                                            <td class="px-4 py-3">
                                                <div class="font-bold text-gray-900 text-sm"><?php echo e($member->full_name); ?></div>
                                                <div class="text-xs text-gray-500 flex gap-2 items-center mt-0.5">
                                                    <span class="bg-gray-100 px-1.5 rounded text-gray-600 border border-gray-200"><?php echo e($member->nis); ?></span>
                                                    <span class="<?php echo e($member->jenis_kelamin == 'L' ? 'text-blue-600' : 'text-pink-600'); ?> font-bold">
                                                        <?php echo e($member->jenis_kelamin == 'L' ? 'L' : 'P'); ?>

                                                    </span>
                                                    
                                                    <span class="sm:hidden text-gray-400">• <?php echo e($member->kelas->nama_kelas ?? '-'); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell">
                                                <?php echo e($member->kelas->nama_kelas ?? '-'); ?>

                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <form action="<?php echo e(route('pendidikan.admin.anggota-kelas.destroy', ['mustawa' => $mustawa->id, 'santri' => $member->id])); ?>" method="POST" onsubmit="return confirm('Keluarkan <?php echo e($member->full_name); ?> dari kelas ini?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-red-400 hover:text-red-600 p-1 rounded hover:bg-red-50 transition tooltip" title="Keluarkan">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                                    <span class="text-sm">Belum ada anggota kelas. Tambahkan dari panel kanan/bawah.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
                <div class="flex flex-col h-fit sticky top-4 order-1 lg:order-2 space-y-4">
                    
                    <div class="bg-white shadow-sm sm:rounded-xl border border-emerald-200 overflow-hidden">
                        
                        <div class="p-4 bg-emerald-600 text-white flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-base">Tambah Anggota</h3>
                                <p class="text-xs text-emerald-100">Cari & centang santri</p>
                            </div>
                            
                            <?php if($mustawa->gender): ?>
                                <div class="bg-white/20 px-2 py-1 rounded text-xs font-semibold backdrop-blur-sm border border-white/30">
                                    Auto-Filter: <?php echo e($mustawa->gender); ?>

                                </div>
                            <?php endif; ?>
                        </div>

                        
                        <div class="p-3 bg-gray-50 border-b border-gray-100 space-y-2">
                            <form method="GET" action="<?php echo e(route('pendidikan.admin.anggota-kelas.show', $mustawa->id)); ?>" id="filterForm">
                                
                                <select name="kelas_filter" onchange="document.getElementById('filterForm').submit()" 
                                    class="w-full text-sm border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500 shadow-sm mb-2">
                                    <option value="">-- Semua Kelas Sekolah --</option>
                                    <?php $__currentLoopData = $dataKelasSekolah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ks): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($ks->id); ?>" <?php echo e(request('kelas_filter') == $ks->id ? 'selected' : ''); ?>>
                                            <?php echo e($ks->nama_kelas); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                                
                                <div class="relative">
                                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari Nama / NIS..." 
                                        class="w-full pl-9 pr-3 py-2 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm">
                                    <div class="absolute left-3 top-2.5 text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <?php if(request('search') || request('kelas_filter')): ?>
                                        <a href="<?php echo e(route('pendidikan.admin.anggota-kelas.show', $mustawa->id)); ?>" class="absolute right-2 top-2 text-xs bg-gray-200 hover:bg-gray-300 text-gray-600 px-2 py-0.5 rounded transition">
                                            Reset
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>

                        
                        <form action="<?php echo e(route('pendidikan.admin.anggota-kelas.store', $mustawa->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            
                            <div class="max-h-[450px] overflow-y-auto p-2 bg-gray-50/50 custom-scrollbar">
                                <?php if($nonKelas->isNotEmpty()): ?>
                                    
                                    <div class="px-2 py-1 flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Kandidat Siswa</span>
                                        <button type="button" onclick="toggleAll(this)" class="text-xs text-emerald-600 hover:text-emerald-800 font-bold cursor-pointer bg-emerald-50 px-2 py-1 rounded hover:bg-emerald-100 transition">
                                            Pilih Semua
                                        </button>
                                    </div>

                                    <div class="space-y-2">
                                        <?php $__currentLoopData = $nonKelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="relative flex items-start p-3 rounded-xl border border-gray-200 bg-white shadow-sm hover:shadow-md hover:border-emerald-400 cursor-pointer transition-all group select-none">
                                                <div class="flex items-center h-5 mt-0.5">
                                                    <input type="checkbox" name="santri_ids[]" value="<?php echo e($santri->id); ?>" class="santri-checkbox rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 h-4 w-4">
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <div class="font-bold text-gray-800 text-sm group-hover:text-emerald-700 line-clamp-1">
                                                        <?php echo e($santri->full_name); ?>

                                                    </div>
                                                    <div class="flex flex-wrap items-center gap-1.5 mt-1 text-xs text-gray-500">
                                                        <span class="bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200"><?php echo e($santri->nis); ?></span>
                                                        <?php if($santri->kelas): ?>
                                                            <span class="bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded border border-blue-100">
                                                                <?php echo e($santri->kelas->nama_kelas); ?>

                                                            </span>
                                                        <?php endif; ?>
                                                        <span class="<?php echo e($santri->jenis_kelamin == 'L' ? 'text-blue-500' : 'text-pink-500'); ?> font-bold px-1">
                                                            <?php echo e($santri->jenis_kelamin); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    <div class="mt-3 px-1">
                                        <?php echo e($nonKelas->links('pagination::simple-tailwind')); ?>

                                    </div>

                                <?php else: ?>
                                    <div class="text-center py-10 px-4 flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-200 rounded-xl m-2">
                                        <svg class="w-10 h-10 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-sm">Tidak ditemukan santri.</p>
                                        <p class="text-xs mt-1">Coba ubah filter kelas atau kata kunci pencarian.</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="p-4 border-t border-gray-100 bg-white sticky bottom-0 rounded-b-xl shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                                <button type="submit" class="w-full flex justify-center items-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed active:transform active:scale-[0.98]" <?php echo e($nonKelas->isEmpty() ? 'disabled' : ''); ?>>
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Simpan Anggota Terpilih
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        function toggleAll(btn) {
            const checkboxes = document.querySelectorAll('.santri-checkbox');
            const isAllChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => {
                cb.checked = !isAllChecked;
            });
            
            btn.innerText = isAllChecked ? "Pilih Semua" : "Batal Pilih";
            btn.classList.toggle('text-red-600', !isAllChecked);
            btn.classList.toggle('text-emerald-600', isAllChecked);
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/anggota-kelas/show.blade.php ENDPATH**/ ?>