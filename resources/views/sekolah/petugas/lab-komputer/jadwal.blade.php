<x-app-layout hide-nav>
    <div class="bg-blue-600 p-6 pb-12 rounded-b-3xl">
        <div class="flex items-center text-white mb-4">
            <a href="{{ route('petugas-lab.dashboard') }}" class="mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-xl font-bold">Jadwal Penggunaan</h1>
        </div>
        <div class="flex justify-between items-center text-blue-100 bg-blue-700/50 rounded-lg p-1">
            <button class="p-2 hover:bg-blue-600 rounded"><i class="fas fa-chevron-left"></i></button>
            <span class="font-medium text-sm">{{ now()->translatedFormat('l, d F Y') }}</span>
            <button class="p-2 hover:bg-blue-600 rounded"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>

    <div class="px-4 -mt-8 space-y-4">
        <div class="bg-white p-4 rounded-2xl shadow-md border-l-4 border-blue-500 flex">
            <div class="flex flex-col items-center justify-center pr-4 border-r border-gray-100 mr-4">
                <span class="text-lg font-bold text-gray-800">08:00</span>
                <span class="text-xs text-gray-500">09:30</span>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Kelas 10 RPL A</h3>
                <p class="text-sm text-gray-600">Mapel: Pemrograman Dasar</p>
                <div class="mt-2 flex items-center gap-2">
                    <span class="px-2 py-1 bg-blue-100 text-blue-600 text-[10px] rounded-md font-bold">LAB 1</span>
                    <span class="text-xs text-gray-400"><i class="fas fa-user-tie mr-1"></i> Ust. Ahmad</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-md border-l-4 border-purple-500 flex">
            <div class="flex flex-col items-center justify-center pr-4 border-r border-gray-100 mr-4">
                <span class="text-lg font-bold text-gray-800">10:00</span>
                <span class="text-xs text-gray-500">11:30</span>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Kelas 11 TKJ B</h3>
                <p class="text-sm text-gray-600">Mapel: Jaringan Komputer</p>
                <div class="mt-2 flex items-center gap-2">
                    <span class="px-2 py-1 bg-purple-100 text-purple-600 text-[10px] rounded-md font-bold">LAB 1</span>
                    <span class="text-xs text-gray-400"><i class="fas fa-user-tie mr-1"></i> Ust. Budi</span>
                </div>
            </div>
        </div>
        
        <div class="text-center text-xs text-gray-400 mt-6">
            -- Tidak ada jadwal lagi hari ini --
        </div>
    </div>
</x-app-layout>