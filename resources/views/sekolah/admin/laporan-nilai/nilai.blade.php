<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nilai ') }} {{ $mapel->nama_mapel }} ({{ $kegiatan->nama_kegiatan }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-4 flex justify-between items-center">
                        <a href="{{ route('sekolah.admin.kegiatan-akademik.kelola.mapel', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id]) }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali ke Mapel</a>
                        
                        <a href="{{ route('sekolah.admin.kegiatan-akademik.cetak.nilai', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id]) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                            Cetak Ledger
                        </a>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        Daftar Nilai Siswa (Kelas {{ $kelas->nama_kelas }})
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($santris as $santri)
                                    @php
                                        $nilai = $existingNilai[$santri->id] ?? null;
                                        $isMissing = is_null($nilai);
                                    @endphp
                                    <tr class class="{{ $isMissing ? 'bg-red-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $santri->full_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $santri->nis }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold {{ $isMissing ? 'text-red-500' : 'text-gray-800' }}">
                                            {{ $isMissing ? 'BELUM DIINPUT' : number_format($nilai, 1) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada santri di kelas ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>