<x-app-layout hide-nav>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profil Saya') }}
            </h2>
            {{-- Tombol Kembali ke Dashboard --}}
            <a href="{{ route('ustadz.dashboard') }}" class="text-gray-500 hover:text-emerald-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </a>
        </div>
    </x-slot>

    {{-- Style untuk menyembunyikan navbar bawaan AppLayout jika belum otomatis --}}
    <style>
        nav.bg-white.border-b { display: none !important; }
        .min-h-screen { background-color: #f3f4f6; }
    </style>

    <div class="py-6 px-4 max-w-md mx-auto pb-24">

        {{-- 1. Kartu Profil Header --}}
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-lg mb-6 relative overflow-hidden">
            <div class="relative z-10 flex items-center gap-4">
                {{-- Avatar Inisial --}}
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white text-2xl font-bold border-2 border-white/30">
                    {{ substr($ustadz->nama_lengkap ?? $user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold leading-tight">{{ $ustadz->nama_lengkap ?? $user->name }}</h2>
                    <p class="text-xs text-emerald-100 mt-1 font-medium bg-emerald-800/30 px-2 py-0.5 rounded-lg inline-block">
                        {{ $ustadz->spesialisasi ?? 'Pengajar Diniyah' }}
                    </p>
                    <p class="text-xs text-emerald-200 mt-1">{{ $user->email }}</p>
                </div>
            </div>
            
            {{-- Dekorasi Background --}}
            <svg class="absolute -bottom-6 -right-6 w-32 h-32 text-white opacity-10 rotate-12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
        </div>

        {{-- Flash Message Success --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-sm flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-sm">{{ session('success') }}</span>
            </div>
        @endif

        {{-- 2. Form Edit Profil --}}
        <form action="{{ route('ustadz.profil.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 space-y-4">
                <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-2 mb-2">Data Diri</h3>

                {{-- Nama Lengkap --}}
                <div>
                    <x-input-label for="nama_lengkap" value="Nama Lengkap" class="text-xs uppercase text-gray-500" />
                    <x-text-input id="nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full text-sm rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" :value="old('nama_lengkap', $ustadz->nama_lengkap ?? $user->name)" required />
                    <x-input-error class="mt-1" :messages="$errors->get('nama_lengkap')" />
                </div>

                {{-- NIP (Read Only) --}}
                @if($ustadz->nip)
                <div>
                    <x-input-label value="NIP / ID Pengajar" class="text-xs uppercase text-gray-500" />
                    <div class="mt-1 w-full bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-lg px-3 py-2">
                        {{ $ustadz->nip }}
                    </div>
                </div>
                @endif

                {{-- Grid Layout --}}
                <div class="grid grid-cols-1 gap-4">
                    {{-- No HP --}}
                    <div>
                        <x-input-label for="no_hp" value="No. WhatsApp" class="text-xs uppercase text-gray-500" />
                        <x-text-input id="no_hp" name="no_hp" type="text" class="mt-1 block w-full text-sm rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" :value="old('no_hp', $ustadz->no_hp)" placeholder="08..." />
                    </div>

                    {{-- Spesialisasi --}}
                    <div>
                        <x-input-label for="spesialisasi" value="Spesialisasi" class="text-xs uppercase text-gray-500" />
                        <x-text-input id="spesialisasi" name="spesialisasi" type="text" class="mt-1 block w-full text-sm rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" :value="old('spesialisasi', $ustadz->spesialisasi)" placeholder="Contoh: Fiqh" />
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <x-input-label for="alamat" value="Alamat Domisili" class="text-xs uppercase text-gray-500" />
                    <textarea id="alamat" name="alamat" rows="3" class="mt-1 block w-full text-sm rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm" placeholder="Alamat lengkap...">{{ old('alamat', $ustadz->alamat) }}</textarea>
                </div>
            </div>

            {{-- 3. Ganti Password --}}
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 space-y-4">
                <h3 class="font-bold text-gray-800 border-b border-gray-100 pb-2 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Keamanan Akun
                </h3>
                
                <div>
                    <x-input-label for="password" value="Password Baru (Opsional)" class="text-xs uppercase text-gray-500" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full text-sm rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" autocomplete="new-password" placeholder="Biarkan kosong jika aman" />
                    <x-input-error class="mt-1" :messages="$errors->get('password')" />
                </div>
                
                <div>
                    <x-input-label for="password_confirmation" value="Ulangi Password" class="text-xs uppercase text-gray-500" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full text-sm rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500" autocomplete="new-password" placeholder="Konfirmasi password baru" />
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="pt-2">
                <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:bg-emerald-700 transition active:scale-95 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>

        {{-- 4. Tombol Logout --}}
        <div class="mt-8">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="return confirm('Yakin ingin keluar?');" class="w-full group bg-red-50 border border-red-100 text-red-600 font-medium py-3.5 px-4 rounded-xl hover:bg-red-100 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar Aplikasi
                </button>
            </form>
            <p class="text-center text-xs text-gray-400 mt-4">Versi Aplikasi 1.0.0</p>
        </div>

    </div>
</x-app-layout>