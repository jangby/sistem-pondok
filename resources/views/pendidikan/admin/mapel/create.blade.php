<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Mata Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <form method="POST" action="{{ route('pendidikan.admin.mapel.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="col-span-2">
                            <x-input-label for="nama_mapel" :value="__('Nama Mata Pelajaran (Kategori)')" />
                            <x-text-input id="nama_mapel" class="block mt-1 w-full" type="text" name="nama_mapel" :value="old('nama_mapel')" required placeholder="Contoh: Fiqih Ibadah" autofocus />
                            <p class="text-xs text-gray-500 mt-1">Nama pelajaran yang muncul di rapor.</p>
                        </div>

                        <div class="col-span-2">
                            <x-input-label for="nama_kitab" :value="__('Nama Kitab Referensi')" />
                            <x-text-input id="nama_kitab" class="block mt-1 w-full" type="text" name="nama_kitab" :value="old('nama_kitab')" required placeholder="Contoh: Safinatun Najah" />
                        </div>

                        <div>
                            <x-input-label for="kode_mapel" :value="__('Kode Mapel (Opsional)')" />
                            <x-text-input id="kode_mapel" class="block mt-1 w-full" type="text" name="kode_mapel" :value="old('kode_mapel')" placeholder="FQ-01" />
                        </div>

                        <div>
                            <x-input-label for="kkm" :value="__('KKM (Standar Kelulusan)')" />
                            <x-text-input id="kkm" class="block mt-1 w-full" type="number" name="kkm" :value="old('kkm', 60)" required min="0" max="100" />
                        </div>
                    </div>

                    <div class="mb-6 border-t border-gray-100 pt-4">
                        <span class="block text-sm font-medium text-gray-700 mb-3">Metode Penilaian (Centang yang berlaku)</span>
                        <div class="flex space-x-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="uji_tulis" value="1" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" checked>
                                <span class="ml-2 text-gray-600">Ujian Tulis</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="uji_lisan" value="1" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                                <span class="ml-2 text-gray-600">Ujian Lisan / Syafahi</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="uji_praktek" value="1" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                                <span class="ml-2 text-gray-600">Ujian Praktek</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('pendidikan.admin.mapel.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                        <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">
                            {{ __('Simpan Data') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>