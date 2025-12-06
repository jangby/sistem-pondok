<x-app-layout hide-nav>
    {{-- Header default dikosongkan --}}
    <x-slot name="header"></x-slot>

    {{-- Latar belakang (bg-gray-50) --}}
    <div class="min-h-screen bg-gray-50 pb-24 font-sans relative">
        
        {{-- 1. MOBILE HEADER SECTION (BLUE THEME) --}}
        <div class="bg-blue-600 pt-8 pb-24 px-6 rounded-b-[40px] shadow-xl relative overflow-hidden z-10">
            
            {{-- Dekorasi Background Abstrak --}}
            <div class="absolute -top-10 -right-10 w-64 h-64 bg-blue-500 opacity-30 rounded-full z-0 blur-3xl mix-blend-screen"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-indigo-400 opacity-20 rounded-full z-0 blur-2xl"></div>
            
            {{-- Konten Header --}}
            <div class="relative z-10 flex justify-between items-start mt-2">
                <div>
                    <p class="text-blue-100 text-xs font-medium mb-1 tracking-wide uppercase">Control Panel Lab</p>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight truncate max-w-[200px]">
                        {{ explode(' ', Auth::user()->name)[0] }} 👋
                    </h1>
                    <span class="inline-flex mt-2 px-2.5 py-0.5 rounded-full bg-blue-700/50 border border-blue-400/30 text-blue-50 text-[10px] font-bold uppercase tracking-wider shadow-sm backdrop-blur-sm">
                        Petugas Lab
                    </span>
                </div>
                
                {{-- Tombol Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" onclick="confirmLogout()" class="bg-white/10 backdrop-blur-md p-2.5 rounded-xl hover:bg-white/20 transition border border-white/10 shadow-lg active:scale-95">
                        <i class="fas fa-power-off text-white text-sm"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- 2. FLOATING STATS CARDS --}}
        <div class="px-5 -mt-16 relative z-20">
            <div class="bg-white rounded-2xl shadow-xl shadow-blue-900/10 border border-white/50 p-4">
                <div class="flex justify-between items-center divide-x divide-gray-100">
                    {{-- Total PC --}}
                    <div class="flex-1 text-center px-1">
                        <span class="block text-xl font-black text-gray-800">{{ $totalKomputer }}</span>
                        <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide">Total PC</span>
                    </div>

                    {{-- Online --}}
                    <div class="flex-1 text-center px-1">
                        <span class="block text-xl font-black text-green-500">{{ $komputerOnline }}</span>
                        <span class="text-[10px] text-green-600/70 font-bold uppercase tracking-wide">Online</span>
                    </div>

                    {{-- Offline --}}
                    <div class="flex-1 text-center px-1">
                        <span class="block text-xl font-black text-red-500">{{ $komputerOffline }}</span>
                        <span class="text-[10px] text-red-600/70 font-bold uppercase tracking-wide">Offline</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. WIDGET MENU UTAMA --}}
        <div class="px-5 mt-6">
            <div class="bg-white rounded-2xl p-5 shadow-lg shadow-blue-900/5 border border-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-800 text-base">Menu Kontrol</h3>
                    <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-1 rounded-md font-bold">Aksi Cepat</span>
                </div>
                
                <div class="grid grid-cols-4 gap-y-6 gap-x-2">
                    
                    {{-- Menu 1: Status PC --}}
                    <a href="{{ route('petugas-lab.komputer.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center shadow-sm border border-blue-100 group-active:scale-95 transition duration-200">
                            <i class="fas fa-desktop text-blue-600 text-lg"></i>
                        </div>
                        <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-blue-600">List PC</span>
                    </a>

                    {{-- Menu 2: Matikan Semua (Form Submit) --}}
                    <form action="{{ route('petugas-lab.shutdown.all') }}" method="POST" class="contents" onsubmit="return confirmShutdownAll(event)">
                        @csrf
                        <button type="submit" class="flex flex-col items-center gap-2 group w-full">
                            <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100 group-active:scale-95 transition duration-200">
                                <i class="fas fa-power-off text-red-500 text-lg"></i>
                            </div>
                            <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-red-600">Off Semua</span>
                        </button>
                    </form>

                    {{-- Menu 3: Ganti Pass --}}
                    <a href="{{ route('petugas-lab.password.form') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-yellow-50 rounded-2xl flex items-center justify-center shadow-sm border border-yellow-100 group-active:scale-95 transition duration-200">
                            <i class="fas fa-key text-yellow-600 text-lg"></i>
                        </div>
                        <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-yellow-600">Password</span>
                    </a>

                    {{-- Menu 4: Refresh --}}
                    <a href="{{ route('petugas-lab.refresh') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center shadow-sm border border-green-100 group-active:scale-95 transition duration-200">
                            <i class="fas fa-sync-alt text-green-600 text-lg"></i>
                        </div>
                        <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-green-600">Refresh</span>
                    </a>

                    {{-- Menu 5: Jadwal --}}
                    <a href="{{ route('petugas-lab.jadwal') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center shadow-sm border border-purple-100 group-active:scale-95 transition duration-200">
                            <i class="fas fa-calendar-alt text-purple-600 text-lg"></i>
                        </div>
                        <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-purple-600">Jadwal</span>
                    </a>

                     {{-- Menu 6: Laporan --}}
                     <a href="{{ route('petugas-lab.laporan') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center shadow-sm border border-indigo-100 group-active:scale-95 transition duration-200">
                            <i class="fas fa-file-alt text-indigo-500 text-lg"></i>
                        </div>
                        <span class="text-[11px] font-medium text-gray-600 text-center leading-tight group-hover:text-indigo-600">Laporan</span>
                    </a>
                </div>
            </div>
        </div>


        {{-- 4. WIDGET AKTIVITAS LIVE --}}
        <div class="px-5 mt-6 mb-6">
            <h3 class="font-bold text-gray-800 text-base mb-3 flex items-center gap-2">
                <span>Monitoring Live</span>
                <span class="animate-pulse w-2 h-2 rounded-full bg-green-500"></span>
            </h3>
            
            <div class="space-y-3">
                @forelse($recentActivities as $pc)
                @php
                    // Cek online (2 menit toleransi)
                    $isOnline = $pc->last_seen >= now()->subMinutes(2);
                @endphp
                <div class="bg-white p-3 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $isOnline ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-lg relative">
                            <i class="fas fa-desktop"></i>
                            @if($isOnline)
                            <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">{{ $pc->pc_name }}</h4>
                            <p class="text-[10px] text-gray-500">
                                {{ $pc->ip_address ?? 'IP: -' }} • 
                                {{ \Carbon\Carbon::parse($pc->last_seen)->diffForHumans(null, true) }}
                            </p>
                        </div>
                    </div>

                    <div class="text-right">
                        @if($pc->pending_command)
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-[10px] rounded-lg font-bold animate-pulse">
                                <i class="fas fa-cog fa-spin mr-1"></i> {{ strtoupper($pc->pending_command) }}
                            </span>
                        @else
                            <span class="px-2 py-1 {{ $isOnline ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-400' }} text-[10px] rounded-lg font-bold">
                                {{ $isOnline ? 'Ready' : 'Offline' }}
                            </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8 bg-white rounded-2xl border border-dashed border-gray-300">
                    <i class="fas fa-ghost text-gray-300 text-2xl mb-2"></i>
                    <p class="text-gray-400 text-xs">Belum ada komputer yang aktif hari ini.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- 5. INCLUDE BOTTOM NAV --}}
    @include('layouts.petugas-lab-nav')

    {{-- Script SweetAlert untuk Konfirmasi --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Konfirmasi Logout
        function confirmLogout() {
            Swal.fire({
                title: 'Keluar Aplikasi?',
                text: "Anda akan logout dari sesi Petugas Lab.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563EB', // Blue-600
                cancelButtonColor: '#E5E7EB', // Gray-200
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: '<span class="text-gray-600">Batal</span>',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-lg shadow-blue-200',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form[action="{{ route('logout') }}"]').submit();
                }
            })
        }

        // Konfirmasi Shutdown All
        function confirmShutdownAll(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Matikan SEMUA PC?',
                text: "Semua PC yang sedang ONLINE akan menerima perintah Shutdown. Aksi ini tidak dapat dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444', // Red-500
                cancelButtonColor: '#E5E7EB',
                confirmButtonText: 'Ya, Matikan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-lg shadow-red-200',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-medium'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>