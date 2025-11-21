<x-app-layout>
    <x-slot name="header">
        {{-- Header Modern dengan Sapaan Personal --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                    Dashboard <span class="text-indigo-600">{{ $sekolah->nama_sekolah ?? 'Sekolah' }}</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Selamat datang kembali, Admin. Inilah ringkasan sekolah hari ini.</p>
            </div>
            <div class="flex items-center bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                <span class="text-sm font-semibold text-gray-600">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- SECTION 1: ALERT & CRITICAL STATS (Top Priority) --}}
            {{-- Hanya muncul jika ada masalah (Contoh logika: ada guru Alpa) --}}
            @if(($guruAlpa ?? 0) > 0 || ($siswaAlpa ?? 0) > 50)
            <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg flex items-start justify-between shadow-sm">
                <div class="flex gap-3">
                    <svg class="w-6 h-6 text-rose-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <h4 class="font-bold text-rose-800">Perhatian Diperlukan</h4>
                        <p class="text-sm text-rose-700 mt-1">
                            Terdeteksi <strong>{{ $guruAlpa ?? 0 }} Guru Alpa</strong> dan <strong>{{ $siswaAlpa ?? 0 }} Siswa Alpa</strong> hari ini. Segera cek laporan kehadiran.
                        </p>
                    </div>
                </div>
                <a href="{{ route('sekolah.admin.monitoring.guru') }}" class="text-xs font-bold bg-rose-200 text-rose-800 px-3 py-1.5 rounded hover:bg-rose-300 transition">
                    Cek Monitoring &rarr;
                </a>
            </div>
            @endif

            {{-- SECTION 2: KEY METRICS (Hero Stats) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Card Siswa --}}
                <div class="relative group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110">
                        <svg class="w-24 h-24 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Siswa</p>
                        <h3 class="text-4xl font-black text-gray-800 mt-2">{{ $totalSiswa ?? 0 }}</h3>
                        
                        {{-- Progress Kehadiran Siswa --}}
                        @php 
                            $persenHadirSiswa = ($totalSiswa > 0) ? round((($siswaHadir ?? 0) / $totalSiswa) * 100) : 0;
                        @endphp
                        <div class="mt-4">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-semibold text-blue-600">Kehadiran Hari Ini</span>
                                <span class="font-bold text-gray-700">{{ $persenHadirSiswa }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-cyan-400 h-2 rounded-full" style="width: {{ $persenHadirSiswa }}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">
                                <span class="text-green-500 font-bold">● {{ $siswaHadir ?? 0 }}</span> Hadir &nbsp; 
                                <span class="text-red-400 font-bold">● {{ $siswaAlpa ?? 0 }}</span> Alpa
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card Guru --}}
                <div class="relative group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110">
                        <svg class="w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M20 6h-1v2h-2v-2h-1c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-12c0-1.1-.9-2-2-2zm-5 10h-2v-2h2v2zm0-4h-2v-2h2v2zm4 4h-2v-2h2v2zm0-4h-2v-2h2v2zM3 6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h4v-2H3V8h2V6H3z"/></svg>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Guru</p>
                        <h3 class="text-4xl font-black text-gray-800 mt-2">{{ $totalGuru ?? 0 }}</h3>

                        {{-- Progress Kehadiran Guru --}}
                        @php 
                            $persenHadirGuru = ($totalGuru > 0) ? round((($guruHadir ?? 0) / $totalGuru) * 100) : 0;
                        @endphp
                        <div class="mt-4">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-semibold text-emerald-600">Kehadiran Hari Ini</span>
                                <span class="font-bold text-gray-700">{{ $persenHadirGuru }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-2 rounded-full" style="width: {{ $persenHadirGuru }}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">
                                <span class="text-emerald-500 font-bold">● {{ $guruHadir ?? 0 }}</span> Hadir &nbsp; 
                                <span class="text-yellow-500 font-bold">● {{ ($guruSakit ?? 0) + ($guruIzin ?? 0) }}</span> Izin/Skt
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card Kelas / Asset --}}
                <div class="relative group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 overflow-hidden">
                     <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110">
                        <svg class="w-24 h-24 text-purple-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
                    </div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Rombel</p>
                            <h3 class="text-4xl font-black text-gray-800 mt-2">{{ $totalKelas ?? 0 }}</h3>
                            <p class="text-sm text-purple-600 mt-1 font-medium">Ruang Kelas Aktif</p>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-dashed border-gray-200">
                            <a href="{{ route('sekolah.admin.kelas.index') }}" class="flex items-center justify-between text-sm font-bold text-gray-600 hover:text-purple-600 transition">
                                <span>Manajemen Kelas</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: MAIN CONTENT GRID (Charts & Quick Actions) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- LEFT COLUMN (2/3): CHART & SCHEDULE --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- 3A: CHART ATTENDANCE --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Tren Kehadiran Siswa</h3>
                                <p class="text-xs text-gray-500">Statistik 7 hari terakhir</p>
                            </div>
                            <select class="text-xs border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option>Minggu Ini</option>
                                <option>Bulan Ini</option>
                            </select>
                        </div>
                        <div class="relative h-72 w-full">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>

                    {{-- 3B: JADWAL KOSONG / GURU PENGGANTI (Jika Ada) --}}
                    <div class="bg-gradient-to-br from-indigo-900 to-slate-800 rounded-2xl p-6 text-white shadow-lg overflow-hidden relative">
                         {{-- Background Decoration --}}
                         <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-5 rounded-full blur-3xl"></div>
                         
                         <div class="relative z-10 flex justify-between items-center mb-4">
                             <h3 class="text-lg font-bold">Guru Pengganti Diperlukan</h3>
                             <span class="px-2 py-1 bg-white/20 text-xs rounded backdrop-blur-sm border border-white/10">Hari Ini</span>
                         </div>
                         
                         <div class="space-y-3">
                            {{-- Contoh Static Item (Ganti dengan Looping Data Real nanti) --}}
                            @if(isset($kelasKosong) && count($kelasKosong) > 0)
                                @foreach($kelasKosong as $kosong)
                                <div class="flex items-center justify-between bg-white/10 p-3 rounded-lg hover:bg-white/20 transition border border-white/5">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-red-500/20 text-red-200 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-sm">{{ $kosong['mapel'] ?? 'Mapel' }} - {{ $kosong['kelas'] ?? 'Kelas' }}</p>
                                            <p class="text-xs text-gray-300">Guru Asli: {{ $kosong['guru'] ?? 'Nama Guru' }} ({{ $kosong['status'] ?? 'Absen' }})</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('sekolah.admin.guru-pengganti.index') }}" class="text-xs bg-indigo-500 hover:bg-indigo-400 px-3 py-1.5 rounded font-medium transition">
                                        Cari Pengganti
                                    </a>
                                </div>
                                @endforeach
                            @else
                                <div class="flex flex-col items-center justify-center py-6 text-gray-300">
                                    <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-sm">Semua kelas memiliki guru saat ini.</p>
                                </div>
                            @endif
                         </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN (1/3): QUICK ACTIONS & AGENDA --}}
                <div class="space-y-8">
                    
                    {{-- 4A: QUICK ACCESS GRID --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Akses Cepat</h3>
                        <div class="grid grid-cols-2 gap-4">
                            
                            <a href="{{ route('sekolah.admin.monitoring.siswa') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-blue-50 hover:border-blue-200 hover:-translate-y-1 transition transform duration-200 group">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-2 group-hover:bg-blue-600 group-hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 group-hover:text-blue-700">Absensi Siswa</span>
                            </a>

                            <a href="{{ route('sekolah.admin.laporan.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-emerald-50 hover:border-emerald-200 hover:-translate-y-1 transition transform duration-200 group">
                                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-2 group-hover:bg-emerald-600 group-hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 group-hover:text-emerald-700">Cetak Laporan</span>
                            </a>

                            <a href="{{ route('sekolah.admin.jadwal-pelajaran.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-purple-50 hover:border-purple-200 hover:-translate-y-1 transition transform duration-200 group">
                                <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-2 group-hover:bg-purple-600 group-hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 group-hover:text-purple-700">Jadwal Mapel</span>
                            </a>

                            <a href="{{ route('sekolah.admin.kegiatan-akademik.index') }}" class="flex flex-col items-center justify-center p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-amber-50 hover:border-amber-200 hover:-translate-y-1 transition transform duration-200 group">
                                <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mb-2 group-hover:bg-amber-600 group-hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                </div>
                                <span class="text-xs font-semibold text-gray-600 group-hover:text-amber-700">Kelola Ujian</span>
                            </a>

                        </div>
                    </div>

                    {{-- 4B: AGENDA TERDEKAT (Static Example) --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Agenda Akademik</h3>
                        <div class="space-y-4">
                            {{-- Timeline Item --}}
                            @if(isset($agendaTerdekat) && count($agendaTerdekat) > 0)
                                @foreach($agendaTerdekat as $agenda)
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                                        <div class="w-0.5 h-full bg-gray-100 my-1"></div>
                                    </div>
                                    <div class="pb-4">
                                        <h5 class="text-sm font-bold text-gray-800">{{ $agenda->nama_kegiatan }}</h5>
                                        <p class="text-xs text-gray-500 mb-1">
                                            {{ \Carbon\Carbon::parse($agenda->tanggal_mulai)->format('d M') }} - 
                                            {{ \Carbon\Carbon::parse($agenda->tanggal_selesai)->format('d M Y') }}
                                        </p>
                                        <span class="inline-block px-2 py-0.5 text-[10px] font-semibold rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100">
                                            {{ $agenda->tipe }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <p class="text-sm text-gray-400 italic">Tidak ada agenda dekat.</p>
                                </div>
                            @endif
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-100">
                            <a href="{{ route('sekolah.admin.kegiatan-akademik.create') }}" class="text-xs font-medium text-blue-600 hover:text-blue-800 flex items-center justify-center">
                                + Tambah Agenda Baru
                            </a>
                        </div>
                    </div>
                    
                    {{-- 4C: MONITOR WIFI & KIOS --}}
                    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 h-full">
    {{-- Header Kartu --}}
    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Status Sistem
    </h3>

    <div class="space-y-3">
        
        {{-- Item 1: Kios Absensi --}}
        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
            <div class="flex items-center gap-3">
                {{-- Indikator Hijau Berdenyut (Ping Animation) --}}
                <div class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </div>
                <span class="text-sm font-bold text-gray-700">Kios Absensi</span>
            </div>
            <a href="{{ route('sekolah.admin.konfigurasi.kios.show') }}" target="_blank" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
                Buka Layar
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>

        {{-- Item 2: WiFi --}}
        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
            <div class="flex items-center gap-3">
                {{-- Indikator Hijau Statis --}}
                <div class="w-3 h-3 bg-emerald-500 rounded-full shadow-sm"></div>
                <span class="text-sm font-bold text-gray-700">WiFi Absensi</span>
            </div>
            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100">
                Aktif
            </span>
        </div>


                </div>
            </div>

        </div>
    </div>

    {{-- Script Chart.js (Dipercantik) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            
            // Buat Gradient agar terlihat modern
            let gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)'); // Indigo
            gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

            const labels = @json($chartLabels ?? []);
            const dataHadir = @json($chartData ?? []);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Siswa Hadir',
                        data: dataHadir,
                        borderColor: '#4F46E5', // Indigo 600
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4F46E5',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.4 // Membuat garis melengkung halus (Curved)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1E293B',
                            padding: 12,
                            titleFont: { size: 13 },
                            bodyFont: { size: 14, weight: 'bold' },
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [4, 4], color: '#E2E8F0' },
                            ticks: { color: '#64748B', font: { size: 11 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748B', font: { size: 11 } }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        });
    </script>
</x-app-layout>