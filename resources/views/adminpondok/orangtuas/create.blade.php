<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('adminpondok.orang-tuas.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Tambah Orang Tua Baru</h2>
                    <p class="text-sm text-gray-500">Data ini akan digunakan untuk login aplikasi wali santri.</p>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('adminpondok.orang-tuas.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            {{-- KOLOM KIRI: Data Profil --}}
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-emerald-700 border-b border-emerald-100 pb-2">Informasi Profil</h3>
                                
                                <div>
                                    <x-input-label for="name" :value="__('Nama Lengkap')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="Nama Ayah/Ibu/Wali" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="phone" :value="__('No. Telepon (WhatsApp)')" />
                                    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" placeholder="Contoh: 0812..." />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('Alamat Lengkap')" />
                                    <textarea id="address" name="address" rows="4" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm placeholder-gray-400" placeholder="Alamat domisili saat ini...">{{ old('address') }}</textarea>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>
                            </div>

                            {{-- KOLOM KANAN: Akun Login --}}
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-emerald-700 border-b border-emerald-100 pb-2">Akun Login Aplikasi</h3>

                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 mb-4">
                                    <p class="text-xs text-blue-700">
                                        <strong>Catatan:</strong> Email dan password ini akan digunakan wali santri untuk masuk ke dashboard/aplikasi mereka.
                                    </p>
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required placeholder="email@contoh.com" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="password" :value="__('Password')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required placeholder="Minimal 8 karakter" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required placeholder="Ulangi password" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('adminpondok.orang-tuas.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="px-6 py-2.5">
                                {{ __('Simpan Data Wali') }}
                            </x-primary-button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>