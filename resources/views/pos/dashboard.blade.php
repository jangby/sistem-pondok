<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- Header --}}
        <div class="bg-emerald-600 pt-8 pb-20 px-6 rounded-b-[30px] shadow-lg relative overflow-hidden">
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <p class="text-emerald-100 text-sm font-medium mb-1">Halo, {{ Auth::user()->name }}</p>
                    <h1 class="text-2xl font-bold text-white">{{ $warung->nama_warung }}</h1>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 bg-white/10 rounded-xl hover:bg-red-500/80 transition text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Saldo Card --}}
        <div class="px-6 -mt-12 relative z-20">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-50">
                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Saldo Warung (Bisa Ditarik)</p>
                <h3 class="text-3xl font-black text-emerald-600 tracking-tight">
                    Rp {{ number_format($warung->saldo, 0, ',', '.') }}
                </h3>
                <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
                    <a href="{{ route('pos.payout') }}" class="flex-1 bg-emerald-600 text-white py-2 rounded-lg text-center text-sm font-bold hover:bg-emerald-700">
                        Tarik Dana
                    </a>
                </div>
            </div>
        </div>

        {{-- Statistik Hari Ini --}}
        <div class="px-6 mt-6">
            <h3 class="text-gray-800 font-bold text-lg mb-4">Performa Hari Ini</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-xs text-gray-400">Omset</p>
                    <p class="text-lg font-bold text-gray-800">Rp {{ number_format($omsetHariIni, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <div class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center text-orange-600 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <p class="text-xs text-gray-400">Transaksi</p>
                    <p class="text-lg font-bold text-gray-800">{{ $transaksiHariIni }}x</p>
                </div>
            </div>
        </div>

        {{-- Tombol Mulai Jualan --}}
        <div class="px-6 mt-6">
            <a href="{{ route('pos.index') }}" class="flex items-center justify-between bg-gradient-to-r from-emerald-600 to-teal-600 p-5 rounded-2xl shadow-lg text-white group active:scale-95 transition-transform">
                <div>
                    <h3 class="font-bold text-lg">Buka Kasir</h3>
                    <p class="text-emerald-100 text-sm">Mulai scan kartu santri</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center group-hover:rotate-90 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
            </a>
        </div>

    </div>

    {{-- Include Navigasi Bawah --}}
    @include('layouts.pos-nav')

</x-app-layout>