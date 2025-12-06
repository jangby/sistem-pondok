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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-orange-500 p-6 relative overflow-hidden transition hover:shadow-md group">
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Payout Tertunda</p>
                        <div class="mt-2 flex items-baseline gap-2">
                            <h3 class="text-3xl font-bold text-gray-900">
                                <?php echo e($kpi_pending_payout_count); ?>

                            </h3>
                            <span class="text-sm text-orange-600 font-medium">Permintaan</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            Total: <span class="font-semibold text-orange-600">Rp <?php echo e(number_format($kpi_pending_payout_amount, 0, ',', '.')); ?></span>
                        </p>
                    </div>
                    
                    <div class="absolute right-2 top-2 text-orange-100 group-hover:text-orange-50 transition duration-300">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    </div>
                </div>
                
                
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-emerald-500 p-6 relative overflow-hidden transition hover:shadow-md group">
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Fee Bulan Ini</p>
                        <div class="mt-2">
                            <h3 class="text-3xl font-bold text-emerald-600">
                                Rp <?php echo e(number_format($kpi_revenue_this_month, 0, ',', '.')); ?>

                            </h3>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Pendapatan bersih sistem.</p>
                    </div>
                    <div class="absolute right-2 top-2 text-emerald-50 group-hover:text-emerald-100 transition duration-300">
                        <svg class="w-24 h-24 -mr-4 -mt-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3.01 1.49 3.01 2.39 0 .5-.14 1.26-2.5 1.26-1.71 0-2.55-.77-2.65-1.8h-2.2c.09 1.97 1.37 3.33 3.65 3.8V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 p-6 relative overflow-hidden transition hover:shadow-md group">
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Fee Seumur Hidup</p>
                        <div class="mt-2">
                            <h3 class="text-2xl font-bold text-gray-900">
                                Rp <?php echo e(number_format($kpi_total_revenue, 0, ',', '.')); ?>

                            </h3>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Akumulasi seluruh fee admin.</p>
                    </div>
                    <div class="absolute right-2 top-2 text-blue-50 group-hover:text-blue-100 transition duration-300">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/></svg>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-indigo-500 p-6 relative overflow-hidden transition hover:shadow-md group">
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Ekosistem</p>
                        <div class="mt-2 flex items-baseline gap-2">
                            <h3 class="text-3xl font-bold text-gray-900">
                                <?php echo e($kpi_total_pondok); ?>

                            </h3>
                            <span class="text-sm text-gray-500 font-medium">Pondok</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            Melayani <strong class="text-indigo-600"><?php echo e(number_format($kpi_total_santri, 0, ',', '.')); ?></strong> Santri
                        </p>
                    </div>
                    <div class="absolute right-2 top-2 text-indigo-50 group-hover:text-indigo-100 transition duration-300">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
                    </div>
                </div>

            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Permintaan Penarikan Tertunda</h3>
                            <p class="text-sm text-gray-500">Harap segera diverifikasi dan diproses.</p>
                        </div>
                        <?php if($kpi_pending_payout_count > 0): ?>
                            <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-bold animate-pulse">
                                <?php echo e($kpi_pending_payout_count); ?> Pending
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="divide-y divide-gray-50">
                        <?php $__empty_1 = true; $__currentLoopData = $daftarPendingPayouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="p-6 hover:bg-gray-50 transition duration-150 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="p-3 bg-emerald-100 rounded-full text-emerald-600 shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-lg font-bold text-gray-900">Rp <?php echo e(number_format($payout->total_amount, 0, ',', '.')); ?></p>
                                        <p class="text-sm text-gray-600 font-medium"><?php echo e($payout->pondok->name); ?></p>
                                        <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Diajukan <?php echo e($payout->requested_at->diffForHumans()); ?>

                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <a href="<?php echo e(route('superadmin.payouts.show', $payout->id)); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:w-auto">
                                        Proses
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="p-10 text-center flex flex-col items-center justify-center text-gray-500">
                                <div class="bg-green-50 p-4 rounded-full mb-3">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <p class="font-medium">Tidak ada permintaan penarikan yang tertunda.</p>
                                <p class="text-sm mt-1">Kerja bagus! Semua permintaan telah diproses.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($kpi_pending_payout_count > 5): ?>
                        <div class="bg-gray-50 p-4 text-center border-t border-gray-100">
                            <a href="<?php echo e(route('superadmin.payouts.index', ['status' => 'pending'])); ?>" class="text-sm font-medium text-emerald-600 hover:text-emerald-800 hover:underline">
                                Lihat Semua Permintaan Tertunda &rarr;
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Tren Pemasukan Fee</h3>
                        <p class="text-sm text-gray-500">Performa 7 hari terakhir</p>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64 w-full">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        // Inisialisasi Grafik dengan Warna Emerald
        const ctx = document.getElementById('revenueChart');
        
        if (ctx) {
            new Chart(ctx, {
                type: 'line', // Ubah ke line agar lebih elegan untuk tren
                data: {
                    labels: <?php echo json_encode($chartLabels, 15, 512) ?>,
                    datasets: [{
                        label: 'Pemasukan Fee (Rp)',
                        data: <?php echo json_encode($chartData, 15, 512) ?>,
                        // Warna Emerald (Hijau)
                        backgroundColor: 'rgba(16, 185, 129, 0.1)', // emerald-500 dengan opacity
                        borderColor: '#059669', // emerald-600 solid
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#059669',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true, // Isi area bawah grafik
                        tension: 0.4 // Membuat garis melengkung (smooth)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                borderDash: [2, 2],
                                color: '#f3f4f6'
                            },
                            ticks: {
                                font: { size: 10 },
                                callback: function(value) {
                                    // Format angka sumbu Y agar lebih ringkas (cth: 10k)
                                    if (value >= 1000000) return (value/1000000) + 'Jt';
                                    if (value >= 1000) return (value/1000) + 'rb';
                                    return value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#064e3b', // emerald-900
                            titleColor: '#ffffff',
                            bodyColor: '#d1fae5', // emerald-100
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    }
                }
            });
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/superadmin/dashboard.blade.php ENDPATH**/ ?>