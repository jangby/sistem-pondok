<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buka Gelombang PPDB Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('adminpondok.ppdb.setting.store') }}" method="POST">
                        @csrf
                        
                        {{-- Tahun Ajaran --}}
                        <div class="mb-4">
                            <x-input-label for="tahun_ajaran" :value="__('Tahun Ajaran')" />
                            <x-text-input id="tahun_ajaran" class="block mt-1 w-full" type="text" name="tahun_ajaran" :value="old('tahun_ajaran', date('Y').'/'.(date('Y')+1))" required />
                            <x-input-error :messages="$errors->get('tahun_ajaran')" class="mt-2" />
                        </div>

                        {{-- Nama Gelombang --}}
                        <div class="mb-4">
                            <x-input-label for="nama_gelombang" :value="__('Nama Gelombang')" />
                            <x-text-input id="nama_gelombang" class="block mt-1 w-full" type="text" name="nama_gelombang" placeholder="Contoh: Gelombang 1" required />
                            <x-input-error :messages="$errors->get('nama_gelombang')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Tanggal Mulai --}}
                            <div class="mb-4">
                                <x-input-label for="tanggal_mulai" :value="__('Tanggal Dibuka')" />
                                <x-text-input id="tanggal_mulai" class="block mt-1 w-full" type="date" name="tanggal_mulai" required />
                            </div>

                            {{-- Tanggal Akhir --}}
                            <div class="mb-4">
                                <x-input-label for="tanggal_akhir" :value="__('Tanggal Ditutup')" />
                                <x-text-input id="tanggal_akhir" class="block mt-1 w-full" type="date" name="tanggal_akhir" required />
                            </div>
                        </div>

                        {{-- Biaya Pendaftaran --}}
                        <div class="mb-4">
                            <x-input-label for="biaya_pendaftaran" :value="__('Biaya Pendaftaran (Rp)')" />
                            <x-text-input id="biaya_pendaftaran" class="block mt-1 w-full" type="number" name="biaya_pendaftaran" placeholder="Contoh: 100000" required />
                            <p class="text-xs text-gray-500 mt-1">Isi 0 jika gratis.</p>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-4">
                            <x-input-label for="deskripsi" :value="__('Keterangan Tambahan (Opsional)')" />
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                        </div>

                        {{-- Checkbox Aktif --}}
                        <div class="block mt-4">
                            <label for="is_active" class="inline-flex items-center">
                                <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" checked>
                                <span class="ms-2 text-sm text-gray-600">{{ __('Langsung Aktifkan Gelombang Ini?') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('adminpondok.ppdb.setting.index') }}" class="text-gray-600 hover:text-gray-900">Batal</a>
                            <x-primary-button class="bg-emerald-600 hover:bg-emerald-500">
                                {{ __('Simpan & Buka Pendaftaran') }}
                            </x-primary-button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>