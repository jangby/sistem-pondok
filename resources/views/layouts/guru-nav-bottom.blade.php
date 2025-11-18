{{-- 
|--------------------------------------------------------------------------
| Navigasi Bawah Khusus untuk GURU (Mobile App Style)
|--------------------------------------------------------------------------
--}}
<div class="fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-200">
    <div class="grid h-full max-w-lg grid-cols-5 mx-auto font-medium">
        
        {{-- 1. Tombol Dashboard --}}
        <a href="{{ route('sekolah.guru.dashboard') }}" 
           class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 group
                  {{ request()->routeIs('sekolah.guru.dashboard') ? 'text-blue-600' : 'text-gray-500' }}">
            
            {{-- Icon (Heroicon: Home) --}}
            <svg class="w-5 h-5 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
            </svg>
            <span class="text-xs">Home</span>
        </a>
        
        {{-- 2. Tombol Absen Hadir --}}
        <a href="{{ route('sekolah.guru.absensi.kehadiran.index') }}" 
           class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 group
                  {{ request()->routeIs('sekolah.guru.absensi.kehadiran.*') ? 'text-blue-600' : 'text-gray-500' }}">
            
            {{-- Icon (Heroicon: Check Badge) --}}
            <svg class="w-5 h-5 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.707-9.293a1 1 0 0 0-1.414-1.414L9 10.586 7.707 9.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
            </svg>
            <span class="text-xs">Absen Hadir</span>
        </a>

        {{-- 3. Tombol Jadwal Mengajar --}}
        <a href="{{ route('sekolah.guru.jadwal.index') }}" 
           class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 group
                  {{ request()->routeIs('sekolah.guru.jadwal.*') ? 'text-blue-600' : 'text-gray-500' }}">
            
            {{-- Icon (Heroicon: Calendar Days) --}}
            <svg class="w-5 h-5 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M6 1a1 1 0 0 0-2 0h.01a1 1 0 0 0-2 0H2a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-1.99a1 1 0 0 0-2 0h-.01a1 1 0 0 0-2 0H6Zm11 6H3v8h14V7ZM3 5h14v1H3V5Z"/>
            </svg>
            <span class="text-xs">Mengajar</span>
        </a>

        {{-- 4. Tombol Input Nilai --}}
        <a href="{{ route('sekolah.guru.nilai.index') }}" 
           class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 group
                  {{ request()->routeIs('sekolah.guru.nilai.*') ? 'text-blue-600' : 'text-gray-500' }}">
            
            {{-- Icon (Heroicon: Pencil Square) --}}
            <svg class="w-5 h-5 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="m13.83 7.085 4.033-4.033a1.733 1.733 0 0 0-2.45-2.45L11.4 4.628a1.73 1.73 0 0 0-2.449 0L1.2 12.379a1.732 1.732 0 0 0 0 2.45l4.033 4.033a1.733 1.733 0 0 0 2.45 0l7.747-7.747a1.73 1.73 0 0 0 0-2.45Zm-4.033 8.486-4.033-4.033 4.033-4.033 4.033 4.033-4.033 4.033Z"/>
                <path d="M14 5.397a1 1 0 0 0-1.414 0L9.84 8.143a1 1 0 0 0 0 1.414l.009.009 2.75 2.75.009.009a1 1 0 0 0 1.414 0l2.747-2.747a1 1 0 0 0 0-1.414L14 5.397Z"/>
            </svg>
            <span class="text-xs">Input Nilai</span>
        </a>
        
        {{-- 5. Tombol Izin/Sakit (Kita buat link # dulu) --}}
        <a href="#" 
           class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 group
                  text-gray-500">
            
            {{-- Icon (Heroicon: Document Text) --}}
            <svg class="w-5 h-5 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M18 0H2a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM5 14a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H5Z"/>
            </svg>
            <span class="text-xs">Izin/Sakit</span>
        </a>
    </div>
</div>