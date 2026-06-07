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
    
    <?php $__env->startPush('styles'); ?>
    <style>
        .active-glow {
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
            border: 2px solid #10B981;
        }
        .timeline-line {
            position: absolute;
            left: 24px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
            z-index: 0;
        }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            
            <?php if($activeTahun): ?>
            <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm border border-white/20 text-xs font-bold uppercase tracking-widest mb-3">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                            Sedang Aktif
                        </div>
                        <h3 class="text-4xl font-black tracking-tight"><?php echo e($activeTahun->nama_tahun_ajaran); ?></h3>
                        <p class="text-emerald-100 mt-2 font-medium flex items-center justify-center md:justify-start gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <?php echo e(\Carbon\Carbon::parse($activeTahun->tanggal_mulai)->isoFormat('D MMMM Y')); ?> 
                            <span class="opacity-50 mx-1">➝</span> 
                            <?php echo e(\Carbon\Carbon::parse($activeTahun->tanggal_selesai)->isoFormat('D MMMM Y')); ?>

                        </p>
                    </div>
                    
                    <div class="bg-white/10 p-4 rounded-2xl border border-white/10 backdrop-blur-md text-center">
                        <div class="text-xs text-emerald-200 uppercase font-bold mb-1">Status Sistem</div>
                        <div class="text-lg font-black">Semua Data Terkunci</div>
                        <div class="text-[10px] text-emerald-100 opacity-80">Ke periode ini</div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="bg-orange-50 border-l-4 border-orange-500 p-6 rounded-r-xl shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="bg-orange-100 p-3 rounded-full text-orange-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-orange-800">Belum Ada Tahun Ajaran Aktif!</h3>
                        <p class="text-orange-700 text-sm">Sistem membutuhkan setidaknya satu tahun ajaran aktif agar fitur absensi dan nilai dapat berjalan.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <form method="GET" class="relative w-full sm:w-96 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                           class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition" 
                           placeholder="Cari tahun ajaran...">
                </form>

                <button onclick="openModal('create')" 
                   class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Buat Periode Baru
                </button>
            </div>

            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <?php if($tahunAjarans->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode Akademik</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Durasi</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php $__currentLoopData = $tahunAjarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="group hover:bg-gray-50 transition-colors <?php echo e($ta->is_active ? 'bg-emerald-50/30' : ''); ?>">
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg shadow-sm border <?php echo e($ta->is_active ? 'bg-emerald-100 text-emerald-600 border-emerald-200' : 'bg-gray-100 text-gray-500 border-gray-200'); ?>">
                                                    <?php echo e(substr($ta->nama_tahun_ajaran, 0, 2)); ?>

                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-800 text-base group-hover:text-indigo-600 transition-colors">
                                                        <?php echo e($ta->nama_tahun_ajaran); ?>

                                                    </div>
                                                    <div class="text-xs text-gray-500 flex items-center gap-1">
                                                        <?php echo e(\Carbon\Carbon::parse($ta->tanggal_mulai)->format('d M Y')); ?> 
                                                        <span>-</span> 
                                                        <?php echo e(\Carbon\Carbon::parse($ta->tanggal_selesai)->format('d M Y')); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e(\Carbon\Carbon::parse($ta->tanggal_mulai)->diffForHumans(\Carbon\Carbon::parse($ta->tanggal_selesai), true)); ?>

                                        </td>

                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <?php if($ta->is_active): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-sm">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                    AKTIF
                                                </span>
                                            <?php else: ?>
                                                <button onclick="confirmActivate(<?php echo e($ta->id); ?>, '<?php echo e($ta->nama_tahun_ajaran); ?>')" 
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-all cursor-pointer">
                                                    Aktifkan
                                                </button>
                                                
                                                <form id="activate-form-<?php echo e($ta->id); ?>" action="<?php echo e(route('sekolah.superadmin.tahun-ajaran.activate', $ta->id)); ?>" method="POST" style="display: none;">
                                                    <?php echo csrf_field(); ?>
                                                </form>
                                            <?php endif; ?>
                                        </td>

                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button onclick="openModal('edit', <?php echo e($ta); ?>)" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                
                                                <?php if(!$ta->is_active): ?>
                                                <form action="<?php echo e(route('sekolah.superadmin.tahun-ajaran.destroy', $ta->id)); ?>" method="POST" class="delete-form">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn-delete p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        <?php echo e($tahunAjarans->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Belum ada data</h3>
                        <p class="text-gray-500 text-sm mt-1 mb-4">Tambahkan tahun ajaran untuk memulai operasional.</p>
                        <button onclick="openModal('create')" class="px-5 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition">
                            + Tambah
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div id="taModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95" id="modalPanel">
                
                <div class="bg-white px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-black text-gray-800" id="modalTitle">Tambah Tahun Ajaran</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Tentukan nama dan durasi periode.</p>
                    </div>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form id="taForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <div id="methodSpoof"></div>

                    <div class="px-6 py-6 space-y-5">
                        <div>
                            <label for="nama_tahun_ajaran" class="block text-sm font-bold text-gray-700 mb-1.5">Nama Tahun Ajaran</label>
                            <input type="text" name="nama_tahun_ajaran" id="nama_tahun_ajaran" required placeholder="Cth: 2024/2025 Ganjil"
                                   class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-bold text-gray-700 mb-1.5">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" required
                                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                            </div>
                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-bold text-gray-700 mb-1.5">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" required
                                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const modal = document.getElementById('taModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        const form = document.getElementById('taForm');
        const title = document.getElementById('modalTitle');
        const methodSpoof = document.getElementById('methodSpoof');
        
        const baseUpdateUrl = "<?php echo e(route('sekolah.superadmin.tahun-ajaran.index')); ?>";
        const storeUrl = "<?php echo e(route('sekolah.superadmin.tahun-ajaran.store')); ?>";

        function openModal(mode, data = null) {
            form.reset();
            methodSpoof.innerHTML = '';

            if (mode === 'create') {
                title.innerText = 'Tambah Tahun Ajaran';
                form.action = storeUrl;
            } else if (mode === 'edit' && data) {
                title.innerText = 'Edit Tahun Ajaran';
                form.action = `${baseUpdateUrl}/${data.id}`;
                methodSpoof.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                
                document.getElementById('nama_tahun_ajaran').value = data.nama_tahun_ajaran;
                document.getElementById('tanggal_mulai').value = data.tanggal_mulai;
                document.getElementById('tanggal_selesai').value = data.tanggal_selesai;
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

        // Konfirmasi Aktivasi dengan SweetAlert
        function confirmActivate(id, nama) {
            Swal.fire({
                title: 'Aktifkan Periode Ini?',
                text: `"${nama}" akan menjadi tahun ajaran aktif. Periode sebelumnya akan dinonaktifkan otomatis.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10B981', // Emerald
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Aktifkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`activate-form-${id}`).submit();
                }
            });
        }

        // Konfirmasi Hapus
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Data yang dihapus tidak bisa dikembalikan.",
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
                document.getElementById('nama_tahun_ajaran').value = "<?php echo e(old('nama_tahun_ajaran')); ?>";
                document.getElementById('tanggal_mulai').value = "<?php echo e(old('tanggal_mulai')); ?>";
                document.getElementById('tanggal_selesai').value = "<?php echo e(old('tanggal_selesai')); ?>";
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/superadmin/tahun-ajaran/index.blade.php ENDPATH**/ ?>