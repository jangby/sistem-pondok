<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER (Gaya Mobile) --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('sekolah.guru.dashboard') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h1 class="text-lg font-bold text-white">Riwayat Izin</h1>
                </div>
                
                {{-- Tombol Tambah (Header) --}}
                <a href="{{ route('sekolah.guru.izin.create') }}" class="bg-white/20 backdrop-blur-md text-white p-2 rounded-xl border border-white/30 hover:bg-white/30 transition shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </a>
            </div>
        </div>

        {{-- 2. KONTEN LIST (Card Style) --}}
        <div class="px-5 -mt-16 relative z-20 space-y-4">
            
            @if(session('success'))
                <div class="mb-2 p-4 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl text-xs font-bold flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($izins as $izin)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-4 relative">
                    {{-- Status Badge (Pojok Kanan Atas) --}}
                    <div class="absolute top-4 right-4">
                        @if($izin->status == 'approved')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase tracking-wide">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Disetujui
                            </span>
                        @elseif($izin->status == 'rejected')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700 uppercase tracking-wide">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700 uppercase tracking-wide">
                                <svg class="w-3 h-3 mr-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Pending
                            </span>
                        @endif
                    </div>

                    <div class="flex items-start gap-4">
                        {{-- Ikon Tipe --}}
                        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 
                            {{ $izin->tipe_izin == 'sakit' ? 'bg-red-50 text-red-500' : 'bg-blue-50 text-blue-500' }}">
                            @if($izin->tipe_izin == 'sakit')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>

                        <div class="flex-1 pr-16"> {{-- Padding right agar tidak nabrak badge --}}
                            <h3 class="font-bold text-gray-800 capitalize">{{ $izin->tipe_izin }}</h3>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $izin->tanggal_mulai->format('d M Y') }} 
                                @if($izin->tanggal_mulai != $izin->tanggal_selesai)
                                    - {{ $izin->tanggal_selesai->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-50">
                        <p class="text-xs text-gray-600 italic">"{{ $izin->keterangan_guru }}"</p>
                        
                        @if($izin->keterangan_admin)
                            <div class="mt-2 bg-gray-50 p-2 rounded-lg">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Catatan Admin:</p>
                                <p class="text-xs text-gray-700">{{ $izin->keterangan_admin }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-300 p-10 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Belum ada riwayat pengajuan.</p>
                    <a href="{{ route('sekolah.guru.izin.create') }}" class="inline-block mt-4 text-xs font-bold text-emerald-600 hover:underline">Buat Pengajuan Baru</a>
                </div>
            @endforelse

            <div class="mt-4 pb-8">
                {{ $izins->links('pagination::tailwind') }}
            </div>
        </div>

        {{-- Floating Action Button (Alternatif Tombol Tambah) --}}
        <a href="{{ route('sekolah.guru.izin.create') }}" class="fixed bottom-6 right-6 w-14 h-14 bg-emerald-600 text-white rounded-full shadow-lg shadow-emerald-300 flex items-center justify-center hover:bg-emerald-700 transition active:scale-95 z-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </a>
    </div>
</x-app-layout>