<div class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-gray-200 pb-safe pt-2 px-6 shadow-[0_-5px_20px_-5px_rgba(0,0,0,0.1)] z-50">
    <div class="flex justify-around items-end pb-3">
        
        {{-- Nav Home --}}
        <a href="{{ route('petugas-lab.dashboard') }}" class="flex flex-col items-center gap-1 group">
            <div class="p-1.5 rounded-xl {{ request()->routeIs('petugas-lab.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:bg-gray-50' }} transition">
                <i class="fas fa-home text-xl"></i>
            </div>
            <span class="text-[10px] font-bold {{ request()->routeIs('petugas-lab.dashboard') ? 'text-blue-700' : 'text-gray-400' }}">Beranda</span>
        </a>

        {{-- Nav Quick Action Tengah (Floating Blue) --}}
        <div class="-mt-10 group relative">
            {{-- PERBAIKAN DI SINI: Menggunakan route 'petugas-lab.komputer.index' --}}
            <a href="{{ route('petugas-lab.komputer.index') }}" class="bg-blue-600 text-white p-4 rounded-2xl shadow-blue-300/50 shadow-lg border-[6px] border-gray-50 flex items-center justify-center transform group-active:scale-95 transition hover:bg-blue-700">
                <i class="fas fa-desktop text-xl"></i>
            </a>
        </div>

        {{-- Nav Profile --}}
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 group text-gray-400 hover:text-blue-600 transition">
            <div class="p-1.5 rounded-xl {{ request()->routeIs('profile.edit') ? 'bg-blue-50 text-blue-600' : 'text-gray-400 hover:bg-gray-50' }}">
                <i class="fas fa-user text-xl"></i>
            </div>
            <span class="text-[10px] font-medium {{ request()->routeIs('profile.edit') ? 'text-blue-700' : 'text-gray-400' }}">Profil</span>
        </a>

    </div>
</div>