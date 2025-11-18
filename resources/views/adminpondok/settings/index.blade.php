<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Pengaturan Identitas</h2>
                    <p class="text-sm text-gray-500">Atur profil, logo, dan kontak resmi pondok pesantren Anda.</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <form method="POST" action="{{ route('adminpondok.pengaturan.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="p-8 grid grid-cols-1 lg:grid-cols-3 gap-10">
                        
                        {{-- KOLOM KIRI: Logo --}}
                        <div class="lg:col-span-1 space-y-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-1">Logo Pondok</h3>
                                <p class="text-sm text-gray-500">Akan ditampilkan di kop laporan dan tagihan.</p>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 flex flex-col items-center justify-center text-center">
                                @if ($setting->logo_url)
                                    <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 mb-4 p-2">
                                        <img src="{{ asset($setting->logo_url) }}" alt="Logo Pondok" class="max-w-full max-h-full object-contain">
                                    </div>
                                    <p class="text-xs text-emerald-600 font-medium">Logo Aktif</p>
                                @else
                                    <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <p class="text-xs text-gray-500">Belum ada logo</p>
                                @endif
                            </div>

                            <div>
                                <x-input-label for="logo" :value="__('Ganti Logo')" />
                                <input id="logo" class="block mt-1 w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-emerald-50 file:text-emerald-700
                                    hover:file:bg-emerald-100
                                    cursor-pointer border border-gray-300 rounded-lg" 
                                    type="file" name="logo" accept="image/png, image/jpeg" />
                                <p class="text-xs text-gray-500 mt-2">Format: PNG, JPG. Maks: 1MB.</p>
                                <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                            </div>
                        </div>

                        {{-- KOLOM KANAN: Form Data --}}
                        <div class="lg:col-span-2 space-y-6 border-l border-gray-100 pl-0 lg:pl-10">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-1">Informasi Umum</h3>
                                <p class="text-sm text-gray-500">Data ini akan muncul di dashboard wali santri.</p>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <x-input-label for="nama_resmi" :value="__('Nama Resmi Pondok')" />
                                    <x-text-input id="nama_resmi" class="block mt-1 w-full text-lg" type="text" name="nama_resmi" :value="old('nama_resmi', $setting->nama_resmi)" required placeholder="Contoh: Pondok Pesantren Al-Ikhlas" />
                                    <x-input-error :messages="$errors->get('nama_resmi')" class="mt-2" />
                                </div>
                                
                                <div>
                                    <x-input-label for="telepon" :value="__('Nomor Telepon / WhatsApp Admin')" />
                                    <x-text-input id="telepon" class="block mt-1 w-full" type="text" name="telepon" :value="old('telepon', $setting->telepon)" placeholder="0812..." />
                                    <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="alamat" :value="__('Alamat Lengkap')" />
                                    <textarea id="alamat" name="alamat" rows="4" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm placeholder-gray-400" placeholder="Jalan, Desa, Kecamatan, Kabupaten...">{{ old('alamat', $setting->alamat) }}</textarea>
                                    <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    {{-- FOOTER --}}
                    <div class="bg-gray-50 px-8 py-5 flex items-center justify-between border-t border-gray-100">
                        <p class="text-xs text-gray-500">
                            Terakhir diperbarui: {{ $setting->updated_at ? $setting->updated_at->diffForHumans() : 'Belum pernah' }}
                        </p>
                        <x-primary-button class="px-6 py-2.5">
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>