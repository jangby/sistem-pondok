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
                    <span class="text-3xl">✅</span> Persetujuan Izin
                </h2>
                <p class="text-sm text-gray-500 mt-1">Tinjau pengajuan izin/sakit dari guru dan staf.</p>
            </div>
            
            
            <form method="GET" class="relative w-full md:w-80 group">
                <input type="hidden" name="status" value="<?php echo e($status); ?>">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                       class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm transition" 
                       placeholder="Cari nama guru...">
            </form>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <div class="flex space-x-1 rounded-xl bg-gray-200/50 p-1 mb-8 max-w-2xl mx-auto md:mx-0">
                <?php
                    $tabs = [
                        'pending' => ['label' => 'Menunggu Konfirmasi', 'icon' => '⏳'],
                        'approved' => ['label' => 'Disetujui', 'icon' => 'check-circle'],
                        'rejected' => ['label' => 'Ditolak', 'icon' => 'x-circle'],
                    ];
                ?>

                <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('sekolah.superadmin.persetujuan-izin.index', ['status' => $key])); ?>"
                       class="w-full rounded-lg py-2.5 text-sm font-bold leading-5 text-center transition-all duration-200
                              <?php echo e($status === $key 
                                 ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-black/5' 
                                 : 'text-gray-500 hover:bg-white/[0.12] hover:text-gray-700'); ?>">
                        
                        
                        <?php if($key == 'pending'): ?> <span>⏳</span> <?php endif; ?>
                        <?php echo e($tab['label']); ?>

                        
                        <?php if($key == 'pending' && $countPending > 0): ?>
                            <span class="ml-2 bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-[10px]"><?php echo e($countPending); ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <?php if($izins->count() > 0): ?>
                <div class="grid grid-cols-1 gap-6">
                    <?php $__currentLoopData = $izins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $izin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $start = \Carbon\Carbon::parse($izin->tanggal_mulai);
                            $end = \Carbon\Carbon::parse($izin->tanggal_selesai);
                            $diff = $start->diffInDays($end) + 1;
                            
                            // Badge Tipe
                            $typeColor = match($izin->tipe_izin) {
                                'sakit' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                'izin' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'cuti' => 'bg-purple-100 text-purple-700 border-purple-200',
                                default => 'bg-gray-100 text-gray-700'
                            };
                            
                            $typeLabel = ucfirst($izin->tipe_izin);
                        ?>

                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 relative overflow-hidden group">
                            
                            
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($status == 'pending' ? 'bg-amber-400' : ($status == 'approved' ? 'bg-emerald-500' : 'bg-rose-500')); ?>"></div>

                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 pl-2">
                                
                                
                                <div class="flex items-center gap-4 w-full md:w-auto">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-lg border border-gray-200">
                                        <?php echo e(substr($izin->guru->name, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900"><?php echo e($izin->guru->name); ?></h3>
                                        <div class="flex items-center gap-2 text-sm text-gray-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                <?php echo e($izin->sekolah->nama_sekolah); ?>

                                            </span>
                                            <span>•</span>
                                            <span class="px-2 py-0.5 rounded-md text-xs font-bold uppercase <?php echo e($typeColor); ?> border">
                                                <?php echo e($typeLabel); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                                    <div class="flex flex-col justify-center">
                                        <span class="text-xs font-bold text-gray-400 uppercase mb-1">Tanggal & Durasi</span>
                                        <div class="font-medium text-gray-800 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <?php echo e($start->format('d M')); ?> - <?php echo e($end->format('d M Y')); ?>

                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-bold ml-1">
                                                <?php echo e($diff); ?> Hari
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <span class="text-xs font-bold text-gray-400 uppercase mb-1">Keterangan</span>
                                        <p class="text-sm text-gray-600 line-clamp-2 italic">"<?php echo e($izin->keterangan_guru); ?>"</p>
                                        <?php if($izin->file_pendukung): ?>
                                            <a href="<?php echo e(asset('storage/'.$izin->file_pendukung)); ?>" target="_blank" class="text-xs text-indigo-600 hover:underline mt-1 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                Lihat Lampiran
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                
                                <?php if($status == 'pending'): ?>
                                    <div class="flex items-center gap-2 w-full md:w-auto justify-end border-t md:border-t-0 pt-4 md:pt-0 mt-4 md:mt-0 border-gray-100">
                                        <button onclick="openRejectModal(<?php echo e($izin->id); ?>, '<?php echo e($izin->guru->name); ?>')" 
                                                class="px-4 py-2 text-sm font-bold text-red-600 bg-red-50 rounded-lg border border-red-100 hover:bg-red-100 hover:border-red-200 transition-all">
                                            Tolak
                                        </button>
                                        <button onclick="openApproveModal(<?php echo e($izin->id); ?>, '<?php echo e($izin->guru->name); ?>')" 
                                                class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 rounded-lg shadow-md hover:bg-emerald-700 hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                            Setujui Izin
                                        </button>
                                    </div>
                                <?php else: ?>
                                    
                                    <div class="flex flex-col items-end justify-center w-full md:w-auto">
                                        <?php if($status == 'approved'): ?>
                                            <div class="flex items-center text-emerald-600 font-bold text-sm bg-emerald-50 px-3 py-1 rounded-lg border border-emerald-100">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Disetujui
                                            </div>
                                            <span class="text-[10px] text-gray-400 mt-1">Oleh Admin <?php echo e($izin->peninjau_user_id); ?></span>
                                        <?php else: ?>
                                            <div class="flex items-center text-red-600 font-bold text-sm bg-red-50 px-3 py-1 rounded-lg border border-red-100">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                Ditolak
                                            </div>
                                            <?php if($izin->keterangan_admin): ?>
                                                <span class="text-[10px] text-red-400 mt-1 italic">"<?php echo e(Str::limit($izin->keterangan_admin, 20)); ?>"</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-6">
                    <?php echo e($izins->links()); ?>

                </div>
            <?php else: ?>
                
                <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl shadow-sm border border-gray-200 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Tidak ada data</h3>
                    <p class="text-gray-500 text-sm mt-1">Tidak ada pengajuan izin dengan status "<?php echo e($status); ?>" saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div id="approveModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="approveBackdrop"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md opacity-0 scale-95" id="approvePanel">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-bold leading-6 text-gray-900">Setujui Izin Guru?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Anda akan menyetujui izin untuk <span id="approveName" class="font-bold text-gray-800"></span>. Absensi guru akan otomatis diperbarui.
                                </p>
                                <form id="approveForm" method="POST" class="mt-4">
                                    <?php echo csrf_field(); ?>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Catatan (Opsional)</label>
                                    <textarea name="keterangan_admin" rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Contoh: Semoga lekas sembuh"></textarea>
                                    
                                    <div class="mt-4 flex flex-row-reverse gap-2">
                                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 sm:w-auto">Ya, Setujui</button>
                                        <button type="button" onclick="closeApproveModal()" class="inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="rejectBackdrop"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md opacity-0 scale-95" id="rejectPanel">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-bold leading-6 text-gray-900">Tolak Izin Guru?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Tolak pengajuan dari <span id="rejectName" class="font-bold text-gray-800"></span>. Wajib sertakan alasan penolakan.
                                </p>
                                <form id="rejectForm" method="POST" class="mt-4">
                                    <?php echo csrf_field(); ?>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                                    <textarea name="keterangan_admin" rows="2" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm" placeholder="Contoh: Bukti surat tidak valid"></textarea>
                                    
                                    <div class="mt-4 flex flex-row-reverse gap-2">
                                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto">Tolak Pengajuan</button>
                                        <button type="button" onclick="closeRejectModal()" class="inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        // APPROVE MODAL
        const approveModal = document.getElementById('approveModal');
        const approveBackdrop = document.getElementById('approveBackdrop');
        const approvePanel = document.getElementById('approvePanel');
        const approveForm = document.getElementById('approveForm');
        const approveName = document.getElementById('approveName');

        function openApproveModal(id, name) {
            approveForm.action = "<?php echo e(url('sekolah-superadmin/persetujuan-izin')); ?>/" + id + "/approve";
            approveName.textContent = name;
            
            approveModal.classList.remove('hidden');
            setTimeout(() => {
                approveBackdrop.classList.remove('opacity-0');
                approvePanel.classList.remove('opacity-0', 'scale-95');
                approvePanel.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeApproveModal() {
            approveBackdrop.classList.add('opacity-0');
            approvePanel.classList.remove('opacity-100', 'scale-100');
            approvePanel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => { approveModal.classList.add('hidden'); }, 300);
        }

        // REJECT MODAL
        const rejectModal = document.getElementById('rejectModal');
        const rejectBackdrop = document.getElementById('rejectBackdrop');
        const rejectPanel = document.getElementById('rejectPanel');
        const rejectForm = document.getElementById('rejectForm');
        const rejectName = document.getElementById('rejectName');

        function openRejectModal(id, name) {
            rejectForm.action = "<?php echo e(url('sekolah-superadmin/persetujuan-izin')); ?>/" + id + "/reject";
            rejectName.textContent = name;
            
            rejectModal.classList.remove('hidden');
            setTimeout(() => {
                rejectBackdrop.classList.remove('opacity-0');
                rejectPanel.classList.remove('opacity-0', 'scale-95');
                rejectPanel.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeRejectModal() {
            rejectBackdrop.classList.add('opacity-0');
            rejectPanel.classList.remove('opacity-100', 'scale-100');
            rejectPanel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => { rejectModal.classList.add('hidden'); }, 300);
        }
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/superadmin/persetujuan-izin/index.blade.php ENDPATH**/ ?>