<x-app-layout hide-nav>
    {{-- Header default dikosongkan --}}
    <x-slot name="header"></x-slot>

    {{-- Latar belakang 'off-white' (bg-gray-100) --}}
    <div class="min-h-screen bg-gray-100 pb-24 font-sans">
        
        {{-- 1. MOBILE HEADER SECTION (EMERALD THEME) --}}
        <div class="bg-emerald-600 pt-8 pb-20 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            
            {{-- 
            PERUBAHAN DI SINI:
            Mengganti dekorasi 'blur' dengan bentuk 'shapes' (lingkaran)
            yang lebih jelas dan tumpang tindih untuk efek visual modern.
            --}}
            <div class="absolute -top-10 -right-16 w-48 h-48 bg-emerald-500 opacity-30 rounded-full z-0"></div>
            <div class="absolute -bottom-20 -left-10 w-40 h-40 bg-white opacity-10 rounded-full z-0"></div>
            
            {{-- Konten Header (Harus memiliki 'relative z-10' agar di atas shapes) --}}
            <div class="relative z-10 flex justify-between items-start mt-2">
                <div>
                    <p class="text-emerald-100 text-xs font-medium mb-1 tracking-wide">Selamat Bertugas,</p>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight truncate max-w-[200px]">
                        {{ Auth::user()->name }}
                    </h1>
                    <span class="inline-flex mt-2 px-2.5 py-0.5 rounded-full bg-emerald-700/50 border border-emerald-500/30 text-emerald-50 text-[10px] font-bold uppercase tracking-wider shadow-sm backdrop-blur-sm">
                        Pengurus Pondok
                    </span>
                </div>
                
                {{-- Tombol Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" onclick="confirmLogout()" class="bg-white/10 backdrop-blur-md p-2.5 rounded-xl hover:bg-white/20 transition border border-white/10 shadow-lg active:scale-95">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- 2. FLOATING STATS CARDS --}}
        <div class="px-5 -mt-12 relative z-20">
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl shadow-emerald-900/10 border border-white/50 p-4">
                <div class="flex justify-between items-center divide-x divide-gray-100">
                    {{-- Sakit --}}
                    <div class="flex-1 text-center px-1">
                        <span class="block text-xl font-black text-gray-800">{{ $sakitHariIni ?? 0 }}</span>
                        <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide">Sakit</span>
                    </div>

                    {{-- Izin --}}
                    <div class="flex-1 text-center px-1">
                        <span class="block text-xl font-black text-gray-800">{{ $izinKeluarAktif ?? 0 }}</span>
                        <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide">Izin</span>
                    </div>

                    {{-- Hadir --}}
                    <div class="flex-1 text-center px-1">
                        <span class="block text-xl font-black text-gray-800">{{ $hadirHariIni ?? 0 }}</span>
                        <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide">Hadir</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. WIDGET MENU UTAMA --}}
        <div class="px-5 mt-6">
            <div class="bg-white rounded-2xl p-5 shadow-lg shadow-emerald-900/5 border border-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-800 text-base">Menu Utama</h3>
                    <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-1 rounded-md font-medium">Akses Cepat</span>
                </div>
                
                <div class="grid grid-cols-4 gap-y-6 gap-x-2">
                    
                    {{-- Menu 1: UKS --}}
                    <a href="{{ route('pengurus.uks.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100 group-active:scale-95 transition duration-200">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-emerald-600">UKS</span>
                    </a>

                    {{-- Menu 2: Perizinan --}}
                    <a href="{{ route('pengurus.perizinan.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center shadow-sm border border-amber-100 group-active:scale-95 transition duration-200">
                            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-emerald-600">Izin</span>
                    </a>

                    {{-- Menu 3: Absensi --}}
                    <a href="{{ route('pengurus.absensi.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center shadow-sm border border-emerald-100 group-active:scale-95 transition duration-200">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-emerald-600">Absen</span>
                    </a>

                    {{-- Menu 4: Santri --}}
                    <a href="{{ route('pengurus.santri.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center shadow-sm border border-blue-100 group-active:scale-95 transition duration-200">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-emerald-600">Data Santri</span>
                    </a>

                    {{-- Menu 5: Inventaris --}}
                    <a href="{{ route('pengurus.inventaris.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center shadow-sm border border-indigo-100 group-active:scale-95 transition duration-200">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-emerald-600">Inventaris</span>
                    </a>

                     {{-- Menu 6: Asrama --}}
                     <a href="{{ route('pengurus.asrama.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center shadow-sm border border-purple-100 group-active:scale-95 transition duration-200">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-emerald-600">Asrama</span>
                    </a>

                    {{-- Menu 7: Perpulangan --}}
                    <a href="{{ route('pengurus.perpulangan.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center shadow-sm border border-rose-100 group-active:scale-95 transition duration-200">
                            {{-- Icon Log Out / Pulang --}}
                            <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-emerald-600">Pulang</span>
                    </a>
                </div>
            </div>
        </div>


        {{-- 4. WIDGET AKTIVITAS TERKINI --}}
        <div class="px-5 mt-6 mb-6">
            <h3 class="font-bold text-gray-800 text-base mb-3">Aktivitas Terkini</h3>
            
            <div class="bg-white rounded-2xl shadow-lg shadow-emerald-900/5 border border-white overflow-hidden p-5">
                <div class="py-8 text-center">
                    <div class="inline-flex bg-emerald-50 p-3 rounded-full mb-3 border border-emerald-100">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Semua Terpantau</p>
                    <p class="text-xs text-gray-400 mt-1">Aktivitas terbaru akan muncul di sini.</p>
                </div>
            </div>
        </div>

    </div>

    {{-- 5. BOTTOM NAVIGATION BAR (FIXED) --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-gray-200 pb-safe pt-2 px-6 shadow-[0_-5px_20px_-5px_rgba(0,0,0,0.1)] z-50">
        <div class="flex justify-around items-end pb-3">
            
            {{-- Nav Home --}}
            <a href="{{ route('pengurus.dashboard') }}" class="flex flex-col items-center gap-1 group">
                <div class="p-1.5 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 transition">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <span class="text-[10px] font-bold text-emerald-700">Beranda</span>
            </a>

            {{-- Nav Scan Tengah (Floating Emerald) --}}
            <a href="#" class="-mt-10 group">
                <div class="bg-emerald-600 text-white p-4 rounded-2xl shadow-emerald-300/50 shadow-lg border-[6px] border-gray-50 flex items-center justify-center transform group-active:scale-95 transition hover:bg-emerald-700">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M19 19h-5m-9-6h5a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zM6 21v-2a1 1 0 011-1h10V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16z"></path></svg>
                </div>
            </a>

            {{-- Nav Profile --}}
            <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 group text-gray-400 hover:text-emerald-600 transition">
                <div class="p-1.5">
                    <svg class="w-6 h-6 group-hover:text-emerald-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <span class="text-[10px] font-medium group-hover:font-bold transition">Profil</span>
            </a>

        </div>
    </div>

    {{-- Script Konfirmasi Logout --}}
    @push('scripts')
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Keluar Aplikasi?',
                text: "Anda harus login kembali untuk mengakses akun.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669', // Emerald-600
                cancelButtonColor: '#e5e7eb', // Gray-200
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: '<span class="text-gray-600">Batal</span>',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl', // Sangat rounded ala iOS
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-lg shadow-emerald-200',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form[action="{{ route('logout') }}"]').submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>