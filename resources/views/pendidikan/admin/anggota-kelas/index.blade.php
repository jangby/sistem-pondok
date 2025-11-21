<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pengelolaan Anggota Kelas (Rombel)') }}
            </h2>
            <a href="{{ route('pendidikan.admin.kenaikan-kelas.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Proses Kenaikan Kelas
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($mustawas as $mustawa)
                    <a href="{{ route('pendidikan.admin.anggota-kelas.show', $mustawa->id) }}" class="block bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md hover:border-emerald-300 transition group">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-md">Level {{ $mustawa->tingkat }}</span>
                                    <h3 class="mt-2 text-lg font-bold text-gray-800 group-hover:text-emerald-600 transition">{{ $mustawa->nama }}</h3>
                                    <p class="text-sm text-gray-500">{{ $mustawa->gender == 'putra' ? '👦 Putra' : ($mustawa->gender == 'putri' ? '👧 Putri' : '👫 Campuran') }}</p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-full group-hover:bg-emerald-50 transition">
                                    <svg class="w-6 h-6 text-gray-400 group-hover:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                            </div>
                            <div class="mt-4 border-t border-gray-100 pt-4 flex justify-between items-center">
                                <span class="text-sm text-gray-600">Jumlah Santri:</span>
                                <span class="text-xl font-bold text-gray-900">{{ $mustawa->total_santri }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>