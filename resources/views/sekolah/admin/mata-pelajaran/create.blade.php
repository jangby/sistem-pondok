<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Mata Pelajaran Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('sekolah.admin.mata-pelajaran.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="nama_mapel" :value="__('Nama Mata Pelajaran')" />
                            <x-text-input id="nama_mapel" class="block mt-1 w-full" type="text" name="nama_mapel" :value="old('nama_mapel')" required autofocus placeholder="Cth: Fiqih" />
                            <x-input-error :messages="$errors->get('nama_mapel')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="kode_mapel" :value="__('Kode Mata Pelajaran (Opsional)')" />
                            <x-text-input id="kode_mapel" class="block mt-1 w-full" type="text" name="kode_mapel" :value="old('kode_mapel')" placeholder="Cth: FQH-01" />
                            <x-input-error :messages="$errors->get('kode_mapel')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>