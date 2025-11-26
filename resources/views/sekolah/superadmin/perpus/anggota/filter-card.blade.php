<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cetak Kartu Perpustakaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Box Filter --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('sekolah.superadmin.perpustakaan.anggota.kartu') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="w-full md:w-1/3">
                            <x-input-label for="kelas_id" :value="__('Pilih Kelas')" />
                            <select name="kelas_id" id="kelas_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }} ({{ $kelas->tingkat }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <x-primary-button>
                            {{ __('Tampilkan Siswa') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>

            {{-- Hasil Filter --}}
            @if(isset($santris))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-4">
                        <h3 class="font-bold text-lg text-gray-800">Daftar Siswa ({{ $santris->count() }})</h3>
                        <a href="{{ request()->fullUrlWithQuery(['print' => 'true']) }}" target="_blank" 
                           class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 shadow transition flex items-center gap-2">
                           <span>🖨️</span> Cetak Semua
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($santris as $santri)
                            <div class="border p-4 rounded-lg flex items-center gap-3 bg-gray-50 hover:bg-gray-100 transition">
                                <div class="w-12 h-12 bg-gray-300 rounded-full flex-shrink-0 overflow-hidden">
                                     <img src="https://ui-avatars.com/api/?name={{ urlencode($santri->name) }}" alt="" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-gray-900">{{ $santri->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $santri->nis }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>