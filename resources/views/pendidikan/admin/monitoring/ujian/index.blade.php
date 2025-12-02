<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring Input Nilai Ujian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Filter Tahun Ajaran (Opsional, UI Sederhana) --}}
            <div class="mb-6 bg-white p-4 rounded-lg shadow-sm flex items-center justify-between">
                <span class="font-bold text-gray-700">T.A: {{ $tahunAjaran }} | Semester: {{ ucfirst($semester) }}</span>
                <span class="text-xs text-gray-500">*Gunakan parameter URL ?semester=genap&tahun_ajaran=2024/2025 untuk ganti</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($mustawas as $mustawa)
                <a href="{{ route('pendidikan.admin.monitoring.ujian.mapel', ['mustawa' => $mustawa->id, 'semester' => $semester, 'tahun_ajaran' => $tahunAjaran]) }}" 
                   class="block bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800">{{ $mustawa->nama_mustawa ?? $mustawa->nama }}</h3>
                            <span class="text-xs font-semibold px-2 py-1 bg-gray-100 rounded text-gray-600">
                                Tingkat {{ $mustawa->tingkat }}
                            </span>
                        </div>
                        
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                            <div class="bg-emerald-600 h-2.5 rounded-full" style="width: {{ $mustawa->progress }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Progress</span>
                            <span class="font-bold">{{ $mustawa->progress }}%</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>