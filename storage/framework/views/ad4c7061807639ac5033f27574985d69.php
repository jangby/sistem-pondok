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

    <div class="min-h-screen bg-gray-50 pb-20">
        
        
        <div class="bg-emerald-600 pt-6 pb-24 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('pengurus.kios.index')); ?>" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">Rekap Kehadiran</h1>
                        <p class="text-emerald-100 text-xs font-medium">Laporan Kinerja Penjaga Gerbang</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="px-5 -mt-16 relative z-20 space-y-6 max-w-5xl mx-auto">
            
            
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-5 flex flex-col sm:flex-row justify-between gap-4 items-center">
                <form action="<?php echo e(route('pengurus.rekap-gerbang.index')); ?>" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <select name="bulan" class="bg-gray-50 border border-gray-200 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 w-full sm:w-32 p-2.5">
                        <?php for($i=1; $i<=12; $i++): ?>
                            <option value="<?php echo e(sprintf('%02d', $i)); ?>" <?php echo e($bulan == sprintf('%02d', $i) ? 'selected' : ''); ?>>Bulan <?php echo e($i); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="tahun" class="bg-gray-50 border border-gray-200 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 w-full sm:w-28 p-2.5">
                        <?php for($t=date('Y'); $t>=date('Y')-2; $t--): ?>
                            <option value="<?php echo e($t); ?>" <?php echo e($tahun == $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" class="bg-emerald-100 text-emerald-700 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-emerald-200 transition">Filter</button>
                </form>

                <a href="<?php echo e(route('pengurus.rekap-gerbang.pdf', ['bulan' => $bulan, 'tahun' => $tahun])); ?>" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Unduh Laporan PDF
                </a>
            </div>

            
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-lg">Periode: <?php echo e($namaBulan); ?></h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100/50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 font-bold border-b border-gray-100">Nama Santri</th>
                                <th class="p-4 font-bold border-b border-gray-100 text-center">Kehadiran Pagi</th>
                                <th class="p-4 font-bold border-b border-gray-100 text-center">Kehadiran Sore</th>
                                <th class="p-4 font-bold border-b border-gray-100 text-center">Total Dinas</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <?php $__empty_1 = true; $__currentLoopData = $rekap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                                    <td class="p-4 font-bold text-gray-800"><?php echo e($data['nama']); ?></td>
                                    <td class="p-4 text-center">
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg font-bold"><?php echo e($data['hadir_pagi']); ?>x</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-lg font-bold"><?php echo e($data['hadir_sore']); ?>x</span>
                                    </td>
                                    <td class="p-4 text-center font-bold text-gray-500"><?php echo e($data['total_tugas']); ?> Hari</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-400 italic">Belum ada data absensi untuk bulan ini.</td>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/absensi/gerbang/rekap.blade.php ENDPATH**/ ?>