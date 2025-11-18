<x-app-layout>
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-3">
            
            {{-- Kiri: Spacer (Kosong agar seimbang) --}}
            <div class="hidden md:block w-1/4">
            </div>

            {{-- Tengah: Switch Guru/Siswa --}}
            <div class="flex justify-center w-full md:w-2/4">
                <div class="bg-gray-100 p-1 rounded-xl inline-flex shadow-inner">
                    {{-- Tombol Guru (Link) --}}
                    <a href="{{ route('sekolah.admin.monitoring.guru') }}" class="px-6 py-2 text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-gray-200 rounded-lg transition-all">
                        Monitoring Guru
                    </a>

                    {{-- Tombol Siswa (Aktif) --}}
                    <span class="px-6 py-2 text-sm font-bold text-gray-800 bg-white rounded-lg shadow-sm cursor-default transition-all">
                        Monitoring Siswa
                    </span>
                </div>
            </div>

            {{-- Kanan: Tombol Laporan Kedisiplinan --}}
            <div class="flex justify-end w-full md:w-1/4">
                {{-- PERBAIKAN DISINI: Mengganti bg-teal-600 menjadi bg-emerald-600 (atau bg-gray-800) --}}
                <a href="{{ route('sekolah.admin.kinerja.siswa') }}" 
                   class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                    
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
            
            {{-- Tampilkan jika hari libur --}}
            @if($isHariLibur || !$isHariKerja)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <h3 class="text-lg font-medium text-gray-900">Hari Libur</h3>
                        <p class="text-sm text-gray-600 mt-2">
                            Tidak ada kegiatan belajar mengajar hari ini.
                        </p>
                    </div>
                </div>

            {{-- Tampilkan Dashboard Normal --}}
            @else
                <div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h4 class="text-xs font-medium text-gray-500 uppercase">Total Siswa</h4>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $kpi_total }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h4 class="text-xs font-medium text-gray-500 uppercase">Hadir Tepat Waktu</h4>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $kpi_hadir }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h4 class="text-xs font-medium text-gray-500 uppercase">Terlambat</h4>
                        <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $kpi_terlambat }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h4 class="text-xs font-medium text-gray-500 uppercase">Alpa / Belum Scan</h4>
                        <p class="text-3xl font-bold text-red-600 mt-1">{{ $kpi_alpa }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h4 class="text-xs font-medium text-gray-500 uppercase">Sakit/Izin</h4>
                        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $kpi_sakit_izin }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Belum Hadir ({{ count($daftarBelumHadir) }})
                            </h3>
                            
                            <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($daftarBelumHadir as $santri)
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <p class="text-sm font-medium text-gray-900">{{ $santri->full_name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $santri->nis }}</p>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                                                    {{ $santri->kelas->nama_kelas }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                    @if($santri->status_hari_ini == 'sakit')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Sakit</span>
                                                    @elseif($santri->status_hari_ini == 'izin')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Izin</span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Alpa</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-4 py-3 text-center text-sm text-gray-500">Semua siswa sudah hadir!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Sudah Hadir ({{ count($daftarHadir) }})
                            </h3>
                            
                            <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jam Masuk</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($daftarHadir as $santri)
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <p class="text-sm font-medium text-gray-900">{{ $santri->full_name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $santri->kelas->nama_kelas }}</p>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-600">
                                                    {{ \Carbon\Carbon::parse($santri->data_absen->jam_masuk)->format('H:i') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                    @if($santri->status_hari_ini == 'terlambat')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Terlambat</span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tepat Waktu</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-4 py-3 text-center text-sm text-gray-500">Belum ada siswa yang hadir.</td>
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