<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-28">
        
        {{-- Header Mobile --}}
        <div class="bg-red-600 pt-8 pb-20 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            {{-- Dekorasi --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>

            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">UKS Center</h1>
                    <p class="text-red-100 text-xs mt-1 font-medium">Pantau kesehatan santri real-time.</p>
                </div>
                <a href="{{ route('pengurus.dashboard') }}" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </a>
            </div>
        </div>

        {{-- KPI CARDS (Floating) --}}
        <div class="px-5 -mt-12 relative z-20">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 backdrop-blur-xl">
                <div class="flex justify-between items-center divide-x divide-gray-100">
                    {{-- Pasien Aktif --}}
                    <div class="flex-1 text-center px-1">
                        <span class="block text-2xl font-black text-red-600">{{ $pasienAktif }}</span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Pasien Aktif</span>
                    </div>

                    {{-- Baru Hari Ini --}}
                    <div class="flex-1 text-center px-1">
                        <span class="block text-2xl font-black text-gray-800">{{ $sakitHariIni }}</span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Kasus Baru</span>
                    </div>

                    {{-- Sembuh Hari Ini --}}
                    <div class="flex-1 text-center px-1">
                        <span class="block text-2xl font-black text-emerald-600">{{ $sembuhHariIni }}</span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">Sembuh Hr Ini</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- LINK KE HISTORY --}}
        <div class="px-5 mt-6">
            <a href="{{ route('pengurus.uks.history') }}" class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-gray-100 group active:scale-98 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">Riwayat Lengkap</h3>
                        <p class="text-xs text-gray-500">Lihat arsip data sakit & sembuh</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        {{-- HEADER LIST --}}
        <div class="px-6 mt-8 mb-4 flex justify-between items-end">
            <h3 class="font-bold text-gray-800 text-lg">Sedang Sakit</h3>
            <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-lg font-bold animate-pulse">Live Update</span>
        </div>

        {{-- LIST SEDANG SAKIT --}}
        <div class="px-5 space-y-3">
            @forelse($sedangSakit as $data)
                <a href="{{ route('pengurus.uks.show', $data->id) }}" class="block group">
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 group-active:scale-[0.98] transition-all duration-200 relative overflow-hidden">
                        
                        {{-- Indikator Garis Kiri --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $data->status == 'rujuk_rs' ? 'bg-red-500' : 'bg-orange-400' }}"></div>

                        <div class="flex justify-between items-start pl-2">
                            <div>
                                <h3 class="font-bold text-gray-800 text-[15px]">{{ $data->santri->full_name }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $data->santri->kelas->nama_kelas ?? '-' }}</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide 
                                {{ $data->status == 'rujuk_rs' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-orange-50 text-orange-600 border border-orange-100' }}">
                                {{ str_replace('_', ' ', $data->status) }}
                            </span>
                        </div>
                        
                        <div class="mt-3 pt-3 border-t border-gray-50 pl-2 flex gap-3">
                            <div class="flex-1">
                                <p class="text-[9px] text-gray-400 uppercase font-bold">Keluhan</p>
                                <p class="text-sm font-medium text-gray-700 truncate">{{ $data->keluhan }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] text-gray-400 uppercase font-bold">Sejak</p>
                                <p class="text-xs font-bold text-gray-600">{{ $data->created_at->format('d M, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-12 bg-white rounded-3xl border border-dashed border-gray-200">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-green-50 rounded-full mb-3">
                        <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-gray-800 font-bold">Tidak Ada Pasien</h3>
                    <p class="text-gray-400 text-xs mt-1">Saat ini tidak ada santri yang tercatat sakit.</p>
                </div>
            @endforelse

            <div class="mt-4 pb-4">
                {{ $sedangSakit->links('pagination::tailwind') }}
            </div>
        </div>

        {{-- FAB Tambah (SCAN) --}}
        <a href="{{ route('pengurus.uks.scan') }}" class="fixed bottom-24 right-6 bg-red-600 text-white w-14 h-14 rounded-2xl shadow-xl shadow-red-400/40 flex items-center justify-center hover:bg-red-700 active:scale-90 transition-transform z-40 border-[3px] border-white/20">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </a>
    </div>

    @include('layouts.pengurus-nav')
</x-app-layout>