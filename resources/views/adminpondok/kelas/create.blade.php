<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('adminpondok.kelas.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Tambah Kelas Baru</h2>
                    <p class="text-sm text-gray-500">Buat rombongan belajar baru.</p>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('adminpondok.kelas.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="nama_kelas" :value="__('Nama Kelas')" />
                                <x-text-input id="nama_kelas" class="block mt-1 w-full" type="text" name="nama_kelas" :value="old('nama_kelas')" required autofocus placeholder="Contoh: 1A, 7B, Takhosus 1" />
                                <x-input-error :messages="$errors->get('nama_kelas')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="tingkat" :value="__('Tingkat / Jenjang')" />
                                <x-text-input id="tingkat" class="block mt-1 w-full" type="text" name="tingkat" :value="old('tingkat')" placeholder="Contoh: SMP, SMA, Ula, Wustho" />
                                <p class="text-xs text-gray-500 mt-1">Digunakan untuk pengelompokan dalam laporan atau tagihan.</p>
                                <x-input-error :messages="$errors->get('tingkat')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('adminpondok.kelas.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="px-6 py-2.5">
                                {{ __('Simpan Kelas') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>