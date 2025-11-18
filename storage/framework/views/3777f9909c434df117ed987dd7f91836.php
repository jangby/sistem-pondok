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
        
        
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-400 opacity-10 rounded-full -ml-10 -mb-10 blur-xl"></div>

            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <p class="text-emerald-100 text-sm font-medium mb-1">Assalamualaikum,</p>
                    <h1 class="text-2xl font-bold text-white truncate max-w-[180px]">
                        <?php echo e(Auth::user()->name); ?>

                    </h1>
                </div>
                
                <div class="flex items-center gap-3">
                    
                    <form method="POST" action="<?php echo e(route('logout')); ?>" id="logout-form">
                        <?php echo csrf_field(); ?>
                        <button type="button" onclick="confirmLogout()" class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 border border-white/20 text-emerald-50 hover:bg-red-500/80 hover:text-white hover:border-red-500/50 transition-all duration-300 backdrop-blur-md shadow-sm" title="Keluar Aplikasi">
                            <svg class="w-5 h-5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>

                    
                    <a href="<?php echo e(route('profile.edit')); ?>" class="block">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full border-2 border-emerald-200/50 p-0.5 shadow-lg">
                            <div class="w-full h-full bg-white rounded-full flex items-center justify-center text-emerald-600 font-bold text-lg">
                                <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        
        <div class="px-6 -mt-16 relative z-20">
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-50 relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-20 h-20 bg-emerald-50 rounded-full opacity-50"></div>

                <?php
                    // HITUNG TOTAL TAGIHAN SEMUA ANAK
                    // Asumsi: Anda mengirim $sisaTagihan dari controller sebagai total. 
                    // Jika belum, kita hitung manual di sini dari relasi santri->tagihans (yang belum lunas)
                    // Tapi biasanya controller sudah mengirim variabel $sisaTagihan yang sudah asumsi total.
                    // Jika $sisaTagihan di controller logic-nya per user, maka ini sudah benar.
                    
                    // HITUNG TOTAL UANG JAJAN SEMUA ANAK
                    $totalUangJajan = $santris->sum(fn($s) => $s->dompet?->saldo ?? 0);
                ?>

                
                <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4 relative z-10">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Total Tagihan Anak</p>
                        <h3 class="text-2xl font-bold text-red-500 tracking-tight">
                            Rp <?php echo e(number_format($sisaTagihan, 0, ',', '.')); ?>

                        </h3>
                    </div>
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-red-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>

                
                <div class="flex justify-between items-center relative z-10">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Total Saldo E-Wallet</p>
                        <h3 class="text-xl font-bold text-emerald-600 tracking-tight">
                            Rp <?php echo e(number_format($totalUangJajan, 0, ',', '.')); ?>

                        </h3>
                    </div>
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="px-6 mt-8">
            <h3 class="text-gray-800 font-bold text-lg mb-4 flex items-center gap-2">
                <span class="w-1 h-5 bg-emerald-500 rounded-full"></span>
                Menu Utama
            </h3>
            <div class="grid grid-cols-4 gap-4">
                
                <a href="<?php echo e(route('orangtua.tagihan.index')); ?>" class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-emerald-600 group-active:scale-95 group-active:bg-emerald-50 transition-all duration-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium text-center group-hover:text-emerald-600">Tagihan</span>
                </a>
                
                <a href="<?php echo e(route('orangtua.dompet.index')); ?>" class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-yellow-500 group-active:scale-95 group-active:bg-yellow-50 transition-all duration-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium text-center group-hover:text-yellow-600">Uang Jajan</span>
                </a>
                
                <a href="<?php echo e(route('orangtua.dompet.topup.create')); ?>" class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-blue-500 group-active:scale-95 group-active:bg-blue-50 transition-all duration-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium text-center group-hover:text-blue-600">Top Up</span>
                </a>
                
                <a href="<?php echo e(route('profile.edit')); ?>" class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-gray-500 group-active:scale-95 group-active:bg-gray-50 transition-all duration-200">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium text-center group-hover:text-gray-800">Akun</span>
                </a>
            </div>
        </div>

        
        <div class="px-6 mt-8 mb-6">
            <h3 class="text-gray-800 font-bold text-lg mb-4 flex items-center gap-2">
                <span class="w-1 h-5 bg-emerald-500 rounded-full"></span>
                Data Anak
            </h3>

            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white rounded-3xl shadow-md p-5 border border-gray-100 relative overflow-hidden group hover:shadow-lg transition duration-300">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 opacity-50 group-hover:scale-110 transition duration-500"></div>
                        
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-4">
                                
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                                        🎓
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800"><?php echo e($santri->full_name); ?></h3>
                                        <p class="text-xs text-gray-500 font-mono"><?php echo e($santri->nis); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo e($santri->kelas->nama_kelas ?? '-'); ?></p>
                                    </div>
                                </div>

                                
                                <div class="flex flex-col items-end gap-2">
                                    
                                    <a href="<?php echo e(route('orangtua.monitoring.index', $santri->id)); ?>" class="flex items-center gap-1 bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wide hover:bg-blue-100 transition border border-blue-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Pantau
                                    </a>

                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-bold uppercase tracking-wide border <?php echo e($santri->status == 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-gray-100 text-gray-600 border-gray-200'); ?>">
                                        <?php echo e($santri->status == 'active' ? 'Aktif' : 'Nonaktif'); ?>

                                    </span>
                                </div>
                            </div>

                            
                            <div class="bg-gray-50 rounded-xl p-3 flex justify-between items-center border border-gray-100/50">
                                <div>
                                    <p class="text-[9px] text-gray-400 uppercase font-bold tracking-wider">Uang Jajan Anak</p>
                                    <p class="text-base font-bold text-gray-800">Rp <?php echo e(number_format($santri->dompet->saldo ?? 0, 0, ',', '.')); ?></p>
                                </div>
                                <div class="text-right">
                                    
                                    <a href="<?php echo e(route('orangtua.dompet.index')); ?>" class="text-emerald-600 text-[10px] font-bold hover:underline">Riwayat Transaksi &rarr;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="bg-white rounded-3xl shadow-sm p-8 text-center border border-dashed border-gray-300">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">Belum ada data santri yang terhubung.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Keluar Aplikasi?',
                text: "Anda harus login kembali untuk mengakses akun.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl', cancelButton: 'rounded-xl' }
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById('logout-form').submit(); }
            })
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/orangtua/dashboard.blade.php ENDPATH**/ ?>