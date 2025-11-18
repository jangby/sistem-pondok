<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            {{-- Dekorasi --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <p class="text-emerald-100 text-sm font-medium mb-1">Selamat Bertugas,</p>
                    <h1 class="text-2xl font-bold text-white truncate max-w-[200px]">
                        {{ Auth::user()->name }}
                    </h1>
                </div>
                
                {{-- Tombol Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" onclick="return confirm('Keluar dari aplikasi?')" class="p-2 bg-white/10 rounded-xl hover:bg-red-500/80 transition text-white backdrop-blur-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- 2. KARTU SALDO KAS (Floating) --}}
        <div class="px-5 -mt-16 relative z-20">
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-50 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-bl-full -mr-5 -mt-5"></div>
                
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1 relative z-10">Saldo Kas Tunai</p>
                <h3 class="text-3xl font-black text-emerald-600 tracking-tight relative z-10">
                    Rp {{ number_format($saldoKas ?? 0, 0, ',', '.') }}
                </h3>
                
                <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                    <a href="{{ route('bendahara.kas.create') }}" class="flex items-center justify-center gap-2 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl text-xs font-bold hover:bg-emerald-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Catat Kas
                    </a>
                    <a href="{{ route('bendahara.setoran.index') }}" class="flex items-center justify-center gap-2 py-2.5 bg-blue-50 text-blue-700 rounded-xl text-xs font-bold hover:bg-blue-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Validasi
                    </a>
                </div>
            </div>
        </div>

        {{-- 3. MENU PINTAS --}}
        <div class="px-5 mt-6">
            <h3 class="text-gray-800 font-bold text-base mb-3">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('bendahara.kas.index') }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 active:scale-95 transition-transform">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Laporan</p>
                        <p class="text-[10px] text-gray-400">Cetak Buku Kas</p>
                    </div>
                </a>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 opacity-50">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Pengaturan</p>
                        <p class="text-[10px] text-gray-400">Segera Hadir</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. SETORAN MASUK (List) --}}
        <div class="px-5 mt-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-gray-800 font-bold text-base">Perlu Konfirmasi</h3>
                <a href="{{ route('bendahara.setoran.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                {{-- Pastikan variabel $pendingSetorans dikirim dari controller --}}
                @if(isset($pendingSetorans) && count($pendingSetorans) > 0)
                    @foreach($pendingSetorans as $setoran)
                        <a href="{{ route('bendahara.setoran.show', $setoran->id) }}" class="block bg-white p-4 rounded-2xl border border-gray-100 shadow-sm active:scale-[0.98] transition-transform relative overflow-hidden">
                            {{-- Indikator Kuning --}}
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-yellow-400"></div>
                            
                            <div class="flex justify-between items-start pl-3">
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-0.5">
                                        {{ $setoran->admin->name ?? 'Admin' }} • {{ $setoran->created_at->format('d M H:i') }}
                                    </p>
                                    <h4 class="text-sm font-bold text-gray-800 capitalize">
                                        {{ str_replace('_', ' ', $setoran->kategori_setoran) }}
                                    </h4>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-bold bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded">Menunggu</span>
                                    <p class="text-sm font-bold text-emerald-600 mt-1">
                                        Rp {{ number_format($setoran->total_setoran, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="text-center py-8 bg-white rounded-2xl border border-dashed border-gray-300">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-50 mb-2">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-gray-500 text-xs font-medium">Semua aman! Tidak ada setoran pending.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- INCLUDE NAVIGASI BAWAH --}}
    @include('layouts.bendahara-nav')

</x-app-layout>