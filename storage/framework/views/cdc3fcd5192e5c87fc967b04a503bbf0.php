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

    <div class="min-h-screen bg-gray-50" 
         x-data="{ 
            showModal: false,
            selectedSholat: '',
            activeTab: 'Laki-laki',
            listType: 'hadir',
            
            currentData: { 
                'Laki-laki': { persen: 0, hadir: 0, wajib: 0, list_hadir: [], list_belum: [] }, 
                'Perempuan': { persen: 0, hadir: 0, wajib: 0, list_hadir: [], list_belum: [] } 
            },

            openModal(sholat) {
                this.selectedSholat = sholat;
                this.currentData = sholatData[sholat];
                this.showModal = true;
                this.renderChart();
            },
            
            renderChart() {
                setTimeout(() => {
                    const data = this.currentData[this.activeTab];
                    const belum = data.wajib - data.hadir;
                    
                    const ctx = document.getElementById('chartCanvas').getContext('2d');
                    if (window.myChart instanceof Chart) window.myChart.destroy();

                    window.myChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Hadir', 'Belum'],
                            datasets: [{
                                data: [data.hadir, belum],
                                backgroundColor: ['#10b981', '#f3f4f6'],
                                borderWidth: 0,
                                cutout: '80%',
                                borderRadius: 20
                            }]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { display: false }, tooltip: { enabled: false } },
                            animation: { animateScale: true, animateRotate: true }
                        }
                    });
                }, 50);
            }
         }">
        
        
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-3xl"></div>
            
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Absensi Jamaah</h1>
                    <p class="text-emerald-100 text-xs mt-1 font-medium">Sholat 5 Waktu</p>
                </div>
                <a href="<?php echo e(route('pengurus.absensi.index')); ?>" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition active:scale-95">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            </div>
        </div>

        
        <div class="px-5 -mt-16 relative z-20">
            <a href="<?php echo e(route('pengurus.absensi.jamaah.haid')); ?>" class="block bg-white rounded-3xl shadow-xl p-5 text-center border border-gray-100 backdrop-blur-xl active:scale-95 transition group">
                <div class="flex items-center justify-between">
                    <div class="text-left">
                        <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Santriwati Haid</p>
                        <h2 class="text-3xl font-black text-pink-500"><?php echo e($sedangHaid); ?></h2>
                    </div>
                    <div class="w-12 h-12 bg-pink-50 text-pink-500 rounded-2xl flex items-center justify-center group-hover:bg-pink-500 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-1 text-xs text-gray-500">
                    <span class="text-pink-500 font-bold">Kelola Data Haid</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>
        </div>

        
        <div class="px-5 mt-6 relative z-20">
             <a href="<?php echo e(route('pengurus.absensi.jamaah.scan')); ?>" class="flex items-center justify-center w-full bg-emerald-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition active:scale-95 group border border-emerald-500">
                <div class="bg-white/20 p-2 rounded-lg mr-3 backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div class="text-left">
                    <span class="block text-base leading-none tracking-wide">Mulai Absen</span>
                    <span class="text-[10px] font-normal text-emerald-100">Tap untuk scan</span>
                </div>
            </a>
        </div>

        
        <div class="px-5 mt-8">
            <h3 class="font-bold text-gray-800 mb-4 text-lg">Kehadiran Hari Ini</h3>
            <div class="space-y-3">
                <?php $__currentLoopData = $sholatList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sholat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div @click="openModal('<?php echo e($sholat); ?>')" class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center group active:scale-95 transition cursor-pointer hover:border-emerald-300">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center font-bold text-xs shadow-inner group-hover:bg-emerald-600 group-hover:text-white transition">
                                <?php echo e(substr($sholat, 0, 3)); ?>

                            </div>
                            <div>
                                <span class="font-bold text-gray-800 text-base"><?php echo e($sholat); ?></span>
                                <p class="text-[10px] text-gray-400">Klik untuk detail</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-xl font-black text-emerald-600"><?php echo e($dataSholat[$sholat]['total_hadir']); ?></span>
                            <span class="text-[10px] text-gray-400 font-bold uppercase">Hadir</span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        
        
        <div class="h-40 w-full"></div> 

        
        <div x-show="showModal" class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center" style="display: none;">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

            <div class="bg-white w-full max-w-md rounded-t-[30px] relative z-10 shadow-2xl transform transition-transform flex flex-col max-h-[90vh]"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                
                <div class="w-full flex justify-center pt-3 pb-1" @click="showModal = false"><div class="w-12 h-1.5 bg-gray-300 rounded-full"></div></div>

                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-xl text-gray-800" x-text="'Sholat ' + selectedSholat"></h3>
                        <p class="text-xs text-gray-500">Statistik Kehadiran</p>
                    </div>
                    <button @click="showModal = false" class="bg-gray-100 p-2 rounded-full text-gray-500"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="px-6 mt-4">
                    <div class="flex p-1 bg-gray-100 rounded-xl">
                        <button @click="activeTab = 'Laki-laki'; renderChart()" 
                            :class="activeTab === 'Laki-laki' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-2 text-xs font-bold rounded-lg transition">Putra</button>
                        <button @click="activeTab = 'Perempuan'; renderChart()" 
                            :class="activeTab === 'Perempuan' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            class="flex-1 py-2 text-xs font-bold rounded-lg transition">Putri</button>
                    </div>
                </div>

                <div class="px-6 mt-6 flex items-center justify-between">
                    <div class="relative h-32 w-32">
                        <canvas id="chartCanvas"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-xl font-black text-emerald-600" x-text="currentData[activeTab].persen + '%'"></span>
                        </div>
                    </div>
                    <div class="flex-1 ml-6 space-y-2">
                        <div @click="listType = 'hadir'" class="flex justify-between items-center p-2 rounded-lg cursor-pointer border transition" :class="listType == 'hadir' ? 'bg-emerald-50 border-emerald-200' : 'border-transparent'">
                            <span class="text-xs font-bold text-gray-500">Hadir</span>
                            <span class="text-lg font-black text-emerald-600" x-text="currentData[activeTab].hadir"></span>
                        </div>
                        <div @click="listType = 'belum'" class="flex justify-between items-center p-2 rounded-lg cursor-pointer border transition" :class="listType == 'belum' ? 'bg-red-50 border-red-200' : 'border-transparent'">
                            <span class="text-xs font-bold text-gray-500">Belum</span>
                            <span class="text-lg font-black text-red-500" x-text="currentData[activeTab].wajib - currentData[activeTab].hadir"></span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-6 bg-gray-50 mt-4 border-t border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-3" x-text="listType == 'hadir' ? 'Daftar Hadir' : 'Belum Absen'"></p>
                    
                    <div class="space-y-2">
                        <template x-for="santri in (listType == 'hadir' ? currentData[activeTab].list_hadir : currentData[activeTab].list_belum)">
                            <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 bg-white shadow-sm">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs" 
                                     :class="listType === 'hadir' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600'">
                                    <span x-text="santri.nama.charAt(0)"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate" x-text="santri.nama"></p>
                                    <p class="text-[10px] text-gray-500 font-mono" x-text="santri.nis"></p>
                                </div>
                            </div>
                        </template>
                        <div x-show="(listType == 'hadir' ? currentData[activeTab].list_hadir.length : currentData[activeTab].list_belum.length) === 0" 
                             class="text-center py-8 text-gray-400 text-xs">
                            Kosong.
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
    
    <script>
        const sholatData = <?php echo json_encode($dataSholat, 15, 512) ?>;
    </script>

    <?php echo $__env->make('layouts.pengurus-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/absensi/jamaah/index.blade.php ENDPATH**/ ?>