<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            {{-- Dekorasi --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('pos.payout') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Detail Penarikan</h1>
            </div>
        </div>

        {{-- 2. KARTU STATUS (Melayang) --}}
        <div class="px-5 -mt-16 relative z-20">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden text-center p-6">
                
                <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-2">Status Pengajuan</p>
                
                @if($payout->status == 'approved')
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3 animate-bounce">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-emerald-600">Berhasil Cair</h2>
                    <p class="text-xs text-gray-500 mt-1">Dana telah dikirim oleh Admin.</p>
                @elseif($payout->status == 'rejected')
                    <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-red-600">Ditolak</h2>
                    <p class="text-xs text-gray-500 mt-1">Pengajuan Anda tidak disetujui.</p>
                @else
                    <div class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-3 animate-pulse">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-yellow-600">Menunggu Proses</h2>
                    <p class="text-xs text-gray-500 mt-1">Admin sedang meninjau permintaan ini.</p>
                @endif

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Nominal</p>
                    <p class="text-3xl font-black text-gray-900 tracking-tight">
                        Rp {{ number_format($payout->nominal, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- 3. RINCIAN & BUKTI --}}
        <div class="px-5 mt-6 space-y-6">
            
            {{-- Timeline Info --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Rincian Waktu</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Diajukan Pada</span>
                        <span class="font-medium text-gray-900">{{ $payout->created_at->format('d M Y • H:i') }}</span>
                    </div>
                    @if($payout->approved_at)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Diproses Pada</span>
                        <span class="font-medium text-gray-900">{{ $payout->approved_at->format('d M Y • H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Catatan --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Catatan</h3>
                
                <div class="mb-4">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Dari Anda (Warung)</p>
                    <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-xl border border-gray-100">
                        {{ $payout->keterangan ?? '-' }}
                    </p>
                </div>

                @if($payout->catatan_admin)
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Dari Admin Uang Jajan</p>
                    <p class="text-sm text-gray-700 bg-emerald-50 p-3 rounded-xl border border-emerald-100">
                        {{ $payout->catatan_admin }}
                    </p>
                </div>
                @endif
            </div>

            {{-- BUKTI TRANSFER --}}
            @if($payout->status == 'approved' && $payout->bukti_transfer)
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 mb-4">Bukti Transfer / Nota</h3>
                    <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-50">
                        {{-- 
                            Karena path di DB sekarang "uploads/bukti-payout/...", 
                            kita cukup tambahkan "storage/" di depannya.
                        --}}
                        <img src="{{ asset('storage/' . $payout->bukti_transfer) }}" 
                             alt="Bukti Transfer" 
                             class="w-full h-auto object-contain max-h-96">
                    </div>
                    <div class="mt-3 text-center">
                        <a href="{{ asset('storage/' . $payout->bukti_transfer) }}" target="_blank" class="text-emerald-600 text-xs font-bold hover:underline inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Lihat Gambar Penuh
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

    @include('layouts.pos-nav')
</x-app-layout>