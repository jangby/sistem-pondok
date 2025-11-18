<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Guru Pengganti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Jadwal Kosong Hari Ini ({{ now()->format('d M Y') }})
                    </h3>
                    
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg text-sm font-medium">{{ session('success') }}</div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Mapel / Kelas</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Guru Asli</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tugaskan Pengganti</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($kelasKosong as $item)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $item['jadwal']->jam_mulai }} - {{ $item['jadwal']->jam_selesai }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <div class="font-bold">{{ $item['jadwal']->mataPelajaran->nama_mapel }}</div>
                                            <div class="text-xs text-gray-500">{{ $item['jadwal']->kelas->nama_kelas }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            {{ $item['jadwal']->guru->name }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ $item['status_guru_asli'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                                            <form method="POST" action="{{ route('sekolah.admin.guru-pengganti.store') }}" class="flex gap-2">
                                                @csrf
                                                <input type="hidden" name="jadwal_id" value="{{ $item['jadwal']->id }}">
                                                <input type="hidden" name="absensi_pelajaran_id" value="{{ $item['absensi_pelajaran_id'] }}">
                                                
                                                <select name="guru_pengganti_id" class="text-xs border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                                    <option value="">-- Pilih Guru --</option>
                                                    @foreach($availableGurus as $guru)
                                                        @if($guru->id != $item['jadwal']->guru_user_id)
                                                            <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    Simpan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-center text-sm text-gray-500">Tidak ada kelas kosong yang membutuhkan pengganti saat ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>