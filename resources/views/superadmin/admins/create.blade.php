<x-app-layout>
    {{-- Header dihapus agar bersih --}}

    <div class="py-6">
        {{-- Container lebar maksimal --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                {{-- HEADER KARTU --}}
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Daftarkan Admin Pondok Baru</h2>
                    <a href="{{ route('superadmin.pondoks.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>

                <div class="p-8 text-gray-900">
                    
                    <form method="POST" action="{{ route('superadmin.admins.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            {{-- KOLOM KIRI: Informasi Penugasan --}}
                            <div class="space-y-5">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-semibold text-emerald-700">Informasi Penugasan</h3>
                                    <p class="text-xs text-gray-500">Tentukan pondok dan identitas admin.</p>
                                </div>
                                
                                <div>
                                    <x-input-label for="pondok_id" :value="__('Pilih Pondok')" />
                                    <select name="pondok_id" id="pondok_id" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                        <option value="">-- Pilih Pondok --</option>
                                        @foreach ($pondoks as $pondok)
                                            <option value="{{ $pondok->id }}" {{ old('pondok_id') == $pondok->id ? 'selected' : '' }}>
                                                {{ $pondok->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Admin ini akan memiliki akses penuh ke data pondok yang dipilih.</p>
                                    <x-input-error :messages="$errors->get('pondok_id')" class="mt-1" />
                                </div>

                                <div>
                                    <x-input-label for="name" :value="__('Nama Lengkap Admin')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required placeholder="Nama Lengkap" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email (Untuk Login)')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required placeholder="alamat@email.com" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                                </div>
                            </div>

                            {{-- KOLOM KANAN: Keamanan Akun --}}
                            <div class="space-y-5">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-semibold text-emerald-700">Keamanan Akun</h3>
                                    <p class="text-xs text-gray-500">Buat password yang kuat untuk admin.</p>
                                </div>

                                <div>
                                    <x-input-label for="password" :value="__('Password')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                                </div>

                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
                                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required placeholder="Ulangi password" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                                </div>

                                {{-- Info Box --}}
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 mt-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <div>
                                            <h4 class="text-sm font-bold text-blue-800">Catatan Penting</h4>
                                            <p class="text-xs text-blue-700 mt-1">
                                                Pastikan email yang didaftarkan aktif. Admin yang didaftarkan akan langsung berstatus <strong>Aktif</strong> dan bisa login untuk mengelola pondok.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- FOOTER TOMBOL --}}
                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('superadmin.pondoks.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <x-primary-button class="w-full sm:w-auto justify-center text-base py-3">
                                {{ __('Daftarkan Admin') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>