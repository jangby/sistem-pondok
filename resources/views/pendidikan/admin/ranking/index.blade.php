<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ranking Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 border-b pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Lihat Peringkat Juara Kelas</h3>
                    <p class="text-sm text-gray-500">
                        Sistem akan menghitung skor akhir berdasarkan bobot: 
                        <strong>40% Akademik, 30% Kedisiplinan, 20% Sikap, dan 10% Keterampilan.</strong>
                    </p>
                </div>

                {{-- Pastikan route ini sesuai dengan yang ada di routes/pesantren.php --}}
                <form action="{{ route('pendidikan.admin.ranking.show') }}" method="GET">
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas (Mustawa)</label>
                            <select name="mustawa_id" required class="w-full md:w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($mustawas as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama }} (Tingkat {{ $m->tingkat }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-start">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-lg flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Lihat Ranking</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>