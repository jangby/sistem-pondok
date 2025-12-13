<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Monitoring: {{ $mustawa->nama_mustawa ?? $mustawa->nama }}
            </h2>
            <a href="{{ route('pendidikan.admin.monitoring.ujian.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($mapels as $mapel)
                        <a href="{{ route('pendidikan.admin.monitoring.ujian.detail', ['mustawa' => $mustawa->id, 'mapel' => $mapel->id, 'semester' => $semester, 'tahun_ajaran' => $tahunAjaran]) }}" 
                           class="border rounded-lg p-4 hover:bg-emerald-50 transition group cursor-pointer">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-gray-800 group-hover:text-emerald-700">{{ $mapel->nama_mapel }}</h4>
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $mapel->kode_mapel }}</span>
                            </div>
                            
                            {{-- Indikator Tipe Ujian --}}
                            <div class="flex gap-1 mb-3 text-[10px] uppercase text-gray-500">
                                @if($mapel->uji_tulis) <span class="border px-1 rounded">Tulis</span> @endif
                                @if($mapel->uji_lisan) <span class="border px-1 rounded">Lisan</span> @endif
                                @if($mapel->uji_praktek) <span class="border px-1 rounded">Praktek</span> @endif
                                @if($mapel->uji_hafalan) <span class="border px-1 rounded">Hafalan</span> @endif
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-1.5 mb-1">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $mapel->progress }}%"></div>
                            </div>
                            <div class="text-right text-xs font-bold text-blue-600">{{ $mapel->progress }}% Terisi</div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>