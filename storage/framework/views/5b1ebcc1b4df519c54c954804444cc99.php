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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ tab: 'nilai' }"> 
            
            
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
                    <span class="block font-bold text-emerald-600"><?php echo e($jadwal->pengawas->nama_lengkap ?? '-'); ?></span>
                </div>
            </div>

            
            <div class="flex gap-4 mb-6 border-b border-gray-200">
                <button @click="tab = 'nilai'" :class="tab === 'nilai' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 px-4 border-b-2 font-bold text-sm transition">
                    Input Nilai (<?php echo e(ucfirst($jadwal->kategori_tes)); ?>)
                </button>
                <button @click="tab = 'absensi'" :class="tab === 'absensi' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 px-4 border-b-2 font-bold text-sm transition">
                    Absensi Ujian
                </button>
                <button @click="tab = 'ledger'" :class="tab === 'ledger' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="pb-2 px-4 border-b-2 font-bold text-sm transition">
                    Ledger & Export
                </button>
            </div>

            
            <div x-show="tab === 'nilai'" class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
                
                <form action="<?php echo e(route('pendidikan.admin.ujian.grades', $jadwal->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    
                    <?php if($jadwal->kategori_tes == 'tulis'): ?>
                    <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl flex items-center justify-between mb-6">
                        <div>
                            <h4 class="text-sm font-bold text-blue-800">Parameter Kehadiran (Semester Ini)</h4>
                            <p class="text-xs text-blue-600">Masukkan total tatap muka untuk menghitung nilai kehadiran otomatis.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="text-xs font-bold text-blue-700 uppercase">Total Tatap Muka:</label>
                            
                            <input type="number" name="total_meetings" value="<?php echo e($totalPertemuan > 0 ? $totalPertemuan : 14); ?>" 
                                class="w-24 text-center font-bold text-blue-700 border-blue-200 rounded-lg focus:border-blue-500 focus:ring-blue-500"
                                min="1" required>
                        </div>
                    </div>
                    <?php else: ?>
                    
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg text-gray-600 text-sm border border-gray-100 flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="font-bold">Input Nilai: Kategori <?php echo e(strtoupper($jadwal->kategori_tes)); ?></p>
                            <p class="opacity-80 mt-1">Hanya input nilai ujian. Nilai kehadiran diambil dari data ujian Tulis.</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <div class="flex justify-between items-center px-4 mb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <span class="w-1/2">Identitas Santri</span>
                        <div class="flex gap-4 w-1/2 justify-end">
                            <span class="w-32 text-center">Nilai <?php echo e(ucfirst($jadwal->kategori_tes)); ?></span>
                            
                            
                            <?php if($jadwal->kategori_tes == 'tulis'): ?>
                                <span class="w-32 text-center text-emerald-600">Jml Hadir</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 
                                $record = $nilai[$santri->id] ?? null; // Variabel dari controller admin biasanya $nilai atau $nilaiData
                                
                                // 1. Nilai Ujian Utama
                                $nilaiAwal = 0;
                                if($record) {
                                    if($jadwal->kategori_tes == 'tulis') $nilaiAwal = $record->nilai_tulis;
                                    elseif($jadwal->kategori_tes == 'lisan') $nilaiAwal = $record->nilai_lisan;
                                    elseif($jadwal->kategori_tes == 'praktek') $nilaiAwal = $record->nilai_praktek;
                                }
                                $nilaiAwal = $nilaiAwal == 0 ? '' : $nilaiAwal;

                                // 2. Nilai Kehadiran (Raw Count)
                                // Kita ambil dari variabel $dataKehadiran yang dikirim controller
                                $valHadir = $dataKehadiran[$santri->id] ?? 0;
                            ?>

                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl bg-white hover:border-emerald-300 transition shadow-sm">
                                
                                <div class="w-1/2 pr-4">
                                    <div class="font-bold text-gray-800"><?php echo e($santri->full_name); ?></div>
                                    <div class="text-xs text-gray-500 mt-0.5">NIS: <?php echo e($santri->nis); ?></div>
                                </div>

                                
                                <div class="flex gap-4 w-1/2 justify-end">
                                    
                                    <div class="w-32">
                                        <input type="number" name="grades[<?php echo e($santri->id); ?>]" value="<?php echo e($nilaiAwal); ?>" min="0" max="100" step="0.01"
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-center font-bold text-gray-700 bg-gray-50 py-2"
                                            placeholder="0">
                                    </div>

                                    
                                    <?php if($jadwal->kategori_tes == 'tulis'): ?>
                                    <div class="w-32 relative">
                                        <input type="number" name="attendance_count[<?php echo e($santri->id); ?>]" value="<?php echo e($valHadir); ?>" min="0"
                                            class="w-full rounded-lg border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 text-center font-bold text-emerald-700 bg-emerald-50 py-2"
                                            placeholder="0">
                                        <div class="absolute right-2 top-2.5 text-[10px] text-emerald-400 pointer-events-none">Hari</div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="flex justify-end mt-8 border-t border-gray-100 pt-6">
                        <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            
            <div x-show="tab === 'absensi'" class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6" style="display: none;">
                <form action="<?php echo e(route('pendidikan.admin.ujian.attendance', $jadwal->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4 text-sm text-gray-500">
                        Absensi kehadiran peserta pada saat pelaksanaan ujian <b><?php echo e($jadwal->mapel->nama_mapel); ?></b>.
                    </div>
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
                                <?php $status = $absensi[$santri->id] ?? 'A'; ?> 
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
                        <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-emerald-700 transition">Simpan Absensi Ujian</button>
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