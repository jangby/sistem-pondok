<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    {{-- Container Mobile Background --}}
    <div class="min-h-screen bg-gray-50 pb-20 font-sans">
        
        {{-- 1. HEADER SECTION (Hijau Emerald) --}}
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 pt-10 pb-24 px-6 rounded-b-[35px] shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-8 -mt-8 blur-2xl"></div>
            
            <div class="relative z-10 flex justify-between items-center">
                <div class="text-white">
                    <p class="text-emerald-100 text-xs font-medium uppercase tracking-wider mb-1">Area Petugas</p>
                    <h1 class="text-2xl font-bold truncate max-w-[200px]">
                        {{ Auth::user()->name }}
                    </h1>
                </div>
                
                {{-- Tombol Logout Minimalis --}}
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="button" onclick="confirmLogout()" class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-white hover:bg-red-500/80 transition-all shadow-lg border border-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- 2. FLOATING CARD STATISTIK --}}
        <div class="px-6 -mt-16 relative z-20">
            <div class="bg-white rounded-2xl shadow-lg p-5 border border-gray-100">
                <div class="flex justify-between items-center text-center divide-x divide-gray-100">
                    <div class="px-2 w-1/3">
                        <span class="block text-2xl font-bold text-orange-500">{{ $peminjamanAktif ?? 0 }}</span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Dipinjam</span>
                    </div>
                    <div class="px-2 w-1/3">
                        <span class="block text-2xl font-bold text-emerald-600">{{ $kunjunganHariIni ?? 0 }}</span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Tamu Harian</span>
                    </div>
                    <div class="px-2 w-1/3">
                        <span class="block text-2xl font-bold text-blue-600">{{ $totalBuku ?? 0 }}</span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Total Buku</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. GRID MENU NAVIGASI (Link Sudah Diperbaiki) --}}
        <div class="px-6 mt-8">
            <h3 class="text-gray-800 font-bold text-lg mb-4 flex items-center gap-2">
                <div class="w-1 h-6 bg-emerald-500 rounded-full"></div>
                Menu Layanan
            </h3>
            
            <div class="grid grid-cols-3 gap-4">
                {{-- Menu: Sirkulasi (Peminjaman & Pengembalian) --}}
                {{-- PERBAIKAN: Mengarah ke sekolah.petugas.sirkulasi.index --}}
                <a href="{{ route('sekolah.petugas.sirkulasi.index') }}" class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-emerald-600 group-active:scale-95 transition-all">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium">Sirkulasi</span>
                </a>

                {{-- Menu: Data Buku (Katalog) --}}
                {{-- PERBAIKAN: Mengarah ke sekolah.petugas.buku.index --}}
                <a href="{{ route('sekolah.petugas.buku.index') }}" class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-blue-500 group-active:scale-95 transition-all">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium">Katalog</span>
                </a>

                {{-- Menu: Buku Tamu --}}
                {{-- PERBAIKAN: Mengarah ke sekolah.petugas.kunjungan.index --}}
                <a href="{{ route('sekolah.petugas.kunjungan.index') }}" class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-purple-500 group-active:scale-95 transition-all">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium">Buku Tamu</span>
                </a>

                {{-- Menu: Audit / Stock Opname --}}
                {{-- PERBAIKAN: Mengarah ke sekolah.petugas.audit.index (Audit Stok) --}}
                <a href="{{ route('sekolah.petugas.audit.index') }}" class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-teal-600 group-active:scale-95 transition-all">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 8V4h4V8H6zM6 20v-4h4v4H6zM16 8V4h4V8h-4zM6 12h12"></path></svg>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium">Cek Stok</span>
                </a>
                
                {{-- Menu: Akun --}}
                <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-gray-500 group-active:scale-95 transition-all">
                       <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="text-[11px] text-gray-600 font-medium">Akun Saya</span>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmLogout() {
            if(confirm("Apakah Anda yakin ingin keluar?")) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>
    @endpush
</x-app-layout>