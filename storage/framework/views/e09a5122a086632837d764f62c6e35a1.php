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
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="<?php echo e(route('sekolah.admin.kegiatan-akademik.index')); ?>" class="hover:text-indigo-600 transition">Kegiatan</a>
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <a href="<?php echo e(route('sekolah.admin.kegiatan-akademik.kelola.kelas', $kegiatan->id)); ?>" class="hover:text-indigo-600 transition">Daftar Kelas</a>
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <span>Mapel</span>
                </div>
                <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                    Kelola Nilai Mapel
                </h2>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            
            <div class="bg-white rounded-2xl shadow-sm border border-indigo-100 p-6 flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden">
                
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-blue-50 rounded-full blur-2xl"></div>

                <div class="relative z-10 flex items-center gap-5 w-full">
                    
                    <div class="flex-shrink-0 w-20 h-20 bg-indigo-600 rounded-2xl flex flex-col items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <span class="text-xs font-medium uppercase tracking-wider opacity-80">Kelas</span>
                        <span class="text-3xl font-black"><?php echo e($kelas->nama_kelas); ?></span>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><?php echo e($kegiatan->nama_kegiatan); ?></h3>
                        <div class="flex items-center gap-4 mt-1 text-sm text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                Tingkat <?php echo e($kelas->tingkat); ?>

                            </span>
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <?php echo e($kelas->santris()->where('status', 'active')->count()); ?> Siswa
                            </span>
                        </div>
                    </div>
                </div>

                
                <div class="flex gap-3 w-full md:w-auto flex-shrink-0 relative z-10">
                    <div class="px-5 py-3 bg-gray-50 border border-gray-200 rounded-xl text-center">
                        <div class="text-xs text-gray-500 uppercase font-bold">Total Mapel</div>
                        <div class="text-xl font-black text-gray-800"><?php echo e($mapelList->count()); ?></div>
                    </div>
                    <div class="px-5 py-3 bg-emerald-50 border border-emerald-100 rounded-xl text-center">
                        <div class="text-xs text-emerald-600 uppercase font-bold">Selesai</div>
                        <div class="text-xl font-black text-emerald-600"><?php echo e($mapelList->where('completion', 100)->count()); ?></div>
                    </div>
                </div>
            </div>

            
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <h3 class="text-lg font-bold text-gray-800">Daftar Mata Pelajaran</h3>
                <form method="GET" class="w-full sm:w-96">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition" 
                               placeholder="Cari mapel (cth: Matematika)...">
                    </div>
                </form>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Mata Pelajaran</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Status Nilai</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi & Laporan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php $__empty_1 = true; $__currentLoopData = $mapelList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $progress = $mapel->completion;
                                    // Tentukan Warna
                                    if($progress == 100) {
                                        $barColor = 'bg-emerald-500';
                                        $textColor = 'text-emerald-700';
                                        $badge = '<span class="ml-2 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">LENGKAP</span>';
                                        $rowClass = 'hover:bg-emerald-50/30';
                                    } elseif($progress > 0) {
                                        $barColor = 'bg-blue-500';
                                        $textColor = 'text-blue-700';
                                        $badge = '<span class="ml-2 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">PROSES</span>';
                                        $rowClass = 'hover:bg-blue-50/30';
                                    } else {
                                        $barColor = 'bg-gray-300';
                                        $textColor = 'text-gray-500';
                                        $badge = ''; // Kosong
                                        $rowClass = 'hover:bg-gray-50';
                                    }

                                    // Inisial Avatar
                                    $initials = $mapel->kode_mapel ? substr($mapel->kode_mapel, 0, 3) : substr($mapel->nama_mapel, 0, 2);
                                    $colors = ['bg-blue-100 text-blue-700', 'bg-purple-100 text-purple-700', 'bg-amber-100 text-amber-700', 'bg-rose-100 text-rose-700'];
                                    $colorClass = $colors[$mapel->id % count($colors)];
                                ?>

                                <tr class="group transition-colors duration-150 <?php echo e($rowClass); ?>">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-lg <?php echo e($colorClass); ?> flex items-center justify-center font-bold text-xs border border-white shadow-sm uppercase">
                                                <?php echo e($initials); ?>

                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 flex items-center">
                                                    <?php echo e($mapel->nama_mapel); ?>

                                                    <?php echo $badge; ?>

                                                </div>
                                                <?php if($mapel->kode_mapel): ?>
                                                    <div class="text-xs text-gray-500 font-mono"><?php echo e($mapel->kode_mapel); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-4 align-middle">
                                        <div class="w-full max-w-xs">
                                            <div class="flex justify-between text-xs mb-1">
                                                <span class="font-medium text-gray-500">Progress</span>
                                                <span class="font-bold <?php echo e($textColor); ?>"><?php echo e($progress); ?>%</span>
                                            </div>
                                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                                <div class="<?php echo e($barColor); ?> h-2 rounded-full transition-all duration-1000" style="width: <?php echo e($progress); ?>%"></div>
                                            </div>
                                        </div>
                                    </td>

                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            
                                            <div class="flex bg-gray-50 rounded-lg border border-gray-200 p-1 mr-2">
                                                <a href="<?php echo e(route('sekolah.admin.kegiatan-akademik.cetak.daftar-hadir', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id])); ?>" 
                                                   target="_blank"
                                                   class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded transition" title="Cetak Daftar Hadir">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                </a>
                                                <div class="w-px bg-gray-200 mx-0.5"></div>
                                                <a href="<?php echo e(route('sekolah.admin.kegiatan-akademik.cetak.nilai', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id])); ?>" 
                                                   target="_blank"
                                                   class="p-1.5 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded transition" title="Cetak Ledger Nilai">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                </a>
                                            </div>

                                            
                                            <a href="<?php echo e(route('sekolah.admin.kegiatan-akademik.kelola.nilai', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id])); ?>" 
                                               class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 transition shadow-sm hover:shadow-indigo-200">
                                                Input Nilai
                                                <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            <p class="text-gray-500 text-sm">Tidak ada mata pelajaran ditemukan.</p>
                                            <?php if(request('search')): ?>
                                                <a href="<?php echo e(url()->current()); ?>" class="mt-2 text-indigo-600 hover:underline text-xs">Reset Pencarian</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/laporan-nilai/mapel.blade.php ENDPATH**/ ?>