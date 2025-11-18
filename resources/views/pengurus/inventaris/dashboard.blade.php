<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- HEADER --}}
        <div class="bg-emerald-600 pt-8 pb-20 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Inventaris</h1>
                    <p class="text-emerald-100 text-xs mt-1 font-medium">Manajemen Aset Pondok</p>
                </div>
                <a href="{{ route('pengurus.dashboard') }}" class="bg-white/20 p-2 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            </div>
        </div>

        {{-- RINGKASAN ASET (FLOATING CARD) --}}
        <div class="px-5 -mt-12 relative z-20">
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-5">
                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2">Estimasi Nilai Aset</p>
                <h2 class="text-3xl font-black text-emerald-600 mb-4">Rp {{ number_format($nilaiAset, 0, ',', '.') }}</h2>
                
                <div class="grid grid-cols-3 gap-2 border-t border-gray-100 pt-4 text-center">
                    <div>
                        <span class="block text-lg font-bold text-gray-800">{{ $totalAset }}</span>
                        <span class="text-[10px] text-gray-400 uppercase">Total Item</span>
                    </div>
                    <div>
                        <span class="block text-lg font-bold text-red-500">{{ $totalRusak }}</span>
                        <span class="text-[10px] text-gray-400 uppercase">Rusak</span>
                    </div>
                    <div>
                        <span class="block text-lg font-bold text-blue-500">{{ $totalPinjam }}</span>
                        <span class="text-[10px] text-gray-400 uppercase">Dipinjam</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- MENU GRID --}}
        <div class="px-5 mt-8">
            <h3 class="font-bold text-gray-800 mb-4">Menu Utama</h3>
            <div class="grid grid-cols-2 gap-4">
                
                {{-- 1. Data Barang --}}
                <a href="{{ route('pengurus.inventaris.barang.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-emerald-200 active:scale-95 transition group">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Data Barang</span>
                </a>

                {{-- 2. Lokasi --}}
                <a href="{{ route('pengurus.inventaris.lokasi.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-emerald-200 active:scale-95 transition group">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-purple-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Lokasi</span>
                </a>

                {{-- 3. Kerusakan --}}
                <a href="{{ route('pengurus.inventaris.kerusakan.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-emerald-200 active:scale-95 transition group">
                    <div class="w-12 h-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-red-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Lapor Rusak</span>
                </a>

                {{-- 4. Peminjaman --}}
                <a href="{{ route('pengurus.inventaris.peminjaman.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-emerald-200 active:scale-95 transition group">
                    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-orange-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="font-bold text-gray-700 text-sm">Peminjaman</span>
                </a>

                {{-- 5. Audit / Rekap --}}
                <a href="{{ route('pengurus.inventaris.rekap.index') }}" class="col-span-2 bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:border-emerald-200 active:scale-95 transition group flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <div>
                        <span class="block font-bold text-gray-700 text-sm">Rekap / Stock Opname</span>
                        <span class="text-xs text-gray-400">Cek fisik & sesuaikan stok</span>
                    </div>
                </a>

            </div>
        </div>
    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>