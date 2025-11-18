<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-28">
        
        {{-- Header --}}
        <div class="bg-emerald-600 pt-8 pb-20 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">Perizinan Santri</h1>
                    <p class="text-emerald-100 text-xs mt-1 font-medium">Kelola izin keluar & pulang.</p>
                </div>
                <a href="{{ route('pengurus.dashboard') }}" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="px-5 -mt-12 relative z-20">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 backdrop-blur-xl">
                <div class="flex justify-between items-center divide-x divide-gray-100">
                    <div class="flex-1 text-center px-1">
                        <span class="block text-2xl font-black text-emerald-600">{{ $sedangDiluar }}</span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase">Sedang Diluar</span>
                    </div>
                    <div class="flex-1 text-center px-1">
                        <span class="block text-2xl font-black text-red-500">{{ $terlambat }}</span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase">Terlambat</span>
                    </div>
                    <div class="flex-1 text-center px-1">
                        <span class="block text-2xl font-black text-gray-800">{{ $izinHariIni }}</span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase">Total Hr Ini</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- LINK KE HISTORY (BUTTON BARU) --}}
        <div class="px-5 mt-6">
            <a href="{{ route('pengurus.perizinan.history') }}" class="flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-gray-100 group active:scale-98 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-50 rounded-full flex items-center justify-center text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">Riwayat Kepulangan</h3>
                        <p class="text-xs text-gray-500">Lihat daftar santri yang sudah kembali</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-300 group-hover:text-purple-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        {{-- List Active --}}
        <div class="px-6 mt-8 mb-4 flex justify-between items-end">
            <h3 class="font-bold text-gray-800 text-lg">Sedang Izin</h3>
        </div>

        <div class="px-5 space-y-3">
            @forelse($izins as $izin)
                <a href="{{ route('pengurus.perizinan.show', $izin->id) }}" class="block bg-white p-4 rounded-2xl shadow-sm border border-gray-100 active:scale-[0.98] transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-800 text-[15px]">{{ $izin->santri->full_name }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $izin->santri->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                        
                        @if($izin->tgl_selesai_rencana < now())
                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase bg-red-50 text-red-600 border border-red-100 animate-pulse">Terlambat</span>
                        @else
                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase bg-blue-50 text-blue-600 border border-blue-100">Aktif</span>
                        @endif
                    </div>
                    
                    <div class="mt-3 pt-3 border-t border-gray-50 flex gap-3 items-center">
                        <div class="flex-1">
                            <p class="text-[9px] text-gray-400 uppercase font-bold">Tipe</p>
                            <p class="text-xs font-bold text-gray-700">
                                {{ $izin->jenis_izin == 'keluar_sebentar' ? 'Keluar Sebentar' : ($izin->jenis_izin == 'sakit_pulang' ? 'Sakit (Pulang)' : 'Pulang Bermalam') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] text-gray-400 uppercase font-bold">Wajib Kembali</p>
                            <p class="text-xs font-bold {{ $izin->tgl_selesai_rencana < now() ? 'text-red-600' : 'text-emerald-600' }}">
                                {{ $izin->tgl_selesai_rencana->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-10 bg-white rounded-3xl border border-dashed border-gray-200">
                    <p class="text-gray-400 text-xs">Semua santri ada di pondok.</p>
                </div>
            @endforelse
             <div class="mt-4">
                {{ $izins->links('pagination::tailwind') }}
            </div>
        </div>

        {{-- FAB (Scan Izin) --}}
        <a href="{{ route('pengurus.perizinan.scan') }}" class="fixed bottom-24 right-6 bg-emerald-600 text-white w-14 h-14 rounded-2xl shadow-xl flex items-center justify-center hover:bg-emerald-700 transition z-40 border-[3px] border-white/20">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </a>
    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>