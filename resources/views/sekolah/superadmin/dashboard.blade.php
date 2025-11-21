<x-app-layout>
    {{-- HEADER SECTION --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-800 tracking-tight">
                    Super Admin Dashboard
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Selamat datang, <span class="font-bold text-indigo-600">{{ Auth::user()->name }}</span>. Kelola seluruh unit pendidikan pondok Anda.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-500 shadow-sm">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. STATS GRID (Ringkasan Eksekutif) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Card 1: Total Sekolah --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-indigo-100 relative overflow-hidden group hover:shadow-md transition-all">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider">Unit Sekolah</p>
                            <h3 class="text-4xl font-black text-gray-800 mt-2">{{ $jumlahSekolah }}</h3>
                            <p class="text-xs text-gray-400 mt-1">Jenjang Pendidikan</p>
                        </div>
                        <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Total Admin --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-100 relative overflow-hidden group hover:shadow-md transition-all">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Admin Sekolah</p>
                            <h3 class="text-4xl font-black text-gray-800 mt-2">{{ $jumlahAdminSekolah }}</h3>
                            <p class="text-xs text-gray-400 mt-1">Akun Pengelola</p>
                        </div>
                        <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Total Guru --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-amber-100 relative overflow-hidden group hover:shadow-md transition-all">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                    <div class="relative z-10 flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Total Guru</p>
                            <h3 class="text-4xl font-black text-gray-800 mt-2">{{ $jumlahGuru }}</h3>
                            <p class="text-xs text-gray-400 mt-1">Tenaga Pendidik</p>
                        </div>
                        <div class="p-3 bg-amber-100 text-amber-600 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 2. QUICK ACTIONS & OVERVIEW --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- LEFT: SCHOOL LIST (2/3) --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                            Daftar Unit Sekolah
                        </h3>
                        <a href="{{ route('sekolah.superadmin.sekolah.create') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 hover:underline flex items-center">
                            + Tambah Baru
                        </a>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Sekolah</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jenjang</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Statistik</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse ($sekolahList as $sekolah)
                                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-sm border border-indigo-100">
                                                        {{ substr($sekolah->nama_sekolah, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-gray-800 text-sm">{{ $sekolah->nama_sekolah }}</div>
                                                        <div class="text-xs text-gray-400">ID: {{ $sekolah->id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold border border-gray-200 uppercase">
                                                    {{ $sekolah->tingkat }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center justify-center gap-4 text-xs font-medium text-gray-500">
                                                    <div class="flex flex-col items-center">
                                                        <span class="font-black text-gray-800 text-sm">{{ $sekolah->guru_count }}</span>
                                                        <span>Guru</span>
                                                    </div>
                                                    <div class="w-px h-6 bg-gray-200"></div>
                                                    <div class="flex flex-col items-center">
                                                        <span class="font-black text-gray-800 text-sm">{{ $sekolah->siswa_count }}</span>
                                                        <span>Siswa</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <a href="{{ route('sekolah.superadmin.sekolah.edit', $sekolah->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold text-xs bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition">
                                                    Kelola
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                    </div>
                                                    <p class="text-sm font-medium">Belum ada unit sekolah.</p>
                                                    <a href="{{ route('sekolah.superadmin.sekolah.create') }}" class="mt-2 text-indigo-600 hover:underline text-xs font-bold">Buat Sekolah Baru</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: QUICK ACTIONS & SYSTEM (1/3) --}}
                <div class="space-y-6">
                    
                    {{-- Akses Cepat --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Akses Cepat</h3>
                        <div class="space-y-3">
                            <a href="{{ route('sekolah.superadmin.admin-sekolah.create') }}" class="flex items-center p-3 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50 transition-all group">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-3 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800">Tambah Admin</h4>
                                    <p class="text-xs text-gray-500">Buat akun pengelola baru</p>
                                </div>
                            </a>

                            <a href="{{ route('sekolah.superadmin.guru.index') }}" class="flex items-center p-3 rounded-xl border border-gray-100 hover:border-amber-200 hover:bg-amber-50 transition-all group">
                                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center mr-3 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800">Manajemen Guru</h4>
                                    <p class="text-xs text-gray-500">Kelola data pengajar</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('sekolah.superadmin.tahun-ajaran.index') }}" class="flex items-center p-3 rounded-xl border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50 transition-all group">
                                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center mr-3 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800">Tahun Ajaran</h4>
                                    <p class="text-xs text-gray-500">Atur periode aktif</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- System Info --}}
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-lg p-6 text-white">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Status Sistem</h4>
                                <p class="text-xs text-gray-400">Semua layanan berjalan normal</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between text-gray-300 border-b border-white/10 pb-1">
                                <span>Server</span>
                                <span class="font-mono text-emerald-400">Online</span>
                            </div>
                            <div class="flex justify-between text-gray-300 border-b border-white/10 pb-1">
                                <span>Database</span>
                                <span class="font-mono text-emerald-400">Connected</span>
                            </div>
                             <div class="flex justify-between text-gray-300">
                                <span>Versi App</span>
                                <span class="font-mono text-indigo-300">v1.2.0</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>