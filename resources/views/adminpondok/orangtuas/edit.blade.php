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
                    <h2 class="text-xl font-bold text-gray-800">Edit Data Orang Tua</h2>
                    <p class="text-sm text-gray-500">Perbarui informasi profil atau reset password.</p>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('adminpondok.orang-tuas.update', $orangTua->id) }}">
                        @csrf
                        @method('PUT') {{-- PENTING UNTUK EDIT --}}

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            {{-- KOLOM KIRI: Data Profil --}}
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-emerald-700 border-b border-emerald-100 pb-2">Informasi Profil</h3>
                                
                                <div>
                                    <x-input-label for="name" :value="__('Nama Lengkap')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $orangTua->name)" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="phone" :value="__('No. Telepon (WhatsApp)')" />
                                    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $orangTua->phone)" />
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('Alamat Lengkap')" />
                                    <textarea id="address" name="address" rows="4" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm placeholder-gray-400">{{ old('address', $orangTua->address) }}</textarea>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>
                            </div>

                            {{-- KOLOM KANAN: Akun Login --}}
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-emerald-700 border-b border-emerald-100 pb-2">Akun Login Aplikasi</h3>

                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full bg-gray-100 cursor-not-allowed" type="email" name="email" :value="old('email', $orangTua->user->email)" readonly />
                                    <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah untuk menjaga konsistensi akun.</p>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100 mt-4">
                                    <p class="text-xs text-yellow-800 font-bold mb-2">Ubah Password (Opsional)</p>
                                    <div class="space-y-4">
                                        <div>
                                            <x-input-label for="password" :value="__('Password Baru')" />
                                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" />
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" placeholder="Ulangi password baru" />
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('adminpondok.orang-tuas.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="px-6 py-2.5">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>