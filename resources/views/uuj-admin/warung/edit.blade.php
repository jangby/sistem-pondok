<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        {{-- Ubah max-w-4xl menjadi max-w-7xl (Lebar Penuh) --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                {{-- HEADER KARTU --}}
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Edit Data Warung</h2>
                        <p class="text-sm text-gray-500">Perbarui nama warung, status, atau reset password kasir.</p>
                    </div>
                    <a href="{{ route('uuj-admin.warung.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('uuj-admin.warung.update', $warung->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            
                            {{-- KOLOM KIRI --}}
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-semibold text-emerald-700">Identitas Warung</h3>
                                </div>
                                
                                <div>
                                    <x-input-label for="nama_warung" :value="__('Nama Warung')" />
                                    <x-text-input id="nama_warung" class="block mt-1 w-full text-lg" type="text" name="nama_warung" :value="old('nama_warung', $warung->nama_warung)" required autofocus />
                                    <x-input-error :messages="$errors->get('nama_warung')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="status" :value="__('Status Operasional')" />
                                    <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                        <option value="active" {{ old('status', $warung->status) == 'active' ? 'selected' : '' }}>Aktif (Buka)</option>
                                        <option value="inactive" {{ old('status', $warung->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif (Tutup)</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Jika status 'Tidak Aktif', akun kasir tidak akan bisa login ke sistem POS.</p>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                            </div>

                            {{-- KOLOM KANAN --}}
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-semibold text-emerald-700">Akun Login Kasir</h3>
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email Login')" />
                                    <x-text-input id="email" class="block mt-1 w-full bg-gray-50" type="email" name="email" :value="old('email', $warung->user->email)" required readonly />
                                    <p class="text-xs text-gray-400 mt-1">Email tidak dapat diubah.</p>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div class="bg-yellow-50 p-5 rounded-xl border border-yellow-100 mt-4">
                                    <p class="text-sm text-yellow-800 font-bold mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                        Ubah Password (Opsional)
                                    </p>
                                    <div class="space-y-4">
                                        <div>
                                            <x-input-label for="password" :value="__('Password Baru')" />
                                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah" />
                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" placeholder="Ulangi password baru" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('uuj-admin.warung.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="px-6 py-3 text-base">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>