<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-28">
        
        {{-- HEADER --}}
        <div class="bg-blue-600 pt-8 pb-20 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold text-white">Absensi Gerbang</h1>
                <a href="{{ route('pengurus.absensi.index') }}" class="text-white/80 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></a>
            </div>
            
            {{-- Info Status --}}
            <div class="grid grid-cols-2 gap-3 text-center">
                <div class="bg-white/20 backdrop-blur-md rounded-2xl p-3 border border-white/10">
                    <span class="block text-2xl font-black text-white">{{ $totalDiluar }}</span>
                    <span class="text-[10px] text-blue-100 uppercase">Sedang Diluar</span>
                </div>
                <div class="bg-red-500/80 backdrop-blur-md rounded-2xl p-3 border border-white/10">
                    <span class="block text-2xl font-black text-white">{{ $totalTerlambat }}</span>
                    <span class="text-[10px] text-white uppercase">Terlambat</span>
                </div>
            </div>
        </div>

        {{-- SETTINGS BUTTON --}}
        <div class="px-5 -mt-6 relative z-20 mb-4">
            <a href="{{ route('pengurus.absensi.gerbang.settings') }}" class="flex items-center justify-between bg-white p-4 rounded-2xl shadow-md border border-gray-100 active:scale-95 transition">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-50 p-2 rounded-xl text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Pengaturan Gerbang</h3>
                        <p class="text-xs text-gray-500">Set durasi & No WA Satpam</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        {{-- LIST SANTRI DILUAR --}}
        <div class="px-5 space-y-3">
            <h3 class="font-bold text-gray-700 ml-1">Sedang Diluar (Realtime)</h3>
            
            @forelse($sedangDiluar as $log)
                @php
                    // --- LOGIKA FORMAT WAKTU (DIPERBAIKI: PAKSA INTEGER) ---
                    
                    // 1. Hitung selisih menit (paksa jadi integer agar tidak ada koma)
                    $totalMenit = (int) $log->out_time->diffInMinutes(now());
                    
                    // 2. Format Tampilan
                    if ($totalMenit < 60) {
                        // Kurang dari 1 jam -> Tampilkan menit saja
                        $durasiTampil = $totalMenit . 'm';
                    } else {
                        // Lebih dari 1 jam -> Hitung jam dan sisa menit
                        $jam = floor($totalMenit / 60);
                        $sisaMenit = $totalMenit % 60;
                        
                        // Format: "1j 20m" atau "2j" (jika pas)
                        $durasiTampil = $jam . 'j';
                        if ($sisaMenit > 0) {
                            $durasiTampil .= ' ' . $sisaMenit . 'm';
                        }
                    }
                    
                    $isLate = $log->is_late; 
                @endphp

                <div class="bg-white p-4 rounded-2xl border {{ $isLate ? 'border-red-200 bg-red-50/30' : 'border-gray-100' }} shadow-sm flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-xs text-gray-600">
                            {{ substr($log->santri->full_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm">{{ $log->santri->full_name }}</h3>
                            <p class="text-[10px] text-gray-500">Keluar: {{ $log->out_time->format('H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <span class="block text-lg font-black {{ $isLate ? 'text-red-600' : 'text-blue-600' }}">
                            {{ $durasiTampil }}
                        </span>
                        @if($isLate)
                            <span class="text-[9px] bg-red-100 text-red-600 px-2 py-0.5 rounded font-bold">TERLAMBAT</span>
                        @else
                            <span class="text-[9px] text-gray-400">Durasi</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12 opacity-50">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-gray-500 text-sm">Tidak ada santri diluar.</p>
                </div>
            @endforelse
        </div>

    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>