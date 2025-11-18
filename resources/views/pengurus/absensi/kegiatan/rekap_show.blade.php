<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    {{-- Alpine Data --}}
    <div class="min-h-screen bg-gray-50 pb-28" 
         x-data="{ 
            activeTab: 'Laki-laki', 
            showList: false, 
            listType: 'hadir', // 'hadir' atau 'belum'
            
            openList(type) {
                this.listType = type;
                this.showList = true;
            }
         }">
        
        {{-- Header --}}
        <div class="bg-white px-6 py-4 border-b border-gray-100 sticky top-0 z-30 flex justify-between items-center shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('pengurus.absensi.kegiatan.rekap') }}" class="text-gray-500 hover:text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div class="overflow-hidden">
                    <h1 class="font-bold text-lg text-gray-800 leading-tight truncate max-w-[200px]">{{ $kegiatan->nama_kegiatan }}</h1>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider">{{ $kegiatan->frekuensi }}</p>
                </div>
            </div>
            
            {{-- Filter Tanggal --}}
            <form method="GET">
                <input type="date" name="date" value="{{ $tanggal }}" class="py-1.5 px-3 rounded-lg border-gray-200 text-xs focus:ring-emerald-500 shadow-sm" onchange="this.form.submit()">
            </form>
        </div>

        <div class="p-5">
            
            {{-- Tab Putra/Putri --}}
            <div class="flex p-1 bg-gray-100 rounded-xl mb-6 shadow-inner">
                <button @click="activeTab = 'Laki-laki'; renderChart('Laki-laki')" 
                    :class="activeTab === 'Laki-laki' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-2.5 text-xs font-bold rounded-lg transition duration-200">
                    PUTRA
                </button>
                <button @click="activeTab = 'Perempuan'; renderChart('Perempuan')" 
                    :class="activeTab === 'Perempuan' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                    class="flex-1 py-2.5 text-xs font-bold rounded-lg transition duration-200">
                    PUTRI
                </button>
            </div>

            {{-- Container Grafik & Statistik --}}
            <div class="bg-white rounded-[30px] shadow-lg shadow-emerald-50 border border-gray-100 p-6 mb-6 relative overflow-hidden">
                
                {{-- Grafik --}}
                <div class="relative h-56 w-56 mx-auto">
                    <canvas id="chartCanvas"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span id="persenText" class="text-4xl font-black text-emerald-600">0%</span>
                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Kehadiran</span>
                    </div>
                </div>

                {{-- Tombol Statistik (Bisa Diklik) --}}
                <div class="grid grid-cols-2 gap-3 mt-8">
                    {{-- Tombol Hadir --}}
                    <div @click="openList('hadir')" class="bg-emerald-50 p-3 rounded-2xl border border-emerald-100 text-center cursor-pointer active:scale-95 transition hover:bg-emerald-100 group">
                        <span class="block text-[10px] text-emerald-600 font-bold uppercase mb-1 group-hover:text-emerald-700">Sudah Hadir</span>
                        <span id="hadirText" class="text-2xl font-black text-emerald-700">0</span>
                        <div class="mt-1 text-[10px] text-emerald-400 flex justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Lihat
                        </div>
                    </div>

                    {{-- Tombol Belum --}}
                    <div @click="openList('belum')" class="bg-red-50 p-3 rounded-2xl border border-red-100 text-center cursor-pointer active:scale-95 transition hover:bg-red-100 group">
                        <span class="block text-[10px] text-red-600 font-bold uppercase mb-1 group-hover:text-red-700">Belum Hadir</span>
                        <span id="belumText" class="text-2xl font-black text-red-700">0</span>
                        <div class="mt-1 text-[10px] text-red-400 flex justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Lihat
                        </div>
                    </div>
                </div>
                
                {{-- Info Sakit/Izin --}}
                <p class="text-center text-[10px] text-gray-400 mt-4 bg-gray-50 py-1 rounded-lg mx-auto w-3/4">
                    Tidak wajib: <span id="sakitCount" class="font-bold text-gray-600">0</span> Sakit • 
                    <span id="izinCount" class="font-bold text-gray-600">0</span> Izin
                </p>
            </div>
        </div>

        {{-- ===================================
             MODAL DAFTAR NAMA (Slide Up)
             =================================== --}}
        <div x-show="showList" class="fixed inset-0 z-50 flex items-end justify-center sm:items-center" style="display: none;">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="showList = false"></div>
            
            {{-- Modal Content --}}
            <div class="bg-white w-full max-w-md rounded-t-[30px] relative z-10 shadow-2xl transform transition-transform max-h-[80vh] flex flex-col"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                
                {{-- Handle Bar --}}
                <div class="w-full flex justify-center pt-3 pb-1 cursor-pointer" @click="showList = false">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>
                
                {{-- Modal Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800" x-text="listType == 'hadir' ? 'Daftar Hadir' : 'Belum Absen'"></h3>
                        <p class="text-xs text-gray-500">
                            Kategori: <span x-text="activeTab" class="font-bold"></span>
                        </p>
                    </div>
                    <button @click="showList = false" class="bg-gray-100 p-2 rounded-full text-gray-500 hover:bg-gray-200 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- List Data (Scrollable) --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-2 min-h-[300px] bg-gray-50">
                    
                    {{-- Data Inject from PHP --}}
                    <script> const detailData = @json($detail); </script>
                    
                    {{-- Loop Template Alpine --}}
                    <template x-for="santri in detailData[listType][activeTab]" :key="santri.id">
                        <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 bg-white shadow-sm">
                            
                            {{-- Avatar --}}
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-inner" 
                                 :class="listType === 'hadir' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600'">
                                <span x-text="santri.full_name.charAt(0)"></span>
                            </div>
                            
                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate" x-text="santri.full_name"></p>
                                <p class="text-[10px] text-gray-500 font-mono bg-gray-100 inline-block px-1.5 rounded mt-0.5" x-text="santri.nis"></p>
                            </div>

                            {{-- Status Badge --}}
                            <span class="text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide"
                                  :class="listType === 'hadir' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100'"
                                  x-text="listType === 'hadir' ? 'OK' : '-'"></span>
                        </div>
                    </template>

                    {{-- Empty State --}}
                    <div x-show="!detailData[listType][activeTab] || detailData[listType][activeTab].length === 0" 
                         class="flex flex-col items-center justify-center py-12 text-gray-400">
                        <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs">Tidak ada data dalam kategori ini.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('layouts.pengurus-nav')

    {{-- CHART SCRIPT --}}
    <script>
        const statsData = @json($stats);
        let myChart = null;

        function renderChart(gender) {
            const data = statsData[gender];
            const belumHadir = data.wajib - data.hadir;
            
            // Update Angka
            document.getElementById('persenText').innerText = data.persen + '%';
            document.getElementById('hadirText').innerText = data.hadir;
            document.getElementById('belumText').innerText = belumHadir;
            document.getElementById('sakitCount').innerText = data.sakit;
            document.getElementById('izinCount').innerText = data.izin;

            // Render Chart
            const ctx = document.getElementById('chartCanvas').getContext('2d');
            if (myChart) myChart.destroy();

            myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Belum'],
                    datasets: [{
                        data: [data.hadir, belumHadir],
                        backgroundColor: ['#10b981', '#e5e7eb'], // Emerald-500, Gray-200
                        borderWidth: 0,
                        cutout: '85%', // Lebih tipis
                        borderRadius: 30
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

        // Render Awal
        window.onload = () => renderChart('Laki-laki');
    </script>
</x-app-layout>