<x-app-layout>
    {{-- Padding atas dikurangi agar form naik ke atas --}}
    <div class="py-6">
        {{-- Ubah max-w-2xl menjadi max-w-7xl agar lebar memenuhi layar --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 h-full">
                
                {{-- HEADER: Judul & Tombol Kembali sejajar agar hemat tempat vertikal --}}
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Registrasi Pondok Baru</h2>
                    <a href="{{ route('superadmin.pondoks.index') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>

                <div class="p-8 text-gray-900">
                    <form method="POST" action="{{ route('superadmin.pondoks.store') }}">
                        @csrf

                        {{-- GRID LAYOUT: Membagi layar menjadi 2 kolom (Kiri 7 bagian, Kanan 5 bagian) --}}
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                            
                            {{-- KOLOM KIRI (Data Identitas & Status) --}}
                            <div class="lg:col-span-7 space-y-5">
                                {{-- Nama Pondok --}}
                                <div>
                                    <x-input-label for="name" :value="__('Nama Pondok Pesantren')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="Cth: Ponpes Al-Hidayah" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    {{-- Telepon --}}
                                    <div>
                                        <x-input-label for="phone" :value="__('Nomor Telepon (Opsional)')" />
                                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" placeholder="08..." />
                                        <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                                    </div>

                                    {{-- Status Awal --}}
                                    <div>
                                        <x-input-label for="status" :value="__('Status Langganan')" />
                                        <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                            <option value="trial" {{ old('status') == 'trial' ? 'selected' : '' }}>Trial (Percobaan)</option>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active (Langsung Aktif)</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive (Non-Aktif)</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('status')" class="mt-1" />
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN (Alamat - Dibuat tinggi agar seimbang) --}}
                            <div class="lg:col-span-5 flex flex-col">
                                <x-input-label for="address" :value="__('Alamat Lengkap')" />
                                {{-- h-full class membuat textarea mengisi sisa ruang vertikal --}}
                                <textarea id="address" name="address" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm placeholder-gray-400 h-32 lg:h-full resize-none" placeholder="Jalan, RT/RW, Kecamatan, Kabupaten/Kota...">{{ old('address') }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-1" />
                            </div>

                        </div>

                        {{-- FOOTER TOMBOL --}}
                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                            <x-primary-button class="w-full sm:w-auto justify-center text-base py-3">
                                {{ __('Simpan & Buat Pondok') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>