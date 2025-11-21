<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Ustadz Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <form method="POST" action="{{ route('pendidikan.admin.ustadz.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        {{-- Kolom Kiri: Data Profil --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-emerald-700 border-b pb-2">Data Pribadi</h3>
                            
                            <div>
                                <x-input-label for="nama_lengkap" :value="__('Nama Lengkap (dengan Gelar)')" />
                                <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap')" required autofocus placeholder="Contoh: Ust. Ahmad, Lc." />
                                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nip" :value="__('NIP / NIY (Opsional)')" />
                                <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip" :value="old('nip')" />
                            </div>

                            <div>
                                <x-input-label for="spesialisasi" :value="__('Spesialisasi / Bidang')" />
                                <x-text-input id="spesialisasi" class="block mt-1 w-full" type="text" name="spesialisasi" :value="old('spesialisasi')" placeholder="Contoh: Fiqih, Tahfidz, Nahwu" />
                            </div>

                            <div>
                                <x-input-label for="alamat" :value="__('Alamat')" />
                                <textarea id="alamat" name="alamat" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" rows="3">{{ old('alamat') }}</textarea>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Data Akun --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-emerald-700 border-b pb-2">Data Akun & Kontak</h3>

                            <div class="bg-yellow-50 p-3 rounded-md border border-yellow-200 text-xs text-yellow-800 mb-4">
                                Data ini akan digunakan Ustadz untuk login ke aplikasi.
                            </div>

                            <div>
                                <x-input-label for="no_hp" :value="__('Nomor WhatsApp')" />
                                <x-text-input id="no_hp" class="block mt-1 w-full" type="text" name="no_hp" :value="old('no_hp')" required placeholder="0812xxxx" />
                                <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email Login')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="text" name="password" required placeholder="Minimal 6 karakter" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('pendidikan.admin.ustadz.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                        <x-primary-button class="bg-emerald-600 hover:bg-emerald-700">
                            {{ __('Simpan Data Ustadz') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>