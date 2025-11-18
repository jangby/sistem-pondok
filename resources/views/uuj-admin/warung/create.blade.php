<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        {{-- Ubah max-w-4xl menjadi max-w-7xl (Lebar Penuh) --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                {{-- HEADER KARTU --}}
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Tambah Warung / POS Baru</h2>
                        <p class="text-sm text-gray-500">Daftarkan outlet baru dan buatkan akun kasirnya.</p>
                    </div>
                    <a href="{{ route('uuj-admin.warung.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('uuj-admin.warung.store') }}">
                        @csrf

                        {{-- GRID 2 KOLOM (Lebar Penuh) --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            
                            {{-- KOLOM KIRI: Identitas Warung --}}
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-semibold text-emerald-700">Identitas Warung</h3>
                                </div>
                                
                                <div>
                                    <x-input-label for="nama_warung" :value="__('Nama Warung')" />
                                    <x-text-input id="nama_warung" class="block mt-1 w-full text-lg" type="text" name="nama_warung" :value="old('nama_warung')" required autofocus placeholder="Cth: Kantin Putra, Koperasi Santri" />
                                    <x-input-error :messages="$errors->get('nama_warung')" class="mt-2" />
                                </div>

                                <div class="bg-emerald-50 p-5 rounded-xl border border-emerald-100 text-sm text-emerald-800 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div>
                                        <p class="font-bold mb-1">Sistem POS Terintegrasi</p>
                                        <p>Setiap warung akan memiliki akun login sendiri. Petugas kasir dapat menggunakan akun tersebut untuk login ke halaman Kasir (Scan Barcode) dan melayani pembelian santri.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: Akun Login --}}
                            <div class="space-y-6">
                                <div class="border-b border-gray-100 pb-2 mb-4">
                                    <h3 class="text-lg font-semibold text-emerald-700">Akun Login Kasir</h3>
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email (Username Login)')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required placeholder="kasir@pondok.com" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="password" :value="__('Password')" />
                                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required placeholder="Min. 8 karakter" />
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="password_confirmation" :value="__('Ulangi Password')" />
                                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required placeholder="Konfirmasi password" />
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                            <a href="{{ route('uuj-admin.warung.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button class="px-6 py-3 text-base">
                                {{ __('Simpan & Buat Akun') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>