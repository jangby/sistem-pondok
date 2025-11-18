<div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 pb-safe z-50">
    <div class="flex justify-around items-center h-16">
        
        {{-- Beranda --}}
        <a href="{{ route('bendahara.dashboard') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('bendahara.dashboard') ? 'text-emerald-600' : 'text-gray-400 hover:text-emerald-500' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-[9px] font-medium">Beranda</span>
        </a>

        {{-- Tunggakan (NEW) --}}
        <a href="{{ route('bendahara.tunggakan.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('bendahara.tunggakan.*') ? 'text-emerald-600' : 'text-gray-400 hover:text-emerald-500' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-[9px] font-medium">Tunggakan</span>
        </a>

        {{-- Setoran (CENTER) --}}
        <a href="{{ route('bendahara.setoran.index') }}" class="flex flex-col items-center justify-center w-full h-full -mt-6">
            <div class="w-12 h-12 bg-emerald-600 rounded-full shadow-lg flex items-center justify-center text-white border-4 border-gray-50 {{ request()->routeIs('bendahara.setoran.*') ? 'bg-emerald-700' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-[9px] font-medium text-emerald-600 mt-1">Setoran</span>
        </a>

        {{-- Buku Kas --}}
        <a href="{{ route('bendahara.kas.index') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('bendahara.kas.*') ? 'text-emerald-600' : 'text-gray-400 hover:text-emerald-500' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <span class="text-[9px] font-medium">Kas</span>
        </a>

        {{-- Akun --}}
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center justify-center w-full h-full {{ request()->routeIs('profile.edit') ? 'text-emerald-600' : 'text-gray-400 hover:text-emerald-500' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="text-[9px] font-medium">Akun</span>
        </a>

    </div>
</div>