<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- Header --}}
        <div class="bg-emerald-600 pt-8 pb-20 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            {{-- Dekorasi --}}
            <div class="absolute top-0 left-0 w-40 h-40 bg-white opacity-10 rounded-full -ml-10 -mt-10 blur-2xl"></div>

            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Pusat Absensi</h1>
                    <p class="text-emerald-100 text-xs mt-1 font-medium">Pilih kategori absensi untuk memulai.</p>
                </div>
                <a href="{{ route('pengurus.dashboard') }}" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </a>
            </div>
        </div>

        {{-- MENU GRID (Floating Up) --}}
        <div class="px-5 -mt-12 relative z-20">
            
            {{-- Menu 1: Gerbang --}}
            <a href="{{ route('pengurus.absensi.gerbang') }}" class="block bg-white p-5 rounded-2xl shadow-md border border-gray-100 mb-4 group active:scale-95 transition">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition">Absensi Gerbang</h3>
                        <p class="text-xs text-gray-500 mt-1">Catat keluar masuk santri & tamu</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>

            {{-- Menu 2: Asrama --}}
            <a href="{{ route('pengurus.absensi.asrama') }}" class="block bg-white p-5 rounded-2xl shadow-md border border-gray-100 mb-4 group active:scale-95 transition">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-800 group-hover:text-purple-600 transition">Absensi Asrama</h3>
                        <p class="text-xs text-gray-500 mt-1">Pengecekan malam & bangun tidur</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>

            {{-- Menu 3: Kegiatan --}}
            <a href="{{ route('pengurus.absensi.kegiatan') }}" class="block bg-white p-5 rounded-2xl shadow-md border border-gray-100 mb-4 group active:scale-95 transition">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-800 group-hover:text-orange-600 transition">Absensi Kegiatan</h3>
                        <p class="text-xs text-gray-500 mt-1">Sekolah, Ekstrakurikuler, Kajian</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>

            {{-- Menu 4: Berjamaah --}}
            <a href="{{ route('pengurus.absensi.jamaah') }}" class="block bg-white p-5 rounded-2xl shadow-md border border-gray-100 mb-4 group active:scale-95 transition">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-800 group-hover:text-emerald-600 transition">Absensi Berjamaah</h3>
                        <p class="text-xs text-gray-500 mt-1">Sholat 5 Waktu di Masjid</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>

            {{-- Menu 5: Kontrol Kehadiran --}}
            <a href="{{ route('pengurus.absensi.kontrol') }}" class="block bg-white p-5 rounded-2xl shadow-md border border-gray-100 mb-4 group active:scale-95 transition">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-800 group-hover:text-red-600 transition">Kontrol Kehadiran</h3>
                        <p class="text-xs text-gray-500 mt-1">Rekapitulasi Alfa, Izin & Sakit</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>

        </div>
    </div>

    @include('layouts.pengurus-nav')
</x-app-layout>