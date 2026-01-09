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
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Distribusi Keuangan PPDB</h2>
                    <p class="text-gray-500 text-sm">Monitoring arus kas masuk dan penyaluran dana per pos anggaran.</p>
                </div>

                
                <form action="<?php echo e(route('adminpondok.ppdb.distribusi.index')); ?>" method="GET" class="flex items-center gap-2 bg-white p-2 rounded-lg shadow-sm border border-gray-200">
                    <svg class="w-5 h-5 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    <select name="gelombang_id" onchange="this.form.submit()" class="border-none text-sm focus:ring-0 text-gray-700 font-medium bg-transparent cursor-pointer">
                        <?php $__currentLoopData = $allSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($st->id); ?>" <?php echo e($activeSetting->id == $st->id ? 'selected' : ''); ?>>
                                Tahun Ajaran <?php echo e($st->tahun_ajaran); ?> (<?php echo e($st->nama_gelombang); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </form>
            </div>

            
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h4 class="font-bold text-blue-700 text-sm">Bagaimana angka ini dihitung?</h4>
                    <p class="text-blue-600 text-sm mt-1">
                        Pemasukan dihitung secara <strong>proporsional</strong>. Jika santri baru membayar 50% dari total tagihan, maka setiap pos anggaran santri tersebut juga baru terisi 50%.
                        <br>
                        <strong>Saldo Mengendap</strong> adalah uang tunai/transfer yang saat ini masih dipegang oleh Bendahara PPDB dan belum disetorkan ke Yayasan/Pondok/Unit Usaha.
                    </p>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <?php
                    // Helper function untuk card styling agar kodenya tidak berulang
                    $cards = [
                        'yayasan' => [
                            'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                            'color' => 'indigo',
                            'desc' => 'Uang Gedung, Pendaftaran, Taaruf'
                        ],
                        'pondok' => [
                            'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                            'color' => 'emerald',
                            'desc' => 'Makan Awal, SPP Bulan 1, Syariah'
                        ],
                        'usaha' => [
                            'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                            'color' => 'orange',
                            'desc' => 'Kitab, Buku Paket, Seragam (Jual)'
                        ],
                        'panitia' => [
                            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                            'color' => 'rose', // Merah muda untuk panitia (operasional)
                            'desc' => 'Atribut, Kegiatan, Konsumsi Panitia'
                        ]
                    ];
                ?>

                <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $style): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $data = $posData[$key] ?? ['masuk' => 0, 'keluar' => 0, 'label' => ucfirst($key)];
                        $saldo = $data['masuk'] - $data['keluar'];
                        $color = $style['color'];
                    ?>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 relative group">
                        
                        
                        <div class="p-6 border-b border-gray-50 flex justify-between items-start relative z-10">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-<?php echo e($color); ?>-50 text-<?php echo e($color); ?>-600 rounded-lg">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($style['icon']); ?>"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800"><?php echo e($data['label']); ?></h3>
                                    <p class="text-xs text-gray-500"><?php echo e($style['desc']); ?></p>
                                </div>
                            </div>
                            
                            
                            <div class="text-right">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Saldo Mengendap</p>
                                <span class="bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-700 text-lg font-bold px-3 py-1 rounded-md">
                                    Rp <?php echo e(number_format($saldo, 0, ',', '.')); ?>

                                </span>
                            </div>
                        </div>

                        
                        <div class="p-6 grid grid-cols-2 gap-4 relative z-10 bg-white/80">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Total Pemasukan</p>
                                <p class="text-base font-semibold text-gray-800 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                    Rp <?php echo e(number_format($data['masuk'], 0, ',', '.')); ?>

                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">
                                    <?php echo e($key == 'panitia' ? 'Total Belanja' : 'Total Disetor'); ?>

                                </p>
                                <p class="text-base font-semibold text-gray-800 flex items-center gap-1">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                    Rp <?php echo e(number_format($data['keluar'], 0, ',', '.')); ?>

                                </p>
                            </div>
                        </div>

                        
                        <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-between items-center relative z-10">
                            <span class="text-xs text-gray-400">Klik rincian untuk kelola dana</span>
                            <a href="<?php echo e(route('adminpondok.ppdb.distribusi.show', $key)); ?>?gelombang_id=<?php echo e($activeSetting->id); ?>" class="text-sm font-bold text-<?php echo e($color); ?>-600 hover:text-<?php echo e($color); ?>-800 flex items-center gap-1">
                                Lihat Rincian & Setor
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>

                        
                        <div class="absolute top-0 right-0 w-32 h-32 bg-<?php echo e($color); ?>-50 rounded-bl-full opacity-50 -mr-8 -mt-8 pointer-events-none group-hover:scale-110 transition-transform duration-500"></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/adminpondok/ppdb/distribusi/index.blade.php ENDPATH**/ ?>