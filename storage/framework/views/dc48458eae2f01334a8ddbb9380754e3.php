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
                <h2 class="font-black text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                    <span class="text-3xl">👨‍🏫</span> Manajemen Guru
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola data tenaga pendidik dan penugasan mengajarnya.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-500 shadow-sm">
                    Total Guru: <span class="text-amber-600 font-black text-sm ml-1"><?php echo e($users->total()); ?></span>
                </span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                
                <div class="relative w-full sm:w-96 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-amber-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <form method="GET">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm shadow-sm transition" 
                               placeholder="Cari nama, NIP, atau email...">
                    </form>
                </div>

                
                <button onclick="openModal('create')" 
                   class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-amber-200 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Tambah Guru Baru
                </button>
            </div>

            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <?php if($users->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Identitas Guru</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kontak & NIP</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Penugasan & Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="group hover:bg-amber-50/30 transition-colors">
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center text-amber-600 font-bold border border-amber-200">
                                                    <?php echo e(substr($user->name, 0, 2)); ?>

                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-800 text-sm"><?php echo e($user->name); ?></div>
                                                    <div class="text-xs text-gray-400">Terdaftar: <?php echo e($user->created_at->format('d M Y')); ?></div>
                                                </div>
                                            </div>
                                        </td>

                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col gap-1">
                                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                                    <span class="font-mono text-xs bg-gray-100 px-1.5 rounded border border-gray-200"><?php echo e($user->guru->nip ?? '-'); ?></span>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                    <?php echo e($user->guru->telepon ?? $user->telepon ?? '-'); ?>

                                                </div>
                                            </div>
                                        </td>

                                        
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-2">
                                                <div class="flex flex-wrap gap-1">
                                                    <?php $__empty_1 = true; $__currentLoopData = $user->sekolahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sekolah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                            <?php echo e($sekolah->nama_sekolah); ?>

                                                        </span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <span class="text-xs text-gray-400 italic">Belum ditugaskan</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <?php if(($user->guru->tipe_jam_kerja ?? '') == 'full_time'): ?>
                                                        <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded border border-green-100">FULL TIME</span>
                                                    <?php elseif(($user->guru->tipe_jam_kerja ?? '') == 'flexi'): ?>
                                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">FLEXI</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>

                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                
                                                <?php
                                                    $userJson = $user->toArray();
                                                    $userJson['guru'] = $user->guru;
                                                    $userJson['sekolah_ids'] = $user->sekolahs->pluck('id')->toArray();
                                                ?>
                                                
                                                <button onclick='openModal("edit", <?php echo json_encode($userJson, 15, 512) ?>)' 
                                                        class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                
                                                <form action="<?php echo e(route('sekolah.superadmin.guru.destroy', $user->id)); ?>" method="POST" class="delete-form">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn-delete p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        <?php echo e($users->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Belum ada Guru</h3>
                        <p class="text-gray-500 text-sm mt-1 mb-4">Mulai tambahkan data pengajar untuk unit sekolah.</p>
                        <button onclick="openModal('create')" class="px-5 py-2 bg-amber-600 text-white text-sm font-bold rounded-lg hover:bg-amber-700 transition">
                            + Tambah Guru
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div id="guruModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl opacity-0 scale-95" id="modalPanel">
                
                <div class="bg-white px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-black text-gray-800" id="modalTitle">Tambah Guru Baru</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Lengkapi biodata dan penugasan.</p>
                    </div>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form id="guruForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <div id="methodSpoof"></div>

                    <div class="px-6 py-6 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        
                        
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 border-b pb-1">1. Informasi Akun Login</h4>
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap (Gelar)</label>
                                    <input type="text" name="name" id="name" required placeholder="Nama Guru"
                                           class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm py-2.5">
                                </div>
                                <div>
                                    <label for="email" class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label>
                                    <input type="email" name="email" id="email" required placeholder="email@sekolah.com"
                                           class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm py-2.5">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="password" class="block text-xs font-bold text-gray-500 uppercase mb-1">Password</label>
                                        <input type="password" name="password" id="password" placeholder="Minimal 8 karakter"
                                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm py-2.5">
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase mb-1">Konfirmasi</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password"
                                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm py-2.5">
                                    </div>
                                </div>
                                <p id="password-hint" class="text-xs text-gray-400 italic hidden">Kosongkan password jika tidak ingin mengubahnya.</p>
                            </div>
                        </div>

                        
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 border-b pb-1">2. Biodata & Pekerjaan</h4>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="nip" class="block text-xs font-bold text-gray-500 uppercase mb-1">NIP / NPY</label>
                                        <input type="text" name="nip" id="nip" placeholder="Nomor Induk"
                                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm py-2.5">
                                    </div>
                                    <div>
                                        <label for="telepon" class="block text-xs font-bold text-gray-500 uppercase mb-1">No. WhatsApp</label>
                                        <input type="text" name="telepon" id="telepon" required placeholder="628..."
                                               class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm py-2.5">
                                    </div>
                                </div>
                                <div>
                                    <label for="alamat" class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat</label>
                                    <textarea name="alamat" id="alamat" rows="2" placeholder="Alamat lengkap..."
                                              class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm"></textarea>
                                </div>
                                <div>
                                    <label for="tipe_jam_kerja" class="block text-xs font-bold text-gray-500 uppercase mb-1">Tipe Jam Kerja</label>
                                    <select name="tipe_jam_kerja" id="tipe_jam_kerja" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm py-2.5">
                                        <option value="full_time">Full-Time (Mengikuti Jam Sekolah)</option>
                                        <option value="flexi">Flexi (Sesuai Jadwal Mengajar)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 border-b pb-1">3. Penugasan Sekolah</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-40 overflow-y-auto p-2 border rounded-xl bg-gray-50">
                                <?php $__currentLoopData = $sekolahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sekolah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center p-2 rounded-lg hover:bg-white hover:shadow-sm cursor-pointer transition-all">
                                        <input type="checkbox" name="sekolah_ids[]" value="<?php echo e($sekolah->id); ?>" 
                                               class="rounded border-gray-300 text-amber-600 shadow-sm focus:ring-amber-500 w-4 h-4">
                                        <span class="ml-2 text-sm text-gray-700 font-medium"><?php echo e($sekolah->nama_sekolah); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Pilih minimal satu sekolah.</p>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3 border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-bold text-white shadow-md hover:bg-amber-700 sm:w-auto transition-all">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const modal = document.getElementById('guruModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        const form = document.getElementById('guruForm');
        const title = document.getElementById('modalTitle');
        const methodSpoof = document.getElementById('methodSpoof');
        const passwordHint = document.getElementById('password-hint');
        
        const baseUpdateUrl = "<?php echo e(url('sekolah-superadmin/guru')); ?>"; 
        const storeUrl = "<?php echo e(route('sekolah.superadmin.guru.store')); ?>";

        function openModal(mode, data = null) {
            form.reset();
            methodSpoof.innerHTML = '';
            
            // Reset Checkboxes
            document.querySelectorAll('input[name="sekolah_ids[]"]').forEach(cb => cb.checked = false);

            if (mode === 'create') {
                title.innerText = 'Tambah Guru Baru';
                form.action = storeUrl;
                passwordHint.classList.add('hidden');
                document.getElementById('password').required = true;
                document.getElementById('password_confirmation').required = true;
            } else if (mode === 'edit' && data) {
                title.innerText = 'Edit Data Guru';
                form.action = `${baseUpdateUrl}/${data.id}`;
                methodSpoof.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                
                document.getElementById('name').value = data.name;
                document.getElementById('email').value = data.email;
                document.getElementById('nip').value = data.guru?.nip || '';
                document.getElementById('telepon').value = data.guru?.telepon || data.telepon || '';
                document.getElementById('alamat').value = data.guru?.alamat || '';
                document.getElementById('tipe_jam_kerja').value = data.guru?.tipe_jam_kerja || 'full_time';

                // Populate Checkboxes (Sekolah IDs)
                if (data.sekolah_ids) {
                    data.sekolah_ids.forEach(id => {
                        const checkbox = document.querySelector(`input[name="sekolah_ids[]"][value="${id}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                }

                passwordHint.classList.remove('hidden');
                document.getElementById('password').required = false;
                document.getElementById('password_confirmation').required = false;
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

        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Hapus Akun Guru?',
                        text: "Data profil dan login akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });

        <?php if($errors->any()): ?>
            document.addEventListener('DOMContentLoaded', function() {
                openModal('create');
                document.getElementById('name').value = "<?php echo e(old('name')); ?>";
                document.getElementById('email').value = "<?php echo e(old('email')); ?>";
                document.getElementById('nip').value = "<?php echo e(old('nip')); ?>";
                document.getElementById('telepon').value = "<?php echo e(old('telepon')); ?>";
                document.getElementById('alamat').value = "<?php echo e(old('alamat')); ?>";
                // Repopulate Checkboxes
                const oldSekolahIds = <?php echo json_encode(old('sekolah_ids', []), 512) ?>;
                oldSekolahIds.forEach(id => {
                    const checkbox = document.querySelector(`input[name="sekolah_ids[]"][value="${id}"]`);
                    if (checkbox) checkbox.checked = true;
                });
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/superadmin/guru/index.blade.php ENDPATH**/ ?>