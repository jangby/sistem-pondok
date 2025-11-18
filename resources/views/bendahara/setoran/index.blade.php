<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-20 shadow-md rounded-b-[30px] relative overflow-hidden">
            {{-- Dekorasi --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <a href="{{ route('bendahara.dashboard') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h1 class="text-lg font-bold text-white">Setoran Masuk</h1>
                </div>
                
                {{-- Ikon Notifikasi / Filter (Opsional) --}}
                <div class="bg-white/20 backdrop-blur-md p-2 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </div>
            </div>
        </div>

        {{-- 2. KONTEN UTAMA --}}
        <div class="px-5 -mt-14 relative z-20">
            
            {{-- Filter Tab Sederhana --}}
            <div class="bg-white p-1.5 rounded-xl shadow-sm border border-gray-100 flex mb-4">
                <button class="flex-1 py-2 text-xs font-bold rounded-lg bg-emerald-100 text-emerald-700 shadow-sm transition">
                    Semua
                </button>
                <button class="flex-1 py-2 text-xs font-bold rounded-lg text-gray-500 hover:bg-gray-50 transition">
                    Menunggu
                </button>
                <button class="flex-1 py-2 text-xs font-bold rounded-lg text-gray-500 hover:bg-gray-50 transition">
                    Selesai
                </button>
            </div>

            {{-- LIST SETORAN --}}
            <div class="space-y-3">
                @forelse ($setorans as $setoran)
                    <a href="{{ route('bendahara.setoran.show', $setoran->id) }}" class="block bg-white p-4 rounded-2xl shadow-sm border border-gray-100 active:scale-[0.98] transition-transform relative overflow-hidden group">
                        
                        {{-- Indikator Status (Garis Kiri) --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $setoran->dikonfirmasi_pada ? 'bg-emerald-500' : 'bg-yellow-400' }}"></div>

                        <div class="flex justify-between items-start pl-3">
                            <div class="flex items-center gap-3">
                                {{-- Avatar Inisial Admin --}}
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold border {{ $setoran->dikonfirmasi_pada ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-yellow-50 text-yellow-600 border-yellow-100' }}">
                                    {{ substr($setoran->admin->name ?? 'A', 0, 2) }}
                                </div>
                                
                                <div>
                                    <p class="text-xs text-gray-400 font-medium mb-0.5">
                                        {{ $setoran->created_at->format('d M Y • H:i') }}
                                    </p>
                                    <h4 class="text-sm font-bold text-gray-800">
                                        {{ $setoran->admin->name ?? 'Admin Terhapus' }}
                                    </h4>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-500 capitalize mt-1 inline-block">
                                        {{ str_replace('_', ' ', $setoran->kategori_setoran) }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-sm font-black text-gray-800 group-hover:text-emerald-600 transition-colors">
                                    Rp {{ number_format($setoran->total_setoran, 0, ',', '.') }}
                                </p>
                                @if ($setoran->dikonfirmasi_pada)
                                    <div class="flex items-center justify-end gap-1 mt-1 text-emerald-600">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="text-[10px] font-bold">Diterima</span>
                                    </div>
                                @else
                                    <div class="flex items-center justify-end gap-1 mt-1 text-yellow-600 animate-pulse">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="text-[10px] font-bold">Menunggu</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <h3 class="text-gray-800 font-bold">Belum Ada Setoran</h3>
                        <p class="text-gray-500 text-xs mt-1">Belum ada laporan setoran masuk dari admin pondok.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6 pb-8">
                {{ $setorans->links() }}
            </div>

        </div>
    </div>

    {{-- INCLUDE NAVIGASI BAWAH --}}
    @include('layouts.bendahara-nav')

</x-app-layout>