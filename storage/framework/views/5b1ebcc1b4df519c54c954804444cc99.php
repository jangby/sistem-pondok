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
                Kelola Ujian: <?php echo e($jadwal->mapel->nama_mapel); ?>

            </h2>
            <a href="<?php echo e(route('pendidikan.admin.ujian.index')); ?>" class="text-sm text-gray-600 hover:text-gray-900">Kembali</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ tab: 'absensi' }">
            
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800"><?php echo e($jadwal->mapel->nama_kitab); ?></h3>
                    <p class="text-sm text-gray-500">
                        <?php echo e($jadwal->mustawa->nama); ?> • 
                        <?php echo e(ucfirst($jadwal->jenis_ujian)); ?> <?php echo e(ucfirst($jadwal->semester)); ?> •
                        <?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->isoFormat('D MMMM Y')); ?>

                    </p>
                </div>
                <div class="text-right">
                    <span class="block text-xs text-gray-400 uppercase font-bold">Pengawas</span>
                    <span class="block font-bold text-emerald-600"><?php echo e($jadwal->pengawas->nama_lengkap); ?></span>
                </div>
            </div>

            
            <div class="flex gap-4 mb-6 border-b border-gray-200">
                <button @click="tab = 'absensi'" :class="tab === 'absensi' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 px-4 border-b-2 font-bold text-sm transition">
                    Absensi Ujian
                </button>
                <button @click="tab = 'nilai'" :class="tab === 'nilai' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 px-4 border-b-2 font-bold text-sm transition">
                    Input Nilai (<?php echo e(ucfirst($jadwal->kategori_tes)); ?>)
                </button>
                <button @click="tab = 'ledger'" :class="tab === 'ledger' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 px-4 border-b-2 font-bold text-sm transition">
                    Ledger & Export
                </button>
            </div>

            
            <div x-show="tab === 'absensi'" class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                <form action="<?php echo e(route('pendidikan.admin.ujian.attendance', $jadwal->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <table class="min-w-full divide-y divide-gray-200 mb-4">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-bold text-gray-500 uppercase">Nama Santri</th>
                                <th class="text-center text-xs font-bold text-gray-500 uppercase">Hadir</th>
                                <th class="text-center text-xs font-bold text-gray-500 uppercase">Izin</th>
                                <th class="text-center text-xs font-bold text-gray-500 uppercase">Sakit</th>
                                <th class="text-center text-xs font-bold text-gray-500 uppercase">Alpha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $status = $absensiData[$santri->id] ?? 'A'; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 text-sm font-bold"><?php echo e($santri->full_name); ?></td>
                                    <td class="text-center"><input type="radio" name="attendance[<?php echo e($santri->id); ?>]" value="H" <?php echo e($status == 'H' ? 'checked' : ''); ?> class="text-emerald-600 focus:ring-emerald-500"></td>
                                    <td class="text-center"><input type="radio" name="attendance[<?php echo e($santri->id); ?>]" value="I" <?php echo e($status == 'I' ? 'checked' : ''); ?> class="text-blue-600 focus:ring-blue-500"></td>
                                    <td class="text-center"><input type="radio" name="attendance[<?php echo e($santri->id); ?>]" value="S" <?php echo e($status == 'S' ? 'checked' : ''); ?> class="text-orange-600 focus:ring-orange-500"></td>
                                    <td class="text-center"><input type="radio" name="attendance[<?php echo e($santri->id); ?>]" value="A" <?php echo e($status == 'A' ? 'checked' : ''); ?> class="text-red-600 focus:ring-red-500"></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-emerald-700 transition">Simpan Absensi</button>
                    </div>
                </form>
            </div>

            
            <div x-show="tab === 'nilai'" class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6" style="display: none;">
                <div class="mb-4 bg-blue-50 p-3 rounded-lg text-blue-700 text-xs flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Anda sedang menginput nilai untuk kategori: <strong><?php echo e(strtoupper($jadwal->kategori_tes)); ?></strong>.
                </div>

                <form action="<?php echo e(route('pendidikan.admin.ujian.grades', $jadwal->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 
                                $record = $nilaiData[$santri->id] ?? null;
                                $nilaiAwal = 0;
                                if($record) {
                                    if($jadwal->kategori_tes == 'tulis') $nilaiAwal = $record->nilai_tulis;
                                    elseif($jadwal->kategori_tes == 'lisan') $nilaiAwal = $record->nilai_lisan;
                                    elseif($jadwal->kategori_tes == 'praktek') $nilaiAwal = $record->nilai_praktek;
                                }
                            ?>
                            <div class="flex items-center justify-between p-3 border rounded-lg bg-gray-50">
                                <label class="text-sm font-bold text-gray-700 w-2/3"><?php echo e($santri->full_name); ?></label>
                                <input type="number" name="grades[<?php echo e($santri->id); ?>]" value="<?php echo e($nilaiAwal); ?>" min="0" max="100" step="0.01"
                                    class="w-24 rounded-md border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-right font-mono font-bold">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Simpan Nilai</button>
                    </div>
                </form>
            </div>

            
            <div x-show="tab === 'ledger'" class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6 text-center" style="display: none;">
                <div class="max-w-md mx-auto">
                    <svg class="w-16 h-16 text-emerald-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Export Data Nilai</h3>
                    <p class="text-gray-500 text-sm mb-6">Unduh rekapitulasi nilai untuk ujian ini dalam format PDF atau Excel.</p>
                    
                    <div class="flex justify-center gap-4">
                        <a href="<?php echo e(route('pendidikan.admin.ujian.pdf', $jadwal->id)); ?>" class="bg-red-500 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 hover:bg-red-600 transition shadow-lg shadow-red-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Download PDF
                        </a>
                        <a href="<?php echo e(route('pendidikan.admin.ujian.excel', $jadwal->id)); ?>" class="bg-green-600 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 hover:bg-green-700 transition shadow-lg shadow-green-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export Excel
                        </a>
                    </div>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/ujian/show.blade.php ENDPATH**/ ?>