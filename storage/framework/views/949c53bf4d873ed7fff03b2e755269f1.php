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
                <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                    Mata Pelajaran
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola kurikulum dan daftar pelajaran sekolah.</p>
            </div>
            
            <div class="hidden md:flex items-center gap-3 text-sm text-gray-600 bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span>Total: <span class="font-bold text-gray-900"><?php echo e($mataPelajarans->total()); ?></span> Mapel</span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                <div class="relative w-full sm:w-96 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <form method="GET">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition" 
                               placeholder="Cari mapel atau kode...">
                    </form>
                </div>

                
                <button onclick="openModal('create')" 
                   class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 cursor-pointer">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                     Mapel
                </button>
            </div>

            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <?php if($mataPelajarans->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Identitas Mapel</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kode Sistem</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="relative px-6 py-4"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php $__currentLoopData = $mataPelajarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $colors = ['bg-blue-100 text-blue-700', 'bg-green-100 text-green-700', 'bg-purple-100 text-purple-700', 'bg-amber-100 text-amber-700', 'bg-rose-100 text-rose-700'];
                                        $colorClass = $colors[$mapel->id % count($colors)];
                                        $initials = $mapel->kode_mapel ? strtoupper(substr($mapel->kode_mapel, 0, 3)) : strtoupper(substr($mapel->nama_mapel, 0, 2));
                                    ?>

                                    <tr class="group hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-lg <?php echo e($colorClass); ?> flex items-center justify-center font-bold text-xs border border-white shadow-sm">
                                                    <?php echo e($initials); ?>

                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900"><?php echo e($mapel->nama_mapel); ?></div>
                                                    <div class="text-xs text-gray-500">ID: <?php echo e($mapel->id); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($mapel->kode_mapel): ?>
                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-md bg-gray-100 text-gray-600 border border-gray-200 font-mono"><?php echo e($mapel->kode_mapel); ?></span>
                                            <?php else: ?>
                                                <span class="text-xs text-gray-400 italic">--</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                
                                                <button onclick="openModal('edit', <?php echo e($mapel); ?>)" 
                                                   class="p-2 bg-white border border-gray-200 rounded-lg text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300 shadow-sm transition" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                
                                                
                                                <form action="<?php echo e(route('sekolah.admin.mata-pelajaran.destroy', $mapel->id)); ?>" method="POST" class="delete-form">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn-delete p-2 bg-white border border-gray-200 rounded-lg text-red-600 hover:bg-red-50 hover:border-red-300 shadow-sm transition" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                        <?php echo e($mataPelajarans->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                        <div class="bg-gray-50 rounded-full p-6 mb-4">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Belum ada Mata Pelajaran</h3>
                        <button onclick="openModal('create')" class="mt-4 inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition">
                            + Tambah Mapel Pertama
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div id="mapelModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95" id="modalPanel">
                <div class="bg-gray-50/50 px-4 py-4 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold leading-6 text-gray-900" id="modalTitle">Tambah Mata Pelajaran</h3>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form id="mapelForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <div id="methodSpoof"></div>
                    <div class="px-4 py-5 sm:p-6 space-y-5">
                        <div>
                            <label for="nama_mapel" class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Pelajaran</label>
                            <input type="text" name="nama_mapel" id="nama_mapel" required placeholder="Cth: Matematika Wajib"
                                   class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5"
                                   value="<?php echo e(old('nama_mapel')); ?>">
                            <p class="mt-1 text-xs text-red-500" id="error_nama_mapel"><?php $__errorArgs = ['nama_mapel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></p>
                        </div>
                        <div>
                            <label for="kode_mapel" class="block text-sm font-medium text-gray-700 mb-1">Kode Mapel (Opsional)</label>
                            <input type="text" name="kode_mapel" id="kode_mapel" placeholder="Cth: MTK-01"
                                   class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5"
                                   value="<?php echo e(old('kode_mapel')); ?>">
                            <p class="mt-1 text-xs text-gray-500">Kode unik untuk identifikasi di sistem absensi.</p>
                            <p class="mt-1 text-xs text-red-500"><?php $__errorArgs = ['kode_mapel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100 gap-2">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition-all">
                            Simpan Data
                        </button>
                        <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all">
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
            /* --- LOGIKA MODAL --- */
            const modal = document.getElementById('mapelModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');
            const form = document.getElementById('mapelForm');
            const title = document.getElementById('modalTitle');
            const methodSpoof = document.getElementById('methodSpoof');
            const baseUpdateUrl = "<?php echo e(route('sekolah.admin.mata-pelajaran.index')); ?>"; 
            const storeUrl = "<?php echo e(route('sekolah.admin.mata-pelajaran.store')); ?>";

            function openModal(mode, data = null) {
                form.reset();
                methodSpoof.innerHTML = '';
                document.getElementById('error_nama_mapel').innerText = ''; 

                if (mode === 'create') {
                    title.innerText = 'Tambah Mata Pelajaran Baru';
                    form.action = storeUrl;
                } else if (mode === 'edit' && data) {
                    title.innerText = 'Edit Mata Pelajaran: ' + data.nama_mapel;
                    form.action = `${baseUpdateUrl}/${data.id}`;
                    methodSpoof.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                    document.getElementById('nama_mapel').value = data.nama_mapel;
                    document.getElementById('kode_mapel').value = data.kode_mapel || '';
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
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300); 
            }

            <?php if($errors->any()): ?>
                document.addEventListener('DOMContentLoaded', function() {
                    openModal('create'); 
                    document.getElementById('nama_mapel').value = "<?php echo e(old('nama_mapel')); ?>";
                    document.getElementById('kode_mapel').value = "<?php echo e(old('kode_mapel')); ?>";
                });
            <?php endif; ?>

            /* --- LOGIKA SWEETALERT DELETE --- */
            document.addEventListener('DOMContentLoaded', function () {
                // Menangkap semua tombol dengan class .btn-delete
                const deleteButtons = document.querySelectorAll('.btn-delete');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault(); // Mencegah form submit langsung
                        
                        // Cari form terdekat dari tombol ini
                        const form = this.closest('.delete-form');
                        
                        Swal.fire({
                            title: 'Hapus Mata Pelajaran?',
                            text: "Data yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444', // Red-500
                            cancelButtonColor: '#6b7280', // Gray-500
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal',
                            background: '#ffffff',
                            customClass: {
                                popup: 'rounded-2xl shadow-xl',
                                confirmButton: 'rounded-lg px-4 py-2',
                                cancelButton: 'rounded-lg px-4 py-2'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/mata-pelajaran/index.blade.php ENDPATH**/ ?>