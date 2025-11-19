<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Nilai: ') }}{{ $kegiatan->nama_kegiatan }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        Daftar Mata Pelajaran (Kelas: {{ $kelas->nama_kelas }})
                    </h3>

                    <div class="mb-4">
                        {{-- Tombol Kembali --}}
                        <a href="{{ route('sekolah.admin.kegiatan-akademik.kelola.kelas', $kegiatan->id) }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali ke Daftar Kelas</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mapel</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress Input</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($mapelList as $mapel)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $mapel->nama_mapel }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $mapel->completion }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-600 mt-1 block">{{ $mapel->completion }}% Selesai</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-2">
                                            <a href="{{ route('sekolah.admin.kegiatan-akademik.kelola.nilai', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id]) }}" class="text-green-600 hover:text-green-900">
                                                Lihat Nilai
                                            </a>
                                            <a href="{{ route('sekolah.admin.kegiatan-akademik.cetak.nilai', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id]) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                Cetak PDF
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada mata pelajaran terdaftar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>