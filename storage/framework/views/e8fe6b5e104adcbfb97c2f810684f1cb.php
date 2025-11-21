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

    
    <div class="min-h-screen bg-gray-50 pb-28" 
         x-data="{ 
            showStats: false, 
            activeTab: 'Laki-laki',
            
            showList: false, 
            listType: 'hadir', // 'hadir' atau 'belum'
            listGender: 'Laki-laki', // Tab dalam modal list
            
            openList(type) {
                this.listType = type;
                this.showList = true;
                this.listGender = 'Laki-laki'; // Reset ke Putra default
            }
         }">
        
        
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Absensi Asrama</h1>
                    <p class="text-emerald-100 text-xs mt-1 font-medium">Pengecekan Kehadiran</p>
                </div>
                <div class="flex gap-3">
                    <a href="<?php echo e(route('pengurus.absensi.asrama.settings')); ?>" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </a>
                    <a href="<?php echo e(route('pengurus.absensi.index')); ?>" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        
        <div class="px-5 -mt-16 relative z-20">
            <div class="bg-white rounded-3xl shadow-xl p-6 text-center border border-gray-100 backdrop-blur-xl">
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2">Total Wajib Hadir</p>
                <h2 class="text-5xl font-black text-emerald-600 mb-2"><?php echo e($totalWajib); ?></h2>
                <p class="text-xs text-gray-500 bg-gray-50 inline-block px-3 py-1 rounded-full">
                    Total: <?php echo e($totalWajib + $totalSakit + $totalIzin); ?> • Sakit: <?php echo e($totalSakit); ?> • Izin: <?php echo e($totalIzin); ?>

                </p>

                <div class="mt-6 pt-6 border-t border-gray-100 flex justify-around">
                    
                    <div @click="openList('hadir')" class="cursor-pointer active:scale-95 transition hover:bg-emerald-50 p-2 rounded-xl w-1/2">
                        <span class="block text-xl font-bold text-gray-800"><?php echo e($totalHadir); ?></span>
                        <span class="text-[10px] text-emerald-600 uppercase font-bold tracking-wide flex items-center justify-center gap-1">
                            Sudah Absen <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </span>
                    </div>
                    
                    
                    <div class="w-px bg-gray-200"></div>

                    
                    <div @click="openList('belum')" class="cursor-pointer active:scale-95 transition hover:bg-red-50 p-2 rounded-xl w-1/2">
                        <span class="block text-xl font-bold text-red-500"><?php echo e($totalWajib - $totalHadir); ?></span>
                        <span class="text-[10px] text-red-400 uppercase font-bold tracking-wide flex items-center justify-center gap-1">
                            Belum Absen <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="px-6 mt-8 space-y-4">
            <button @click="showStats = true" class="w-full bg-white border border-gray-200 text-gray-700 font-bold py-4 rounded-2xl shadow-sm hover:bg-gray-50 transition active:scale-95 flex items-center justify-center gap-2">
                <div class="bg-emerald-50 p-1.5 rounded-full">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                </div>
                Grafik Kehadiran
            </button>

            <a href="<?php echo e(route('pengurus.absensi.asrama.rekap')); ?>" class="w-full bg-white border border-gray-200 text-gray-700 font-bold py-4 rounded-2xl shadow-sm hover:bg-gray-50 transition active:scale-95 flex items-center justify-center gap-3">
                <div class="bg-orange-50 p-1.5 rounded-full">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                Rekap & Riwayat
            </a>

            <a href="<?php echo e(route('pengurus.absensi.asrama.scan')); ?>" class="flex flex-col items-center justify-center w-full bg-emerald-600 text-white font-bold py-6 rounded-3xl shadow-xl shadow-emerald-300 hover:bg-emerald-700 transition active:scale-95 relative overflow-hidden border border-emerald-500 mt-2">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-xl"></div>
                <div class="bg-white/20 p-4 rounded-full mb-2 backdrop-blur-sm shadow-inner">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="text-lg tracking-wide">Mulai Absensi</span>
            </a>
        </div>

        
        <div x-show="showList" class="fixed inset-0 z-50 flex items-end justify-center sm:items-center" style="display: none;">
            
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showList = false"></div>

            
            <div class="bg-white w-full max-w-md rounded-t-[30px] sm:rounded-3xl relative z-10 shadow-2xl transform transition-transform max-h-[80vh] flex flex-col"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-y-full opacity-0"
                 x-transition:enter-end="translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-y-0 opacity-100"
                 x-transition:leave-end="translate-y-full opacity-0">
                
                
                <div class="w-full flex justify-center pt-3 pb-1" @click="showList = false">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>

                
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800" x-text="listType == 'hadir' ? 'Daftar Hadir' : 'Belum Absen'"></h3>
                        <p class="text-xs text-gray-500">Data hari ini</p>
                    </div>
                    <button @click="showList = false" class="bg-gray-100 p-2 rounded-full text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                
                <div class="px-6 pt-2">
                    <div class="flex p-1 bg-gray-100 rounded-xl">
                        <button @click="listGender = 'Laki-laki'" 
                            :class="listGender === 'Laki-laki' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500'"
                            class="flex-1 py-2 text-xs font-bold rounded-lg transition">Putra</button>
                        <button @click="listGender = 'Perempuan'" 
                            :class="listGender === 'Perempuan' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500'"
                            class="flex-1 py-2 text-xs font-bold rounded-lg transition">Putri</button>
                    </div>
                </div>

                
                <div class="flex-1 overflow-y-auto p-6 space-y-2 min-h-[300px]">
                    
                    
                    
                    <script>
                        const santriData = <?php echo json_encode($detailSantri, 15, 512) ?>;
                    </script>

                    <template x-for="santri in santriData[listType][listGender]" :key="santri.id">
                        <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100" 
                             :class="listType === 'hadir' ? 'bg-emerald-50/50' : 'bg-red-50/50'">
                            
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm"
                                 :class="listType === 'hadir' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600'">
                                <span x-text="santri.full_name.charAt(0)"></span>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate" x-text="santri.full_name"></p>
                                <p class="text-[10px] text-gray-500" x-text="santri.nis"></p>
                            </div>

                            <span class="text-[10px] font-bold px-2 py-1 rounded"
                                  :class="listType === 'hadir' ? 'bg-emerald-200 text-emerald-800' : 'bg-red-200 text-red-800'"
                                  x-text="listType === 'hadir' ? 'OK' : '-'"></span>
                        </div>
                    </template>

                    
                    <div x-show="santriData[listType][listGender].length === 0" class="text-center py-10 text-gray-400 text-xs">
                        Tidak ada data.
                    </div>
                </div>
            </div>
        </div>

        
        <div x-show="showStats" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showStats = false"></div>
            <div class="bg-white rounded-[30px] p-6 w-full max-w-sm relative z-10 shadow-2xl transform transition-all">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg text-gray-800">Statistik Kehadiran</h3>
                    <button @click="showStats = false" class="bg-gray-100 p-2 rounded-full text-gray-500 hover:bg-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="flex p-1 bg-gray-100 rounded-xl mb-6">
                    <button @click="activeTab = 'Laki-laki'; renderChart('Laki-laki')" 
                        :class="activeTab === 'Laki-laki' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500'"
                        class="flex-1 py-2 text-xs font-bold rounded-lg transition">Putra</button>
                    <button @click="activeTab = 'Perempuan'; renderChart('Perempuan')" 
                        :class="activeTab === 'Perempuan' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500'"
                        class="flex-1 py-2 text-xs font-bold rounded-lg transition">Putri</button>
                </div>
                <div class="relative h-52 w-52 mx-auto mb-4">
                    <canvas id="chartCanvas"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span id="persenText" class="text-4xl font-black text-emerald-600">0%</span>
                        <span class="text-[10px] text-gray-400 uppercase font-bold">Kehadiran</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-center text-sm mt-2">
                    <div class="bg-emerald-50 p-2 rounded-xl border border-emerald-100">
                        <span class="block text-[10px] text-emerald-600 font-bold uppercase">Hadir</span>
                        <span id="hadirText" class="text-lg font-black text-emerald-700">0</span>
                    </div>
                    <div class="bg-red-50 p-2 rounded-xl border border-red-100">
                        <span class="block text-[10px] text-red-600 font-bold uppercase">Belum</span>
                        <span id="belumText" class="text-lg font-black text-red-700">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('layouts.pengurus-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <script>
        const statsData = <?php echo json_encode($stats, 15, 512) ?>;
        let myChart = null;

        function renderChart(gender) {
            const data = statsData[gender];
            const belumHadir = data.wajib - data.hadir;
            
            document.getElementById('persenText').innerText = data.persen + '%';
            document.getElementById('hadirText').innerText = data.hadir;
            document.getElementById('belumText').innerText = belumHadir;
            
            const ctx = document.getElementById('chartCanvas').getContext('2d');
            if (myChart) myChart.destroy();

            myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Belum'],
                    datasets: [{
                        data: [data.hadir, belumHadir],
                        backgroundColor: ['#10b981', '#f3f4f6'],
                        borderWidth: 0,
                        cutout: '80%',
                        borderRadius: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: false } },
                    animation: { animateScale: true, animateRotate: true }
                }
            });
        }

        document.addEventListener('alpine:init', () => {
            Alpine.effect(() => { });
        });
        window.onload = () => renderChart('Laki-laki');
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/absensi/asrama/index.blade.php ENDPATH**/ ?>