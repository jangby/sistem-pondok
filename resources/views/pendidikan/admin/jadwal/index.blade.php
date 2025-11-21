<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Jadwal Pelajaran (Ngaji)') }}
            </h2>
            <a href="{{ route('pendidikan.admin.jadwal.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition">
                + Buat Jadwal
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Filter --}}
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-6">
                <form method="GET" action="{{ route('pendidikan.admin.jadwal.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <x-input-label for="hari" :value="__('Filter Hari')" />
                        <select name="hari" class="block mt-1 w-40 border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm text-sm">
                            <option value="">Semua Hari</option>
                            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Ahad'] as $h)
                                <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="mustawa_id" :value="__('Filter Kelas')" />
                        <select name="mustawa_id" class="block mt-1 w-48 border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm text-sm">
                            <option value="">Semua Kelas</option>
                            @foreach($mustawas as $m)
                                <option value="{{ $m->id }}" {{ request('mustawa_id') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pb-1">
                        <x-primary-button class="bg-gray-800 hover:bg-gray-700">Cari</x-primary-button>
                        @if(request()->hasAny(['hari', 'mustawa_id']))
                            <a href="{{ route('pendidikan.admin.jadwal.index') }}" class="ml-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari & Jam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengajar</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($jadwals as $jadwal)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-emerald-600">{{ $jadwal->hari }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded bg-gray-100 border border-gray-200 font-medium">
                                        {{ $jadwal->mustawa->nama }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $jadwal->mapel->nama_mapel }}</div>
                                    <div class="text-xs text-gray-500 italic">{{ $jadwal->mapel->nama_kitab }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center text-[10px] text-blue-600 mr-2 font-bold">
                                            {{ substr($jadwal->ustadz->nama_lengkap, 0, 1) }}
                                        </div>
                                        <span class="text-sm text-gray-700">{{ $jadwal->ustadz->nama_lengkap }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center space-x-3">
                                        <a href="{{ route('pendidikan.admin.jadwal.edit', $jadwal->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form action="{{ route('pendidikan.admin.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    Belum ada jadwal yang dibuat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $jadwals->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>