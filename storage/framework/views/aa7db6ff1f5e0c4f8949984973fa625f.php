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
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-800 tracking-tight">
                    Manajemen Unit Sekolah
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola data sekolah formal (MTS, MA, SMK) di lingkungan pondok.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-500 shadow-sm">
                    Total Unit: <span class="text-indigo-600 font-black text-sm ml-1"><?php echo e($sekolahs->total()); ?></span>
                </span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                
                <div class="relative w-full sm:w-96 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <form method="GET">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition" 
                               placeholder="Cari sekolah atau kepala sekolah...">
                    </form>
                </div>

                
                <button onclick="openModal('create')" 
                   class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-indigo-200 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Unit Baru
                </button>
            </div>

            
            <?php if($sekolahs->count() > 0): ?>
                <div class="grid grid-cols-1 gap-6">
                    <?php $__currentLoopData = $sekolahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sekolah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $colors = [
                                'SD' => 'bg-blue-100 text-blue-600 border-blue-200',
                                'MI' => 'bg-green-100 text-green-600 border-green-200',
                                'SMP' => 'bg-indigo-100 text-indigo-600 border-indigo-200',
                                'MTS' => 'bg-teal-100 text-teal-600 border-teal-200',
                                'SMA' => 'bg-gray-100 text-gray-600 border-gray-200',
                                'MA' => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                                'SMK' => 'bg-orange-100 text-orange-600 border-orange-200',
                                'Pondok' => 'bg-purple-100 text-purple-600 border-purple-200',
                            ];
                            $badgeColor = $colors[$sekolah->tingkat] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                            $initial = substr($sekolah->nama_sekolah, 0, 1);
                        ?>

                        <div class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md hover:border-indigo-200 transition-all duration-200 relative overflow-hidden">
                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                                
                                
                                <div class="flex items-center gap-5 w-full md:w-auto">
                                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl <?php echo e($badgeColor); ?> flex items-center justify-center text-2xl font-black border shadow-sm">
                                        <?php echo e($initial); ?>

                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-3 mb-1">
                                            <h3 class="text-xl font-black text-gray-800 group-hover:text-indigo-600 transition-colors"><?php echo e($sekolah->nama_sekolah); ?></h3>
                                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide <?php echo e($badgeColor); ?>">
                                                <?php echo e($sekolah->tingkat); ?>

                                            </span>
                                        </div>
                                        <div class="space-y-1">
                                            <?php if($sekolah->kepala_sekolah): ?>
                                                <p class="text-sm text-gray-600 flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                    <span class="font-semibold">Kepsek:</span> <?php echo e($sekolah->kepala_sekolah); ?>

                                                </p>
                                            <?php endif; ?>
                                            <p class="text-sm text-gray-500 flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                <?php echo e($sekolah->alamat ?? '-'); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 pt-4 md:pt-0 border-gray-100">
                                    
                                    <div class="flex gap-6 text-center">
                                        <div>
                                            <div class="text-lg font-black text-gray-800"><?php echo e($sekolah->guru_count); ?></div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Guru</div>
                                        </div>
                                        <div class="w-px h-8 bg-gray-200"></div>
                                        <div>
                                            <div class="text-lg font-black text-gray-800"><?php echo e($sekolah->siswa_count); ?></div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Siswa</div>
                                        </div>
                                    </div>

                                    
                                    <div class="flex items-center gap-2">
                                        
                                        <button onclick="openModal('edit', <?php echo e($sekolah); ?>)" 
                                                class="p-2.5 bg-white border border-gray-200 rounded-xl text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 transition-all" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        
                                        <form action="<?php echo e(route('sekolah.superadmin.sekolah.destroy', $sekolah->id)); ?>" method="POST" onsubmit="return confirm('Yakin hapus unit sekolah ini? Data guru & siswa terkait mungkin akan error.');">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="p-2.5 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-red-600 hover:bg-red-50 hover:border-red-200 transition-all" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="mt-6"><?php echo e($sekolahs->links()); ?></div>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl shadow-sm border border-gray-200 text-center">
                    <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-800">Belum Ada Data</h3>
                    <button onclick="openModal('create')" class="mt-4 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition">
                        + Tambah Sekolah
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div id="sekolahModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95" id="modalPanel">
                
                <div class="bg-white px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-black text-gray-800" id="modalTitle">Tambah Sekolah</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Lengkapi data unit pendidikan.</p>
                    </div>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form id="sekolahForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <div id="methodSpoof"></div>

                    <div class="px-6 py-6 space-y-4">
                        
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 md:col-span-1">
                                <label for="tingkat" class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenjang</label>
                                <select name="tingkat" id="tingkat" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <option value="">-- Pilih --</option>
                                    <option value="SD">SD / MI</option>
                                    <option value="SMP">SMP / MTS</option>
                                    <option value="SMA">SMA / MA / SMK</option>
                                    <option value="Pondok">Pondok Pesantren</option>
                                    
                                    <option value="MI">MI</option>
                                    <option value="MTS">MTS</option>
                                    <option value="MA">MA</option>
                                    <option value="SMK">SMK</option>
                                </select>
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label for="nama_sekolah" class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Unit</label>
                                <input type="text" name="nama_sekolah" id="nama_sekolah" required placeholder="Cth: Al-Hikmah"
                                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                        </div>

                        
                        <div>
                            <label for="kepala_sekolah" class="block text-xs font-bold text-gray-500 uppercase mb-1">Kepala Sekolah</label>
                            <input type="text" name="kepala_sekolah" id="kepala_sekolah" placeholder="Nama Lengkap & Gelar"
                                   class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        
                        <div>
                            <label for="alamat" class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="2" placeholder="Lokasi sekolah..."
                                      class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                        </div>

                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="no_telp" class="block text-xs font-bold text-gray-500 uppercase mb-1">No. Telp</label>
                                <input type="text" name="no_telp" id="no_telp" placeholder="08..."
                                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label>
                                <input type="email" name="email" id="email" placeholder="admin@..."
                                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3 border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-md hover:bg-indigo-700 sm:w-auto transition-all">
                            Simpan Data
                        </button>
                        <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        const modal = document.getElementById('sekolahModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        const form = document.getElementById('sekolahForm');
        const title = document.getElementById('modalTitle');
        const methodSpoof = document.getElementById('methodSpoof');
        
        const baseUpdateUrl = "<?php echo e(route('sekolah.superadmin.sekolah.index')); ?>";
        const storeUrl = "<?php echo e(route('sekolah.superadmin.sekolah.store')); ?>";

        function openModal(mode, data = null) {
            form.reset();
            methodSpoof.innerHTML = '';

            if (mode === 'create') {
                title.innerText = 'Tambah Sekolah Baru';
                form.action = storeUrl;
            } else if (mode === 'edit' && data) {
                form.action = `/sekolah-superadmin/sekolah/${id}`;
                methodInput.value = 'PUT';
                modalTitle.textContent = 'Edit Unit Sekolah';
                
                document.getElementById('nama_sekolah').value = data.nama_sekolah;
                document.getElementById('tingkat').value = data.tingkat;
                document.getElementById('kepala_sekolah').value = data.kepala_sekolah || '';

                // --- TAMBAHAN BARU ---
                document.getElementById('alamat').value = data.alamat || '';
                document.getElementById('email').value = data.email || '';
                document.getElementById('no_telp').value = data.no_telp || '';
                // ---------------------
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'scale-95');
                panel.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeModal() {
            backdrop.classList.add('opacity-0');
            panel.classList.remove('opacity-100', 'scale-100');
            panel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }
        
        <?php if($errors->any()): ?>
            document.addEventListener('DOMContentLoaded', function() {
                openModal('create'); 
                // Repopulate old inputs
                document.getElementById('nama_sekolah').value = "<?php echo e(old('nama_sekolah')); ?>";
                document.getElementById('tingkat').value = "<?php echo e(old('tingkat')); ?>";
                document.getElementById('kepala_sekolah').value = "<?php echo e(old('kepala_sekolah')); ?>";
                document.getElementById('alamat').value = "<?php echo e(old('alamat')); ?>";
                document.getElementById('no_telp').value = "<?php echo e(old('no_telp')); ?>";
                document.getElementById('email').value = "<?php echo e(old('email')); ?>";
            });
        <?php endif; ?>
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/superadmin/sekolah/index.blade.php ENDPATH**/ ?>