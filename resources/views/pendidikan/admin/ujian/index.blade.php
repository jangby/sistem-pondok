<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Jadwal Ujian Pesantren') }}
            </h2>
            <a href="{{ route('pendidikan.admin.ujian.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-emerald-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Buat Jadwal
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- FILTER BOX --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
                <form method="GET" action="{{ route('pendidikan.admin.ujian.index') }}" class="flex flex-wrap items-end gap-4">
                    
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Jenis Ujian</label>
                        <select name="jenis_ujian" class="block w-32 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="uts" {{ $jenisUjian == 'uts' ? 'selected' : '' }}>UTS</option>
                            <option value="uas" {{ $jenisUjian == 'uas' ? 'selected' : '' }}>UAS</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Semester</label>
                        <select name="semester" class="block w-32 mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="ganjil" {{ $semester == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ $semester == 'genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>

                    <div class="flex-grow">
                        <label class="text-xs font-bold text-gray-500 uppercase">Filter Kelas</label>
                        <select name="mustawa_id" class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Semua Kelas</option>
                            @foreach($mustawas as $m)
                                <option value="{{ $m->id }}" {{ request('mustawa_id') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-md text-sm font-bold hover:bg-gray-700">
                        Filter
                    </button>
                </form>
            </div>

            {{-- TABEL JADWAL --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal & Jam</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas (Mustawa)</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mata Ujian</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pengawas</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Metode</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($jadwals as $jadwal)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-800">{{ $jadwal->tanggal->isoFormat('dddd, D MMM Y') }}</div>
                                        <div class="text-xs text-emerald-600 font-mono">
                                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold">
                                            {{ $jadwal->mustawa->nama }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $jadwal->mapel->nama_mapel }}</div>
                                        <div class="text-xs text-gray-500">{{ $jadwal->mapel->nama_kitab }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $jadwal->pengawas->nama_lengkap }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($jadwal->kategori_tes == 'tulis')
                                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">Tulis</span>
                                        @elseif($jadwal->kategori_tes == 'lisan')
                                            <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded">Lisan</span>
                                        @else
                                            <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded">Praktek</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center flex justify-center gap-2">
    {{-- Tombol Kelola --}}
    <a href="{{ route('pendidikan.admin.ujian.show', $jadwal->id) }}" class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-md text-xs font-bold hover:bg-emerald-200 transition">
        Kelola
    </a>
    
    {{-- Tombol Hapus --}}
    <form action="{{ route('pendidikan.admin.ujian.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ujian ini?');">
        @csrf @method('DELETE')
        <button class="bg-red-100 text-red-700 px-3 py-1 rounded-md text-xs font-bold hover:bg-red-200 transition">
            Hapus
        </button>
    </form>
</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                                        Belum ada jadwal ujian untuk filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100">
                    {{ $jadwals->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>