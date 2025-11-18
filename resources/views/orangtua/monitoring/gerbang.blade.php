<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        
        {{-- HEADER --}}
        <div class="bg-white px-6 py-4 border-b border-gray-100 sticky top-0 z-30 flex items-center gap-4 shadow-sm">
            <a href="{{ route('orangtua.monitoring.index', $santri->id) }}" class="text-gray-500 hover:text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="font-bold text-lg text-gray-800">Log Gerbang</h1>
        </div>

        {{-- LIST HISTORY --}}
        <div class="px-5 mt-5 space-y-3">
            @forelse($history as $h)
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    
                    {{-- Baris Waktu --}}
                    <div class="flex justify-between items-center mb-3">
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold">
                            {{ $h->out_time->format('d M Y') }}
                        </span>
                        @if($h->is_late)
                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-[10px] font-bold uppercase">Terlambat</span>
                        @else
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-[10px] font-bold uppercase">Tepat Waktu</span>
                        @endif
                    </div>

                    {{-- Timeline Keluar -> Masuk --}}
                    <div class="flex items-center justify-between relative">
                        {{-- Garis Penghubung --}}
                        <div class="absolute top-1/2 left-10 right-10 h-0.5 bg-gray-100 -z-10"></div>

                        {{-- Keluar --}}
                        <div class="text-center bg-white px-2">
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Keluar</p>
                            <p class="text-lg font-black text-gray-800">{{ $h->out_time->format('H:i') }}</p>
                        </div>

                        {{-- Panah --}}
                        <div class="text-gray-300 bg-white px-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>

                        {{-- Masuk --}}
                        <div class="text-center bg-white px-2">
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Masuk</p>
                            @if($h->in_time)
                                <p class="text-lg font-black text-emerald-600">{{ $h->in_time->format('H:i') }}</p>
                            @else
                                <p class="text-xs font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded mt-1">Sedang Diluar</p>
                            @endif
                        </div>
                    </div>

                    {{-- Durasi --}}
                    @if($h->in_time)
                        <div class="mt-3 pt-2 border-t border-gray-50 text-center">
                            <p class="text-xs text-gray-400">
                                Durasi diluar: <span class="font-bold text-gray-600">{{ $h->out_time->diffForHumans($h->in_time, true) }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 text-gray-400 text-sm bg-white rounded-2xl border-2 border-dashed border-gray-200">
                    <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-2 text-orange-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </div>
                    <p>Belum ada aktivitas gerbang.</p>
                </div>
            @endforelse

            <div class="mt-4 pb-8">
                {{ $history->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</x-app-layout>