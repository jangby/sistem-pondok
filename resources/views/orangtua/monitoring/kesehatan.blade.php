<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        
        {{-- HEADER --}}
        <div class="bg-white px-6 py-4 border-b border-gray-100 sticky top-0 z-30 flex items-center gap-4 shadow-sm">
            <a href="{{ route('orangtua.monitoring.index', $santri->id) }}" class="text-gray-500 hover:text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="font-bold text-lg text-gray-800">Riwayat Kesehatan</h1>
        </div>

        {{-- LIST HISTORY --}}
        <div class="px-5 mt-5 space-y-3">
            @forelse($history as $h)
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col gap-2">
                    
                    {{-- Baris Atas: Tanggal & Status --}}
                    <div class="flex justify-between items-start">
                        <span class="text-xs font-bold text-gray-500">{{ $h->created_at->format('d M Y, H:i') }}</span>
                        
                        @php
                            $statusClass = match($h->status) {
                                'sembuh' => 'bg-green-100 text-green-700',
                                'rujuk_rs' => 'bg-red-100 text-red-700',
                                'dirawat_di_asrama' => 'bg-orange-100 text-orange-700',
                                default => 'bg-yellow-100 text-yellow-700',
                            };
                            $statusLabel = str_replace('_', ' ', $h->status);
                        @endphp
                        
                        <span class="text-[10px] px-2 py-1 rounded font-bold uppercase {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    
                    {{-- Keluhan --}}
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Keluhan</p>
                        <p class="font-bold text-gray-800 text-sm">{{ $h->keluhan }}</p>
                    </div>

                    {{-- Tindakan (Jika ada) --}}
                    @if($h->tindakan)
                        <div class="pt-2 border-t border-gray-50 mt-1">
                            <p class="text-[10px] text-gray-400 uppercase font-bold">Tindakan / Obat</p>
                            <p class="text-xs text-gray-600">{{ $h->tindakan }}</p>
                        </div>
                    @endif

                    {{-- Tanggal Sembuh --}}
                    @if($h->tanggal_sembuh)
                        <div class="flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-[10px] text-green-600 font-medium">Sembuh: {{ $h->tanggal_sembuh->format('d M Y') }}</span>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 text-gray-400 text-sm bg-white rounded-2xl border-2 border-dashed border-gray-200">
                    <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-2 text-emerald-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <p>Alhamdulillah, tidak ada riwayat sakit.</p>
                </div>
            @endforelse

            <div class="mt-4 pb-8">
                {{ $history->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</x-app-layout>