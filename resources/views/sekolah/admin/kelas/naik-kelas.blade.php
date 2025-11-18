<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Naik Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <p class="mb-4 text-sm text-gray-600">
                        Gunakan fitur ini untuk memindahkan semua santri dari satu kelas ke kelas lain secara massal.
                    </p>

                    <form method="POST" action="{{ route('sekolah.admin.kelas.naikKelas.process') }}" onsubmit="return confirm('Anda yakin? Tindakan ini akan memindahkan SEMUA santri di kelas asal ke kelas tujuan.');">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="kelas_asal_id" :value="__('Pindahkan Santri DARI Kelas:')" />
                                <select name="kelas_asal_id" id="kelas_asal_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Kelas Asal (Hanya {{ $sekolah->tingkat }}) --</option>
                                    @foreach($kelasSekolahIni as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('kelas_asal_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="kelas_tujuan_id" :value="__('Pindahkan Santri KE Kelas:')" />
                                <select name="kelas_tujuan_id" id="kelas_tujuan_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">-- Pilih Kelas Tujuan (Semua Tingkat) --</option>
                                    @foreach($kelasList as $tingkat => $kelases)
                                        <optgroup label="Tingkat: {{ $tingkat }}">
                                            @foreach($kelases as $kelas)
                                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('kelas_tujuan_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="bg-blue-600 hover:bg-blue-500">
                                {{ __('Proses Pindah Kelas Massal') }}
                            </x-primary-button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>