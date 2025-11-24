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

    <div class="min-h-screen bg-gray-50 pb-24" x-data="{ tab: 'nilai' }"> 
        
        
        <div class="bg-white shadow-sm sticky top-0 z-30">
            <div class="bg-emerald-600 px-6 pt-6 pb-12 rounded-b-[25px]">
                <div class="flex items-center gap-4 text-white">
                    <a href="<?php echo e(route('ustadz.ujian.index')); ?>" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition backdrop-blur-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div class="flex-grow">
                        <h1 class="text-lg font-bold leading-tight"><?php echo e($jadwal->mapel->nama_mapel); ?></h1>
                        <p class="text-xs text-emerald-100 opacity-90">
                            <?php echo e($jadwal->mustawa->nama); ?> • <?php echo e(ucfirst($jadwal->kategori_tes)); ?>

                        </p>
                    </div>
                </div>
            </div>

            
            <div class="px-6 -mt-6 pb-4">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 p-1 flex">
                    <button @click="tab = 'nilai'" :class="tab === 'nilai' ? 'bg-emerald-100 text-emerald-700 shadow-sm' : 'text-gray-500'" class="flex-1 py-2.5 rounded-lg text-xs font-bold transition-all">
                        Input Nilai
                    </button>
                    <button @click="tab = 'absensi'" :class="tab === 'absensi' ? 'bg-blue-100 text-blue-700 shadow-sm' : 'text-gray-500'" class="flex-1 py-2.5 rounded-lg text-xs font-bold transition-all">
                        Absensi Ujian
                    </button>
                </div>
            </div>
        </div>

        <div class="px-4 mt-2">
            
            
            <div x-show="tab === 'nilai'" x-transition>
                <form action="<?php echo e(route('ustadz.ujian.nilai', $jadwal->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    
                    <?php if(strtolower($jadwal->kategori_tes) == 'tulis'): ?>
                    <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-xl flex items-center justify-between mb-4 mt-2">
                        <div>
                            <h4 class="text-xs font-bold text-emerald-800">Total Tatap Muka</h4>
                            <p class="text-[10px] text-emerald-600">Digunakan untuk hitung % kehadiran</p>
                        </div>
                        <div class="flex flex-col items-end">
                             <input type="number" name="total_meetings" value="<?php echo e($totalPertemuan); ?>" 
                                class="w-16 text-center text-sm font-bold text-emerald-700 border-emerald-200 rounded-lg focus:border-emerald-500 p-1.5 bg-white shadow-sm"
                                min="1" required>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="space-y-4 pb-20 mt-4"> 
                        
                        <div class="flex justify-between items-center px-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            <span>Santri</span>
                            <div class="flex gap-4 text-right">
                                <span class="w-20">Nilai</span>
                                <?php if(strtolower($jadwal->kategori_tes) == 'tulis'): ?>
                                    <span class="w-20 text-emerald-600">Jml Hadir</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 
                                $record = $nilai[$santri->id] ?? null;
                                
                                // Nilai Ujian
                                $val = 0;
                                if($record) {
                                    if(strtolower($jadwal->kategori_tes) == 'tulis') $val = $record->nilai_tulis;
                                    elseif(strtolower($jadwal->kategori_tes) == 'lisan') $val = $record->nilai_lisan;
                                    elseif(strtolower($jadwal->kategori_tes) == 'praktek') $val = $record->nilai_praktek;
                                }
                                $val = $val == 0 ? '' : $val;

                                // Nilai Kehadiran (Diambil dari Controller yg sudah menghitung Logic Saved vs Log)
                                $valHadir = $dataKehadiran[$santri->id] ?? 0;
                            ?>

                            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between gap-3">
                                <div class="flex-grow min-w-0">
                                    <h4 class="text-sm font-bold text-gray-800 truncate"><?php echo e($santri->full_name); ?></h4>
                                    <p class="text-[10px] text-gray-400"><?php echo e($santri->nis); ?></p>
                                </div>

                                <div class="flex gap-3">
                                    
                                    <div class="flex flex-col items-center">
                                        <input type="number" name="grades[<?php echo e($santri->id); ?>]" value="<?php echo e($val); ?>" 
                                            class="w-20 text-center text-lg font-bold text-gray-700 border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 p-2 bg-gray-50"
                                            placeholder="0" min="0" max="100" step="0.01">
                                    </div>

                                    
                                    <?php if(strtolower($jadwal->kategori_tes) == 'tulis'): ?>
                                    <div class="flex flex-col items-center">
                                        <input type="number" name="attendance_count[<?php echo e($santri->id); ?>]" value="<?php echo e($valHadir); ?>" 
                                            class="w-20 text-center text-lg font-bold text-emerald-700 border-emerald-100 rounded-lg focus:border-emerald-500 focus:ring-emerald-500 p-2 bg-emerald-50"
                                            placeholder="0" min="0">
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 z-40 max-w-md mx-auto shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
                        <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-emerald-700 transition flex justify-center items-center gap-2 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>

            
            <div x-show="tab === 'absensi'" x-transition style="display: none;">
                <form action="<?php echo e(route('ustadz.ujian.absensi', $jadwal->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-3 pb-20">
                         <div class="bg-blue-50 text-blue-800 text-xs p-3 rounded-lg mb-2">
                            Ini adalah absensi kehadiran peserta <b>saat ujian berlangsung</b>.
                        </div>
                        <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $status = $absensi[$santri->id] ?? 'H'; ?> 
                            
                            <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                                <div class="mb-2 border-b border-gray-50 pb-2">
                                    <h4 class="text-sm font-bold text-gray-800"><?php echo e($santri->full_name); ?></h4>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="attendance[<?php echo e($santri->id); ?>]" value="H" class="peer hidden" <?php echo e($status == 'H' ? 'checked' : ''); ?>>
                                        <div class="text-center py-2 rounded-lg text-xs font-bold bg-gray-50 text-gray-400 peer-checked:bg-emerald-100 peer-checked:text-emerald-700 transition">Hadir</div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="attendance[<?php echo e($santri->id); ?>]" value="I" class="peer hidden" <?php echo e($status == 'I' ? 'checked' : ''); ?>>
                                        <div class="text-center py-2 rounded-lg text-xs font-bold bg-gray-50 text-gray-400 peer-checked:bg-blue-100 peer-checked:text-blue-700 transition">Izin</div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="attendance[<?php echo e($santri->id); ?>]" value="S" class="peer hidden" <?php echo e($status == 'S' ? 'checked' : ''); ?>>
                                        <div class="text-center py-2 rounded-lg text-xs font-bold bg-gray-50 text-gray-400 peer-checked:bg-orange-100 peer-checked:text-orange-700 transition">Sakit</div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="attendance[<?php echo e($santri->id); ?>]" value="A" class="peer hidden" <?php echo e($status == 'A' ? 'checked' : ''); ?>>
                                        <div class="text-center py-2 rounded-lg text-xs font-bold bg-gray-50 text-gray-400 peer-checked:bg-red-100 peer-checked:text-red-700 transition">Alpha</div>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 z-40 max-w-md mx-auto">
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-blue-700 transition">
                            Simpan Absensi Ujian
                        </button>
                    </div>
                </form>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/ustadz/ujian/show.blade.php ENDPATH**/ ?>