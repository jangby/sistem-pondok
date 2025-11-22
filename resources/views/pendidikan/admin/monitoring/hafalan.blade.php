<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Hafalan Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Filter Section --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('pendidikan.admin.monitoring.hafalan') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        
                        <div class="col-span-2 flex gap-2">
                            <div class="w-full">
                                <x-input-label value="Dari" />
                                <x-text-input type="date" name="start_date" value="{{ $tanggalAwal }}" class="w-full mt-1 text-sm" />
                            </div>
                            <div class="w-full">
                                <x-input-label value="Sampai" />
                                <x-text-input type="date" name="end_date" value="{{ $tanggalAkhir }}" class="w-full mt-1 text-sm" />
                            </div>
                        </div>

                        <div>
                            <x-input-label value="Penyimak (Ustadz)" />
                            <select name="ustadz_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                <option value="">Semua</option>
                                @foreach($ustadzs as $u)
                                    <option value="{{ $u->id }}" {{ request('ustadz_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Kelas Santri" />
                            <select name="mustawa_id" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm">
                                <option value="">Semua</option>
                                @foreach($mustawas as $m)
                                    <option value="{{ $m->id }}" {{ request('mustawa_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Cari Nama Santri" />
                            <x-text-input type="text" name="nama_santri" value="{{ request('nama_santri') }}" placeholder="Nama..." class="w-full mt-1 text-sm" />
                        </div>

                        <div class="flex items-end">
                            <x-primary-button class="w-full justify-center h-10">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Filter
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Data --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-lg text-emerald-700">Riwayat Setoran Hafalan</h3>
                        <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Total: {{ $jurnals->total() }} Setoran</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Waktu</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Santri</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Jenis</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Materi Hafalan</th>
                                    <th class="px-4 py-3 text-center font-medium text-gray-500 uppercase">Predikat</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase">Penyimak</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($jurnals as $jurnal)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="font-bold text-gray-700">
                                                {{ \Carbon\Carbon::parse($jurnal->tanggal)->locale('id')->isoFormat('D MMM Y') }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $jurnal->created_at->format('H:i') }} WIB
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-gray-800">{{ $jurnal->santri->nama_lengkap ?? $jurnal->santri->full_name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $jurnal->santri->mustawa->nama ?? 'Kelas Tidak Diketahui' }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col gap-1">
                                                <span class="px-2 py-0.5 w-fit rounded text-[10px] font-bold uppercase tracking-wider {{ $jurnal->kategori_hafalan == 'quran' ? 'bg-emerald-100 text-emerald-700' : 'bg-purple-100 text-purple-700' }}">
                                                    {{ $jurnal->kategori_hafalan }}
                                                </span>
                                                <span class="px-2 py-0.5 w-fit rounded text-[10px] border {{ $jurnal->jenis_setoran == 'ziyadah' ? 'border-blue-200 text-blue-600 bg-blue-50' : 'border-amber-200 text-amber-600 bg-amber-50' }}">
                                                    {{ ucfirst($jurnal->jenis_setoran) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-gray-800 font-medium">{{ $jurnal->materi }}</div>
                                            @if($jurnal->start_at)
                                                <div class="text-xs text-gray-500">
                                                    {{ $jurnal->start_at }} s/d {{ $jurnal->end_at }}
                                                </div>
                                            @endif
                                            @if($jurnal->catatan)
                                                <div class="text-xs text-gray-400 italic mt-1">
                                                    "{{ Str::limit($jurnal->catatan, 30) }}"
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @php
                                                $color = match($jurnal->predikat) {
                                                    'A', 'Mumtaz' => 'text-green-600 bg-green-100',
                                                    'B', 'Jayyid' => 'text-blue-600 bg-blue-100',
                                                    'C', 'Maqbul' => 'text-amber-600 bg-amber-100',
                                                    default => 'text-red-600 bg-red-100',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm {{ $color }}">
                                                {{ $jurnal->predikat }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm text-gray-600">{{ $jurnal->ustadz->nama_lengkap ?? '-' }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-10 text-center text-gray-400">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-10 h-10 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                Belum ada data hafalan pada periode ini.
                                            </div>
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