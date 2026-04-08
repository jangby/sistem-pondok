<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        
        {{-- HEADER --}}
        <div class="bg-emerald-600 pt-6 pb-24 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('pengurus.kios.index') }}" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">Rekap Kehadiran</h1>
                        <p class="text-emerald-100 text-xs font-medium">Laporan Kinerja Penjaga Gerbang</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- KONTEN --}}
        <div class="px-5 -mt-16 relative z-20 space-y-6 max-w-5xl mx-auto">
            
            {{-- Filter & Tombol Cetak --}}
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-5 flex flex-col sm:flex-row justify-between gap-4 items-center">
                <form action="{{ route('pengurus.rekap-gerbang.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <select name="bulan" class="bg-gray-50 border border-gray-200 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 w-full sm:w-32 p-2.5">
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ sprintf('%02d', $i) }}" {{ $bulan == sprintf('%02d', $i) ? 'selected' : '' }}>Bulan {{ $i }}</option>
                        @endfor
                    </select>
                    <select name="tahun" class="bg-gray-50 border border-gray-200 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 w-full sm:w-28 p-2.5">
                        @for($t=date('Y'); $t>=date('Y')-2; $t--)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-emerald-100 text-emerald-700 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-emerald-200 transition">Filter</button>
                </form>

                <a href="{{ route('pengurus.rekap-gerbang.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Unduh Laporan PDF
                </a>
            </div>

            {{-- Tabel Rekap --}}
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-lg">Periode: {{ $namaBulan }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100/50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="p-4 font-bold border-b border-gray-100">Nama Santri</th>
                                <th class="p-4 font-bold border-b border-gray-100 text-center">Kehadiran Pagi</th>
                                <th class="p-4 font-bold border-b border-gray-100 text-center">Kehadiran Sore</th>
                                <th class="p-4 font-bold border-b border-gray-100 text-center">Total Dinas</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($rekap as $data)
                                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                                    <td class="p-4 font-bold text-gray-800">{{ $data['nama'] }}</td>
                                    <td class="p-4 text-center">
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg font-bold">{{ $data['hadir_pagi'] }}x</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-lg font-bold">{{ $data['hadir_sore'] }}x</span>
                                    </td>
                                    <td class="p-4 text-center font-bold text-gray-500">{{ $data['total_tugas'] }} Hari</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-400 italic">Belum ada data absensi untuk bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>