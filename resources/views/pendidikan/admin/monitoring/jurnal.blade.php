<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Jurnal Mengajar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Card Filter --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('pendidikan.admin.monitoring.jurnal') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        
                        <div class="col-span-2 flex gap-2">
                            <div class="w-full">
                                <x-input-label value="Dari Tanggal" />
                                <x-text-input type="date" name="start_date" value="{{ $tanggalAwal }}" class="w-full mt-1" />
                            </div>
                            <div class="w-full">
                                <x-input-label value="Sampai" />
                                <x-text-input type="date" name="end_date" value="{{ $tanggalAkhir }}" class="w-full mt-1" />
                            </div>
                        </div>

                        <div>
                            <x-input-label value="Filter Ustadz" />
                            <select name="ustadz_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Semua Ustadz</option>
                                @foreach($ustadzs as $u)
                                    <option value="{{ $u->id }}" {{ request('ustadz_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Filter Kelas" />
                            <select name="mustawa_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Semua Kelas</option>
                                @foreach($mustawas as $m)
                                    <option value="{{ $m->id }}" {{ request('mustawa_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <x-primary-button class="w-full justify-center h-10">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Filter Data
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Data --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-lg">Riwayat Mengajar</h3>
                        <span class="text-sm text-gray-500">Total: {{ $jurnals->total() }} Data</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Tanggal / Jam</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Ustadz</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Kelas & Mapel</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Materi yang Disampaikan</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($jurnals as $jurnal)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-bold text-gray-800">
                                                {{ \Carbon\Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('dddd, D MMM Y') }}
                                            </div>
                                            {{-- Menampilkan jam jika ada di relasi jadwal --}}
                                            <div class="text-xs text-gray-500">
                                                {{ $jurnal->jadwal->jam_mulai ?? '-' }} - {{ $jurnal->jadwal->jam_selesai ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium">{{ $jurnal->ustadz->nama_lengkap }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-md bg-emerald-100 text-emerald-700 font-bold text-xs">
                                                {{ $jurnal->jadwal->mustawa->nama ?? '?' }}
                                            </span>
                                            <div class="mt-1 text-gray-600">{{ $jurnal->jadwal->mapel->nama_mapel ?? '?' }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-gray-800">{{ $jurnal->materi }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 italic">
                                            {{ $jurnal->catatan ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                            Tidak ada data jurnal pada periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $jurnals->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>