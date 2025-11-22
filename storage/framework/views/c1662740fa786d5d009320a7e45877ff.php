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

    <div class="min-h-screen bg-white pb-20">
        
        
        <div class="bg-white px-6 py-4 flex items-center gap-4 border-b border-gray-100 sticky top-0 z-30">
            <a href="<?php echo e(route('pengurus.santri.index')); ?>" class="text-gray-500 hover:text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-800">Edit Data Santri</h1>
        </div>

        <form action="<?php echo e(route('pengurus.santri.update', $santri->id)); ?>" method="POST" class="p-6 space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">NIS</label>
                        <input type="text" name="nis" value="<?php echo e(old('nis', $santri->nis)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('nis')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('nis'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">RFID UID</label>
                        <input type="text" name="rfid_uid" value="<?php echo e(old('rfid_uid', $santri->rfid_uid)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 bg-gray-50">
                        <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('rfid_uid')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('rfid_uid'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Nama Lengkap</label>
                    <input type="text" name="full_name" value="<?php echo e(old('full_name', $santri->full_name)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                </div>
            </div>

            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kelas</label>
                    <select name="kelas_id" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">- Pilih -</option>
                        <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k->id); ?>" <?php echo e(old('kelas_id', $santri->kelas_id) == $k->id ? 'selected' : ''); ?>>
                                <?php echo e($k->nama_kelas); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="active" <?php echo e(old('status', $santri->status) == 'active' ? 'selected' : ''); ?>>Aktif</option>
                        <option value="graduated" <?php echo e(old('status', $santri->status) == 'graduated' ? 'selected' : ''); ?>>Lulus</option>
                        <option value="moved" <?php echo e(old('status', $santri->status) == 'moved' ? 'selected' : ''); ?>>Pindah</option>
                    </select>
                </div>
            </div>

            
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Akun Orang Tua</label>
                <select name="orang_tua_id" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500" required>
                    <option value="">- Pilih Wali -</option>
                    <?php $__currentLoopData = $orangTuas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ortu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($ortu->id); ?>" <?php echo e(old('orang_tua_id', $santri->orang_tua_id) == $ortu->id ? 'selected' : ''); ?>>
                            <?php echo e($ortu->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <hr class="border-dashed border-gray-200">

            
            <h3 class="font-bold text-gray-800">Data Pribadi</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $santri->tempat_lahir)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $santri->tanggal_lahir?->format('Y-m-d'))); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="Laki-laki" <?php echo e(old('jenis_kelamin', $santri->jenis_kelamin) == 'Laki-laki' ? 'selected' : ''); ?>>Laki-laki</option>
                        <option value="Perempuan" <?php echo e(old('jenis_kelamin', $santri->jenis_kelamin) == 'Perempuan' ? 'selected' : ''); ?>>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Gol. Darah</label>
                    <select name="golongan_darah" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">-</option>
                        <option value="A" <?php echo e(old('golongan_darah', $santri->golongan_darah) == 'A' ? 'selected' : ''); ?>>A</option>
                        <option value="B" <?php echo e(old('golongan_darah', $santri->golongan_darah) == 'B' ? 'selected' : ''); ?>>B</option>
                        <option value="AB" <?php echo e(old('golongan_darah', $santri->golongan_darah) == 'AB' ? 'selected' : ''); ?>>AB</option>
                        <option value="O" <?php echo e(old('golongan_darah', $santri->golongan_darah) == 'O' ? 'selected' : ''); ?>>O</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Riwayat Penyakit</label>
                <textarea name="riwayat_penyakit" rows="2" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500"><?php echo e(old('riwayat_penyakit', $santri->riwayat_penyakit)); ?></textarea>
            </div>

            <hr class="border-dashed border-gray-200">

            
            <h3 class="font-bold text-gray-800">Alamat & Domisili</h3>
            
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500"><?php echo e(old('alamat', $santri->alamat)); ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">RT</label>
                        <input type="text" name="rt" value="<?php echo e(old('rt', $santri->rt)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">RW</label>
                        <input type="text" name="rw" value="<?php echo e(old('rw', $santri->rw)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kode Pos</label>
                    <input type="text" name="kode_pos" value="<?php echo e(old('kode_pos', $santri->kode_pos)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kelurahan/Desa</label>
                    <input type="text" name="desa" value="<?php echo e(old('desa', $santri->desa)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Kecamatan</label>
                    <input type="text" name="kecamatan" value="<?php echo e(old('kecamatan', $santri->kecamatan)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
            </div>

            <hr class="border-dashed border-gray-200">

            
            <h3 class="font-bold text-gray-800">Data Ayah (EMIS)</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Nama Ayah</label>
                    <input type="text" name="nama_ayah" value="<?php echo e(old('nama_ayah', $santri->nama_ayah)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">NIK Ayah</label>
                        <input type="number" name="nik_ayah" value="<?php echo e(old('nik_ayah', $santri->nik_ayah)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tahun Lahir</label>
                        <input type="number" name="thn_lahir_ayah" value="<?php echo e(old('thn_lahir_ayah', $santri->thn_lahir_ayah)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Pendidikan</label>
                        <select name="pendidikan_ayah" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                            <option value="">- Pilih -</option>
                            <?php $__currentLoopData = ['SD','SMP','SMA','D3','S1','S2','S3','Tidak Sekolah']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($edu); ?>" <?php echo e(old('pendidikan_ayah', $santri->pendidikan_ayah) == $edu ? 'selected' : ''); ?>><?php echo e($edu); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Pekerjaan</label>
                        <input type="text" name="pekerjaan_ayah" value="<?php echo e(old('pekerjaan_ayah', $santri->pekerjaan_ayah)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Penghasilan / Bulan</label>
                    <select name="penghasilan_ayah" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">- Pilih Range -</option>
                        <?php $__currentLoopData = ['< 1 Juta', '1 - 3 Juta', '3 - 5 Juta', '> 5 Juta']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $income): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($income); ?>" <?php echo e(old('penghasilan_ayah', $santri->penghasilan_ayah) == $income ? 'selected' : ''); ?>><?php echo e($income); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <hr class="border-dashed border-gray-200">

            
            <h3 class="font-bold text-gray-800">Data Ibu (EMIS)</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Nama Ibu</label>
                    <input type="text" name="nama_ibu" value="<?php echo e(old('nama_ibu', $santri->nama_ibu)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">NIK Ibu</label>
                        <input type="number" name="nik_ibu" value="<?php echo e(old('nik_ibu', $santri->nik_ibu)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Tahun Lahir</label>
                        <input type="number" name="thn_lahir_ibu" value="<?php echo e(old('thn_lahir_ibu', $santri->thn_lahir_ibu)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Pendidikan</label>
                        <select name="pendidikan_ibu" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                            <option value="">- Pilih -</option>
                            <?php $__currentLoopData = ['SD','SMP','SMA','D3','S1','S2','S3','Tidak Sekolah']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($edu); ?>" <?php echo e(old('pendidikan_ibu', $santri->pendidikan_ibu) == $edu ? 'selected' : ''); ?>><?php echo e($edu); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Pekerjaan</label>
                        <input type="text" name="pekerjaan_ibu" value="<?php echo e(old('pekerjaan_ibu', $santri->pekerjaan_ibu)); ?>" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase mb-1 block">Penghasilan / Bulan</label>
                    <select name="penghasilan_ibu" class="w-full rounded-xl border-gray-200 focus:ring-emerald-500">
                        <option value="">- Pilih Range -</option>
                        <?php $__currentLoopData = ['< 1 Juta', '1 - 3 Juta', '3 - 5 Juta', '> 5 Juta']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $income): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($income); ?>" <?php echo e(old('penghasilan_ibu', $santri->penghasilan_ibu) == $income ? 'selected' : ''); ?>><?php echo e($income); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            
            <div class="pt-4">
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-200 active:scale-95 transition">
                    Perbarui Data
                </button>
            </div>
        </form>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/santri/edit.blade.php ENDPATH**/ ?>