<x-app-layout hide-nav>
    {{-- Header default dikosongkan --}}
    <x-slot name="header"></x-slot>

    {{-- Container Utama --}}
    <div class="min-h-screen bg-gray-50 pb-24" x-data="{ nominal: '' }">
        
        {{-- 1. HEADER --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-20 shadow-md rounded-b-[30px] relative overflow-hidden">
            {{-- Dekorasi --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('pos.dashboard') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Tarik Dana</h1>
            </div>
        </div>

        <div class="px-5 -mt-14 relative z-20">
            
            {{-- 2. INFO SALDO --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 mb-6 text-center relative overflow-hidden">
                {{-- Hiasan --}}
                <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-50 rounded-bl-full -mr-2 -mt-2"></div>

                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1 relative z-10">Saldo Dapat Ditarik</p>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight relative z-10">
                    Rp {{ number_format($warung->saldo, 0, ',', '.') }}
                </h3>
                <p class="text-[10px] text-gray-400 mt-2">Minimal penarikan Rp 10.000</p>
            </div>

            {{-- 3. FORM PENGAJUAN --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
                <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Buat Pengajuan Baru
                </h3>
                
                <form method="POST" action="{{ route('pos.payout.store') }}">
                    @csrf
                    
                    {{-- Input Nominal --}}
                    <div class="mb-4">
                        <label class="block text-xs text-gray-500 mb-1 font-medium">Nominal Penarikan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400 font-bold">Rp</span>
                            <input type="number" 
                                   name="nominal" 
                                   x-model="nominal"
                                   class="block w-full pl-10 pr-4 py-3 border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 font-bold text-lg text-gray-800 placeholder-gray-300" 
                                   placeholder="0" 
                                   min="10000"
                                   max="{{ $warung->saldo }}" 
                                   required>
                        </div>
                        {{-- Pesan Error Client Side --}}
                        <p x-show="nominal > {{ $warung->saldo }}" class="text-xs text-red-500 mt-1 font-medium animate-pulse" style="display: none;">
                            Nominal melebihi saldo tersedia!
                        </p>
                    </div>

                    {{-- Input Keterangan --}}
                    <div class="mb-5">
                        <label class="block text-xs text-gray-500 mb-1 font-medium">Tujuan Transfer / Catatan</label>
                        <textarea name="keterangan" class="block w-full border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm" rows="2" placeholder="Contoh: Transfer ke BCA 12345678 a.n Budi" required></textarea>
                    </div>

                    <button type="submit" 
                            :disabled="!nominal || nominal > {{ $warung->saldo }} || nominal < 10000"
                            class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-xl shadow-md shadow-emerald-200 hover:bg-emerald-700 transition active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        Ajukan Sekarang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>
                </form>
            </div>

            <h3 class="text-gray-800 font-bold text-base mb-3 pl-1">Riwayat Pengajuan</h3>
            <div class="space-y-3">
                @forelse($riwayatPenarikan as $payout)
                    {{-- Ubah div menjadi link (a) menuju detail --}}
                    <a href="{{ route('pos.payout.show', $payout->id) }}" class="block bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex justify-between items-center active:scale-95 transition-transform group">
                        <div>
                            <p class="text-sm font-bold text-gray-800 group-hover:text-emerald-600 transition-colors">
                                Rp {{ number_format($payout->nominal, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] text-gray-400">{{ $payout->created_at->format('d M Y • H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($payout->status == 'pending')
                                <span class="px-2.5 py-1 bg-yellow-50 text-yellow-700 text-[10px] font-bold rounded-lg border border-yellow-100 flex items-center gap-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></div>
                                    PROSES
                                </span>
                            @elseif($payout->status == 'approved')
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-lg border border-emerald-100">
                                    CAIR
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-red-50 text-red-700 text-[10px] font-bold rounded-lg border border-red-100">
                                    TOLAK
                                </span>
                            @endif
                            {{-- Ikon panah kecil penanda bisa diklik --}}
                            <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-10 bg-white rounded-xl border border-dashed border-gray-300">
                        <p class="text-gray-400 text-xs">Belum ada riwayat penarikan.</p>
                    </div>
                @endforelse
                
                <div class="pt-2">
                    {{ $riwayatPenarikan->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- Include Menu Navigasi Bawah --}}
    @include('layouts.pos-nav')

    {{-- SCRIPT NOTIFIKASI SWEETALERT --}}
    @push('scripts')
    <script>
        // Cek jika ada session success
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                confirmButtonColor: '#059669',
                customClass: { popup: 'rounded-2xl' }
            });
        @endif

        // Cek jika ada session error
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session("error") }}',
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'rounded-2xl' }
            });
        @endif

        // Cek validasi error
        @if ($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Periksa Input',
                html: '<ul class="text-left text-sm">@foreach ($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#f59e0b',
                customClass: { popup: 'rounded-2xl' }
            });
        @endif
    </script>
    @endpush

</x-app-layout>