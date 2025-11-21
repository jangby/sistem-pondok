<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pendidikan Pesantren (Madin)') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 1. Welcome Banner --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl mb-6 border border-emerald-100">
                <div class="p-6 text-gray-900 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-emerald-800">Ahlan wa Sahlan, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-600 text-sm mt-1">
                            Selamat datang di Sistem Manajemen Pendidikan Diniyah Pesantren.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <svg class="h-12 w-12 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- 2. Statistik Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-blue-500 p-5">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Santri</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalSantri ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-emerald-500 p-5">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-emerald-100 text-emerald-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Ustadz</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalUstadz ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-amber-500 p-5">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-amber-100 text-amber-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Mustawa/Kelas</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalMustawa ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-purple-500 p-5">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Total Jadwal</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalJadwal ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Quick Menu (Akses Cepat) --}}
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Menu Cepat</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                <a href="{{ route('pendidikan.admin.mustawa.index') }}" class="block p-6 bg-white rounded-xl border border-gray-200 hover:shadow-md hover:border-emerald-300 transition group">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center mb-3 group-hover:bg-emerald-100 transition">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    </div>
                    <h4 class="font-semibold text-gray-800">Data Mustawa</h4>
                    <p class="text-xs text-gray-500 mt-1">Kelola tingkatan kelas diniyah.</p>
                </a>

                <a href="{{ route('pendidikan.admin.mapel.index') }}" class="block p-6 bg-white rounded-xl border border-gray-200 hover:shadow-md hover:border-emerald-300 transition group">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center mb-3 group-hover:bg-emerald-100 transition">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h4 class="font-semibold text-gray-800">Data Kitab</h4>
                    <p class="text-xs text-gray-500 mt-1">Kelola mata pelajaran/kitab.</p>
                </a>

                <a href="{{ route('pendidikan.admin.ustadz.index') }}" class="block p-6 bg-white rounded-xl border border-gray-200 hover:shadow-md hover:border-emerald-300 transition group">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center mb-3 group-hover:bg-emerald-100 transition">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h4 class="font-semibold text-gray-800">Data Ustadz</h4>
                    <p class="text-xs text-gray-500 mt-1">Kelola data pengajar.</p>
                </a>

                <a href="{{ route('pendidikan.admin.jadwal.index') }}" class="block p-6 bg-white rounded-xl border border-gray-200 hover:shadow-md hover:border-emerald-300 transition group">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center mb-3 group-hover:bg-emerald-100 transition">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="font-semibold text-gray-800">Jadwal & Absensi</h4>
                    <p class="text-xs text-gray-500 mt-1">Atur jadwal dan monitoring.</p>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>