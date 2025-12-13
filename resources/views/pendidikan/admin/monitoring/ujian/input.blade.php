<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Input Nilai {{ ucfirst($jenis) }} - {{ $mapel->nama_mapel }}
            </h2>
            <a href="{{ route('pendidikan.admin.monitoring.ujian.detail', ['mustawa' => $mustawa->id, 'mapel' => $mapel->id]) }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('pendidikan.admin.monitoring.ujian.update', ['mustawa' => $mustawa->id, 'mapel' => $mapel->id, 'jenis' => $jenis]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="semester" value="{{ $semester }}">
                    <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">

                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Santri</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nilai {{ ucfirst($jenis) }} (0-100)
                                        </th>
                                        @if($jenis == 'tulis')
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nilai Kehadiran (Opsional)
                                        </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($santris as $index => $santri)
                                    @php
                                        $nilai = $existingNilai[$santri->id] ?? null;
                                        $val = null;
                                        if($nilai) {
                                            if($jenis == 'tulis') $val = $nilai->nilai_tulis;
                                            elseif($jenis == 'lisan') $val = $nilai->nilai_lisan;
                                            elseif($jenis == 'praktek') $val = $nilai->nilai_praktek;
                                            elseif($jenis == 'hafalan') $val = $nilai->nilai_hafalan;
                                        }
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $santri->full_name }}
                                            <div class="text-xs text-gray-400">{{ $santri->nis }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" step="0.01" min="0" max="100" 
                                                   name="nilai[{{ $santri->id }}]" 
                                                   value="{{ $val }}"
                                                   class="border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm w-32">
                                        </td>
                                        @if($jenis == 'tulis')
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" step="0.01" min="0" max="100" 
                                                   name="kehadiran[{{ $santri->id }}]" 
                                                   value="{{ $nilai->nilai_kehadiran ?? '' }}"
                                                   placeholder="0-100"
                                                   class="border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm w-32 bg-gray-50">
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow">
                            Simpan Data Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>