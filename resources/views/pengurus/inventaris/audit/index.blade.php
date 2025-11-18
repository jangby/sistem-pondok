<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-28" x-data="{ activeTab: 'pending' }">
        
        {{-- HEADER --}}
        <div class="bg-purple-600 pt-8 pb-20 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold text-white">Rekap Stok (Audit)</h1>
                <a href="{{ route('pengurus.inventaris.index') }}" class="text-white/80 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            </div>
            
            {{-- Tab Switcher --}}
            <div class="flex p-1 bg-purple-800/30 rounded-xl backdrop-blur-sm">
                <button @click="activeTab = 'pending'" 
                    :class="activeTab === 'pending' ? 'bg-white text-purple-700 shadow-sm' : 'text-purple-100 hover:text-white'"
                    class="flex-1 py-2 text-xs font-bold rounded-lg transition">
                    Perlu Tindakan
                    @if($pendings->count() > 0)
                        <span class="ml-1 bg-red-500 text-white px-1.5 py-0.5 rounded-full text-[9px]">{{ $pendings->count() }}</span>
                    @endif
                </button>
                <button @click="activeTab = 'history'" 
                    :class="activeTab === 'history' ? 'bg-white text-purple-700 shadow-sm' : 'text-purple-100 hover:text-white'"
                    class="flex-1 py-2 text-xs font-bold rounded-lg transition">
                    Riwayat Laporan
                </button>
            </div>
        </div>

        <div class="px-5 -mt-6 relative z-20 space-y-4">
            
            {{-- TAB 1: PENDING --}}
            <div x-show="activeTab === 'pending'" class="space-y-3">
                
                {{-- Tombol Scan --}}
                <a href="{{ route('pengurus.inventaris.rekap.scan') }}" class="block w-full bg-white border-2 border-dashed border-purple-300 text-purple-600 py-4 rounded-2xl font-bold text-sm text-center hover:bg-purple-50 active:scale-95 transition mb-4">
                    + Mulai Stock Opname Baru
                </a>

                @forelse($pendings as $a)
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $a->barang->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $a->audit_date->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="text-[10px] px-2 py-1 rounded font-bold uppercase bg-orange-100 text-orange-600">
                                Butuh Aksi
                            </span>
                        </div>

                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded-lg mb-3 text-xs">
                            <div class="text-center">
                                <span class="block text-gray-400 uppercase">Sistem</span>
                                <span class="font-bold text-gray-700 text-sm">{{ $a->expected_qty }}</span>
                            </div>
                            <div class="text-gray-300">→</div>
                            <div class="text-center">
                                <span class="block text-gray-400 uppercase">Fisik</span>
                                <span class="font-bold text-blue-600 text-sm">{{ $a->actual_qty }}</span>
                            </div>
                            <div class="text-gray-300">=</div>
                            <div class="text-center">
                                <span class="block text-gray-400 uppercase">Selisih</span>
                                <span class="font-bold text-sm {{ $a->difference < 0 ? 'text-red-500' : ($a->difference > 0 ? 'text-green-500' : 'text-gray-500') }}">
                                    {{ $a->difference > 0 ? '+' : '' }}{{ $a->difference }}
                                </span>
                            </div>
                        </div>

                        @if($a->difference != 0)
                            <form action="{{ route('pengurus.inventaris.rekap.reconcile', $a->id) }}" method="POST" onsubmit="return confirm('Stok sistem akan disesuaikan dengan fisik. Data ini akan masuk ke riwayat laporan. Lanjutkan?')">
                                @csrf
                                <button class="w-full bg-red-50 text-red-600 py-2.5 rounded-xl text-xs font-bold hover:bg-red-100 transition flex justify-center items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Rekonsiliasi (Sesuaikan Stok)
                                </button>
                            </form>
                        @else
                            <form action="{{ route('pengurus.inventaris.rekap.reconcile', $a->id) }}" method="POST">
                                @csrf
                                <button class="w-full bg-green-50 text-green-600 py-2.5 rounded-xl text-xs font-bold hover:bg-green-100 transition">
                                    Selesai (Cocok)
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400 text-sm bg-white rounded-2xl border border-dashed border-gray-200">
                        Tidak ada audit yang tertunda.
                    </div>
                @endforelse
            </div>

            {{-- TAB 2: HISTORY --}}
            <div x-show="activeTab === 'history'" class="space-y-4" style="display: none;">
                
                {{-- Filter & Print --}}
                <form method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <input type="hidden" name="filter" value="true">
                    <div class="flex gap-2 items-center mb-3">
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-1/2 rounded-xl border-gray-200 text-xs focus:ring-purple-500">
                        <span class="text-gray-400">-</span>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-1/2 rounded-xl border-gray-200 text-xs focus:ring-purple-500">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-purple-600 text-white py-2 rounded-xl text-xs font-bold hover:bg-purple-700 transition">
                            Filter Data
                        </button>
                        <button type="submit" formaction="{{ route('pengurus.inventaris.rekap.pdf') }}" formtarget="_blank" class="bg-red-100 text-red-600 px-3 py-2 rounded-xl text-xs font-bold hover:bg-red-200 transition flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            PDF
                        </button>
                    </div>
                </form>

                {{-- List History --}}
                @forelse($history as $h)
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm">{{ $h->barang->name }}</h3>
                            <p class="text-[10px] text-gray-400">{{ $h->audit_date->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                             @if($h->difference < 0)
                                <span class="text-red-500 font-bold text-sm">Hilang {{ abs($h->difference) }}</span>
                             @elseif($h->difference > 0)
                                <span class="text-green-500 font-bold text-sm">Lebih {{ $h->difference }}</span>
                             @else
                                <span class="text-green-600 font-bold text-sm">Cocok</span>
                             @endif
                             <p class="text-[9px] text-gray-400">Stok disesuaikan</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400 text-xs bg-white rounded-2xl border border-dashed border-gray-200">
                        Belum ada riwayat rekonsiliasi pada periode ini.
                    </div>
                @endforelse

                <div class="mt-4">{{ $history->links('pagination::tailwind') }}</div>
            </div>

        </div>
    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>