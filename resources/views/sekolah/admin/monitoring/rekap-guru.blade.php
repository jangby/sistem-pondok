<x-app-layout>
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-3">
                
                {{-- Kiri: Spacer (atau Judul Dashboard jika mau) --}}
                <div class="hidden md:block w-1/4">
                    {{-- Kosong agar seimbang --}}
                </div>

                {{-- Tengah: Switch Guru/Siswa --}}
                <div class="flex justify-center w-full md:w-2/4">
                    <div class="bg-gray-100 p-1 rounded-xl inline-flex shadow-inner">
                        {{-- Tombol Guru (Aktif) --}}
                        <span class="px-6 py-2 text-sm font-bold text-gray-800 bg-white rounded-lg shadow-sm cursor-default transition-all">
                            Monitoring Guru
                        </span>
                        
                        {{-- Tombol Siswa (Link) --}}
                        <a href="{{ route('sekolah.admin.monitoring.siswa') }}" class="px-6 py-2 text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-gray-200 rounded-lg transition-all">
                            Monitoring Siswa
                        </a>
                    </div>
                </div>

                {{-- Kanan: Tombol Laporan Kinerja --}}
                <div class="flex justify-end w-full md:w-1/4">
                    <a href="{{ route('sekolah.admin.kinerja.guru') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                        <span class="hidden md:inline">Laporan Kinerja</span>
                        <span class="md:hidden">Laporan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tampilkan error jika setting belum diatur --}}
            @if(session('error') || isset($error))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="p-4 bg-red-100 text-red-700 rounded-lg text-sm font-medium">
                            {{ session('error') ?? $error }}
                        </div>
                    </div>
                </div>
            
            {{-- Tampilkan jika hari libur --}}
            @elseif($isHariLibur || !$isHariKerja)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <h3 class="text-lg font-medium text-gray-900">Hari Libur</h3>
                        <p class="text-sm text-gray-600 mt-2">
                            @if($isHariLibur)
                                Hari ini adalah Hari Libur Nasional/Sekolah.
                            @else
                                Hari ini ({{ $namaHariIni }}) bukan hari kerja.
                            @endif
                        </p>
                    </div>
                </div>

            {{-- Tampilkan Dashboard Normal --}}
            @else
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-6">
                    {{-- [PERBAIKAN DESAIN] Menggunakan style kartu yang konsisten --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h4 class="text-xs font-medium text-gray-500 uppercase">Hadir Tepat Waktu</h4>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $kpi_hadir }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h4 class="text-xs font-medium text-gray-500 uppercase">Terlambat</h4>
                        <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $kpi_terlambat }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h4 class="text-xs font-medium text-gray-500 uppercase">Alpa</h4>
                        <p class="text-3xl font-bold text-red-600 mt-1">{{ $kpi_alpa }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h4 class="text-xs font-medium text-gray-500 uppercase">Sakit/Izin</h4>
                        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $kpi_sakit_izin }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h4 class="text-xs font-medium text-gray-500 uppercase">Jam Kosong</h4>
                        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $kpi_jam_kosong }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Guru Belum Hadir ({{ count($daftarBelumHadir) }})
                            </h3>
                            
                            {{-- [PERBAIKAN DESAIN] Menggunakan Tabel --}}
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($daftarBelumHadir as $guru)
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <p class="text-sm font-medium text-gray-900">{{ $guru->name ?? $guru->guru->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $guru->email ?? $guru->guru->email }}</p>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                    @if($guru instanceof \App\Models\Sekolah\AbsensiGuru && $guru->status == 'sakit')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Sakit</span>
                                                    @elseif($guru instanceof \App\Models\Sekolah\AbsensiGuru && $guru->status == 'izin')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Izin</span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Alpa</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="px-4 py-3 text-center text-sm text-gray-500">Semua guru sudah hadir.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Guru Sudah Hadir ({{ count($daftarHadir) }})
                            </h3>
                            
                            {{-- [PERBAIKAN DESAIN] Menggunakan Tabel --}}
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jam Masuk</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($daftarHadir as $absensi)
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <p class="text-sm font-medium text-gray-900">{{ $absensi->guru->name }}</p>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <p class="text-sm text-gray-500">{{ $absensi->jam_masuk }}</p>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                    @if($absensi->jam_masuk > $settings->batas_telat)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Terlambat</span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tepat Waktu</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-4 py-3 text-center text-sm text-gray-500">Belum ada guru yang hadir.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>