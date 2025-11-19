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
                        
                        <div class="flex gap-2">
                            {{-- Tombol 1: Daftar Hadir --}}
                            <a href="{{ route('sekolah.admin.kegiatan-akademik.cetak.daftar-hadir', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id]) }}" 
                               target="_blank" 
                               class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                Daftar Hadir
                            </a>

                            {{-- Tombol 2: Format Nilai --}}
                            <a href="{{ route('sekolah.admin.kegiatan-akademik.cetak.format-nilai', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id]) }}" 
                               target="_blank" 
                               class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Format Nilai
                            </a>

                            {{-- Tombol 3: Ledger (Yang Lama) --}}
                            <a href="{{ route('sekolah.admin.kegiatan-akademik.cetak.nilai', ['kegiatan' => $kegiatan->id, 'kelas' => $kelas->id, 'mapel' => $mapel->id]) }}" 
                               target="_blank" 
                               class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Ledger
                            </a>
                        </div>
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