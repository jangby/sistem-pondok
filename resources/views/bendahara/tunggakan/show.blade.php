<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER --}}
        <div class="bg-red-600 px-5 pt-8 pb-20 shadow-md rounded-b-[30px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-red-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('bendahara.tunggakan.index') }}" class="text-white hover:bg-red-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Rincian Tunggakan</h1>
            </div>
        </div>

        {{-- 2. INFO SANTRI --}}
        <div class="px-5 -mt-14 relative z-20 mb-6">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-bold text-xl border-4 border-white shadow-sm mx-auto -mt-10 mb-3">
                    {{ substr($santri->full_name, 0, 2) }}
                </div>
                <h2 class="text-lg font-bold text-gray-900">{{ $santri->full_name }}</h2>
                <p class="text-xs text-gray-500">{{ $santri->kelas->nama_kelas ?? '-' }} • NIS: {{ $santri->nis }}</p>
                
                <div class="mt-4 pt-4 border-t border-gray-50">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Harus Dibayar</p>
                    <p class="text-3xl font-black text-red-600 tracking-tight">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- 3. LIST TAGIHAN BELUM LUNAS --}}
        <div class="px-5 space-y-4">
            <h3 class="text-gray-800 font-bold text-base pl-1">Daftar Tagihan Aktif</h3>

            @forelse ($tagihans as $tagihan)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    {{-- Header Item --}}
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">{{ $tagihan->jenisPembayaran->name }}</h4>
                            @if($tagihan->periode_bulan)
                                <p class="text-[10px] text-gray-500">Periode: {{ \Carbon\Carbon::create(null, $tagihan->periode_bulan, 1)->format('F') }} {{ $tagihan->periode_tahun }}</p>
                            @endif
                        </div>
                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded">Belum Lunas</span>
                    </div>

                    {{-- Rincian Item --}}
                    <div class="p-4 space-y-2">
                        @foreach ($tagihan->tagihanDetails as $item)
                            {{-- Hanya tampilkan item yang masih ada sisa --}}
                            @if($item->sisa_tagihan_item > 0)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">{{ $item->nama_item }}</span>
                                    <span class="font-bold text-gray-900">Rp {{ number_format($item->sisa_tagihan_item, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        @endforeach
                        
                        <div class="pt-2 mt-2 border-t border-dashed border-gray-200 flex justify-between items-center">
                            <span class="text-xs text-gray-400">Jatuh Tempo: {{ \Carbon\Carbon::parse($tagihan->due_date)->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-white rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-400 text-xs">Data tidak sinkron. Total ada, tapi rincian kosong.</p>
                </div>
            @endforelse
        </div>

    </div>

    @include('layouts.bendahara-nav')

</x-app-layout>