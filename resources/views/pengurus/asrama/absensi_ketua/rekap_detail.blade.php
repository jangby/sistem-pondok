<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        
        {{-- Header --}}
        <div class="bg-white px-6 py-4 border-b border-gray-100 sticky top-0 z-30 flex items-center gap-4 shadow-sm">
            <a href="{{ route('pengurus.asrama.ketua.rekap', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="text-gray-500 hover:text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="font-bold text-lg text-gray-800 leading-tight">{{ $santri->full_name }}</h1>
                <p class="text-[10px] text-gray-400 uppercase tracking-wider">{{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            </div>
        </div>

        <div class="p-5">
            
            {{-- Statistik Grid --}}
            <div class="grid grid-cols-4 gap-2 mb-6 text-center">
                <div class="bg-green-100 p-3 rounded-xl border border-green-200">
                    <span class="block text-xl font-black text-green-700">{{ $stats['hadir'] }}</span>
                    <span class="text-[9px] text-green-600 font-bold uppercase">Hadir</span>
                </div>
                <div class="bg-yellow-100 p-3 rounded-xl border border-yellow-200">
                    <span class="block text-xl font-black text-yellow-700">{{ $stats['sakit'] }}</span>
                    <span class="text-[9px] text-yellow-600 font-bold uppercase">Sakit</span>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl border border-blue-200">
                    <span class="block text-xl font-black text-blue-700">{{ $stats['izin'] }}</span>
                    <span class="text-[9px] text-blue-600 font-bold uppercase">Izin</span>
                </div>
                <div class="bg-red-100 p-3 rounded-xl border border-red-200">
                    <span class="block text-xl font-black text-red-700">{{ $stats['alpha'] }}</span>
                    <span class="text-[9px] text-red-600 font-bold uppercase">Alpha</span>
                </div>
            </div>

            {{-- Timeline Harian --}}
            <div class="space-y-2 bg-white p-4 rounded-3xl shadow-sm border border-gray-100">
                @foreach($history as $date => $status)
                    <div class="flex justify-between items-center p-3 border-b border-gray-50 last:border-0">
                        <span class="text-sm font-bold text-gray-700">{{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMM') }}</span>
                        
                        @if($status == 'H')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-xs font-bold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                HADIR
                            </span>
                        @elseif($status == 'S')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-lg text-xs font-bold">SAKIT</span>
                        @elseif($status == 'I')
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-xs font-bold">IZIN</span>
                        @elseif($status == 'A')
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-xs font-bold">ALPHA</span>
                        @else
                            <span class="text-gray-300 text-xs font-bold">-</span>
                        @endif
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>