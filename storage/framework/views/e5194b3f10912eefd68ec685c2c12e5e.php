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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Anggota Kelas: <span class="text-emerald-600"><?php echo e($mustawa->nama); ?></span>
            </h2>
            <a href="<?php echo e(route('pendidikan.admin.anggota-kelas.index')); ?>" class="text-sm text-gray-600 hover:text-gray-900 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"></path></svg>
                Kembali
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r shadow-sm">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 flex flex-col h-full">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">
                            Daftar Santri
                            <span class="ml-2 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs"><?php echo e($members->count()); ?> Siswa</span>
                        </h3>
                    </div>
                    
                    <div class="p-0 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Identitas Santri</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-500 w-10"><?php echo e($index + 1); ?></td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-900"><?php echo e($member->full_name); ?></div>
                                            <div class="text-xs text-gray-500">NIS: <?php echo e($member->nis); ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="<?php echo e(route('pendidikan.admin.anggota-kelas.destroy', ['mustawa' => $mustawa->id, 'santri' => $member->id])); ?>" method="POST" onsubmit="return confirm('Keluarkan santri ini dari kelas?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold bg-red-50 px-3 py-1.5 rounded-lg border border-red-100 hover:bg-red-100 transition">
                                                    Keluarkan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-400 flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            Belum ada santri di kelas ini.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-emerald-100 h-fit sticky top-6">
                    
                    
                    <div class="p-5 bg-emerald-50 border-b border-emerald-100">
                        <h3 class="text-lg font-bold text-emerald-800">Tambah Anggota</h3>
                        <p class="text-xs text-emerald-600 mt-1">Cari dan centang santri untuk dimasukkan.</p>
                    </div>

                    
                    <div class="p-4 border-b border-gray-100 bg-white">
                        <form method="GET" action="<?php echo e(route('pendidikan.admin.anggota-kelas.show', $mustawa->id)); ?>">
                            <div class="relative">
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama atau NIS..." 
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm">
                                <div class="absolute left-3 top-2.5 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>
                        </form>
                    </div>

                    
                    <form action="<?php echo e(route('pendidikan.admin.anggota-kelas.store', $mustawa->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="max-h-[400px] overflow-y-auto p-2 space-y-1 custom-scrollbar">
                            <?php if($nonKelas->isNotEmpty()): ?>
                                
                                
                                <div class="px-3 py-2 flex items-center justify-between border-b border-gray-50 mb-2">
                                    <span class="text-xs font-bold text-gray-500">Daftar Santri (Non-Kelas)</span>
                                    <button type="button" onclick="toggleAll(this)" class="text-xs text-emerald-600 hover:text-emerald-800 font-bold cursor-pointer">
                                        Pilih Semua
                                    </button>
                                </div>

                                <?php $__currentLoopData = $nonKelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition border border-transparent hover:border-gray-200 group">
                                        <input type="checkbox" name="santri_ids[]" value="<?php echo e($santri->id); ?>" class="santri-checkbox rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 h-4 w-4 mt-0.5">
                                        <div class="ml-3">
                                            <span class="block text-sm font-medium text-gray-700 group-hover:text-gray-900"><?php echo e($santri->full_name); ?></span>
                                            <span class="block text-xs text-gray-400"><?php echo e($santri->nis); ?></span>
                                        </div>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                
                                <div class="mt-2 px-2">
                                    <?php echo e($nonKelas->links()); ?> 
                                </div>

                            <?php else: ?>
                                <div class="text-center py-8 px-4 text-gray-400">
                                    <p class="text-xs">Tidak ditemukan santri yang belum memiliki kelas.</p>
                                    <?php if(request('search')): ?>
                                        <a href="<?php echo e(route('pendidikan.admin.anggota-kelas.show', $mustawa->id)); ?>" class="text-emerald-600 text-xs font-bold mt-2 block hover:underline">Reset Pencarian</a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="p-4 border-t border-gray-100 bg-gray-50">
                            <button type="submit" class="w-full flex justify-center items-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed" <?php echo e($nonKelas->isEmpty() ? 'disabled' : ''); ?>>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Masukkan Terpilih
                            </button>
                        </div>
                    </form>
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
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
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