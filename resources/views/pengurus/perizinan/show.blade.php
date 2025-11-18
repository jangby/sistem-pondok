<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-28 relative">
        
        {{-- Header Background (Emerald) --}}
        <div class="h-64 bg-emerald-600 rounded-b-[40px] relative overflow-hidden">
            {{-- Dekorasi --}}
            <div class="absolute top-0 left-0 w-40 h-40 bg-white opacity-10 rounded-full -ml-10 -mt-10 blur-2xl"></div>
            
            {{-- Navbar --}}
            <div class="flex justify-between items-center p-6 text-white relative z-10">
                <a href="{{ route('pengurus.perizinan.index') }}" class="bg-white/20 p-2 rounded-xl backdrop-blur-md hover:bg-white/30 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="font-bold text-lg">Detail Izin</h1>
                <div class="w-10"></div> {{-- Spacer agar judul di tengah --}}
            </div>
        </div>

        {{-- Card Utama (Floating Profile) --}}
        <div class="px-6 -mt-36 relative z-20">
            <div class="bg-white rounded-3xl shadow-xl p-6 text-center border border-gray-100">
                {{-- Avatar --}}
                <div class="w-20 h-20 bg-emerald-50 rounded-full mx-auto mb-3 flex items-center justify-center text-2xl font-bold text-emerald-600 ring-4 ring-white shadow-md">
                    {{ substr($izin->santri->full_name, 0, 1) }}
                </div>
                
                <h2 class="text-lg font-bold text-gray-800 leading-tight">{{ $izin->santri->full_name }}</h2>
                <p class="text-gray-500 text-xs mt-1">{{ $izin->santri->kelas->nama_kelas ?? '-' }} • {{ $izin->santri->nis }}</p>

                <div class="mt-4 flex justify-center gap-2">
                    {{-- Badge Jenis Izin --}}
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 uppercase tracking-wider">
                        {{ $izin->jenis_izin == 'keluar_sebentar' ? 'Keluar Sebentar' : ($izin->jenis_izin == 'sakit_pulang' ? 'Sakit (Pulang)' : 'Pulang Bermalam') }}
                    </span>

                    {{-- Badge Status --}}
                    @if($izin->status == 'kembali')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 uppercase tracking-wider">Sudah Kembali</span>
                    @elseif($izin->tgl_selesai_rencana < now())
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 uppercase tracking-wider animate-pulse">Terlambat</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 uppercase tracking-wider">Aktif (Diluar)</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detail Waktu & Alasan --}}
        <div class="px-6 mt-6 space-y-4">
            
            {{-- Grid Waktu --}}
            <div class="grid grid-cols-2 gap-4">
                {{-- Waktu Keluar --}}
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Waktu Keluar</p>
                    <p class="font-bold text-gray-800 text-sm">{{ $izin->tgl_mulai->format('d M, H:i') }}</p>
                    <p class="text-[10px] text-gray-500">{{ $izin->tgl_mulai->diffForHumans() }}</p>
                </div>

                {{-- Wajib Kembali --}}
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
                    @if($izin->status != 'kembali' && $izin->tgl_selesai_rencana < now())
                        <div class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full animate-ping m-2"></div>
                    @endif
                    
                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Wajib Kembali</p>
                    <p class="font-bold text-gray-800 text-sm">{{ $izin->tgl_selesai_rencana->format('d M, H:i') }}</p>
                    
                    @if($izin->status == 'kembali')
                        <p class="text-[10px] text-green-600 font-bold">Selesai</p>
                    @elseif($izin->tgl_selesai_rencana < now())
                        <p class="text-[10px] text-red-600 font-bold">Lewat {{ $izin->tgl_selesai_rencana->diffForHumans(null, true) }}</p>
                    @else
                        <p class="text-[10px] text-blue-600 font-bold">Sisa {{ $izin->tgl_selesai_rencana->diffForHumans(null, true) }}</p>
                    @endif
                </div>
            </div>

            {{-- Alasan --}}
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Alasan Izin</h3>
                <p class="text-gray-800 text-sm leading-relaxed font-medium">
                    "{{ $izin->alasan }}"
                </p>
                
                <div class="mt-4 pt-3 border-t border-gray-50 flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[9px] font-bold text-gray-500">
                        {{ substr($izin->penyetuju->name ?? 'A', 0, 1) }}
                    </div>
                    <p class="text-xs text-gray-400">Disetujui oleh: <span class="text-gray-600">{{ $izin->penyetuju->name ?? 'Admin' }}</span></p>
                </div>
            </div>

            {{-- Info Pengembalian (Jika Sudah Kembali) --}}
            @if($izin->status == 'kembali')
                <div class="bg-green-50 p-4 rounded-2xl border border-green-100 flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-green-600 font-bold uppercase">Telah Kembali</p>
                        <p class="text-sm font-bold text-green-800">{{ $izin->tgl_kembali_realisasi->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            @endif

        </div>

        {{-- FOOTER ACTION (Hanya jika belum kembali) --}}
        @if($izin->status == 'disetujui' || $izin->status == 'terlambat')
            <div class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-md border-t border-gray-200 p-4 pb-8 z-30">
                <div class="max-w-7xl mx-auto">
                    <a href="{{ route('pengurus.perizinan.edit', $izin->id) }}" class="flex items-center justify-center w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-emerald-200 active:scale-95 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Konfirmasi Kepulangan
                    </a>
                </div>
            </div>
        @endif

    </div>
</x-app-layout>