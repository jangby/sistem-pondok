<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    {{-- Tambahkan 'showFilterModal: false' di x-data utama --}}
    <div class="min-h-screen bg-gray-50 pb-24 h-screen flex flex-col overflow-hidden" 
         x-data="{ showSettingsModal: false, showFilterModal: false, activeDompet: null }">
        
        {{-- 1. FIXED HEADER & CARDS AREA --}}
        <div class="flex-none z-30 relative">
            <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

                <div class="relative z-10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('orangtua.dashboard') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <h1 class="text-lg font-bold text-white">Dompet Santri</h1>
                    </div>
                </div>
            </div>

            {{-- WALLET CARDS SLIDER --}}
            <div class="px-5 -mt-16 relative z-40 pb-2 overflow-x-auto no-scrollbar flex gap-4 snap-x snap-mandatory">
                @forelse ($santris as $santri)
                    <div class="min-w-[100%] snap-center">
                        <div class="group relative">
                            @if($santri->dompet)
                                <div class="bg-gradient-to-br from-emerald-500 to-teal-700 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden transition-transform transform active:scale-[0.99]">
                                    <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl -mr-10 -mt-10"></div>
                                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>

                                    <div class="relative z-10">
                                        <div class="flex justify-between items-start mb-6">
                                            <div>
                                                <p class="text-emerald-100 text-[10px] uppercase tracking-widest font-medium mb-1">Saldo Aktif</p>
                                                <h3 class="text-3xl font-bold tracking-tight">
                                                    Rp {{ number_format($santri->dompet->saldo, 0, ',', '.') }}
                                                </h3>
                                            </div>
                                            <button @click="showSettingsModal = true; activeDompet = { 
        id: @js($santri->dompet->id), 
        nama: @js($santri->full_name), 
        limit: @js($santri->dompet->daily_spending_limit), 
        status: @js($santri->dompet->status),
        url: @js(route('orangtua.dompet.update', $santri->dompet->id))
    }"
    class="p-2 bg-white/20 rounded-lg hover:bg-white/30 backdrop-blur-sm transition border border-white/10">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            </button>
                                        </div>

                                        <div class="flex justify-between items-end">
                                            <div>
                                                <p class="font-bold text-lg tracking-wide truncate max-w-[180px]">{{ $santri->full_name }}</p>
                                                <p class="text-xs text-emerald-200 font-mono mt-0.5">
                                                    Limit: {{ $santri->dompet->daily_spending_limit ? 'Rp '.number_format($santri->dompet->daily_spending_limit,0,',','.') : '∞' }}
                                                </p>
                                            </div>
                                            
                                            <div class="text-right">
                                                @if($santri->dompet->status == 'active')
                                                    <span class="px-2 py-1 rounded-lg bg-emerald-400/20 backdrop-blur-md text-[10px] font-bold border border-emerald-300/30 text-emerald-50 flex items-center gap-1.5">
                                                        <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span> AKTIF
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 rounded-lg bg-red-500/20 backdrop-blur-md text-[10px] font-bold border border-red-500/30 text-red-100 flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-red-500"></div> DIBLOKIR</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4 grid grid-cols-2 gap-3">
                                    <a href="{{ route('orangtua.dompet.topup.create', ['dompet_id' => $santri->dompet->id]) }}" class="flex items-center justify-center gap-2 bg-white border border-emerald-100 p-3 rounded-xl shadow-sm text-emerald-700 font-bold text-sm hover:bg-emerald-50 transition active:scale-95">
                                        <div class="p-1 bg-emerald-100 rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg></div> Isi Saldo
                                    </a>
                                    
                                    {{-- PERBAIKAN: Tombol Filter Membuka Modal Bawah (Bukan Dropdown) --}}
                                    <button @click="showFilterModal = true" 
                                            class="flex items-center justify-center gap-2 bg-white border p-3 rounded-xl shadow-sm font-medium text-sm hover:bg-gray-50 transition active:scale-95 {{ request('filter') ? 'border-emerald-200 text-emerald-600 bg-emerald-50' : 'border-gray-100 text-gray-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg> 
                                        {{ request('filter') ? ucwords(str_replace('_', ' ', request('filter'))) : 'Filter' }}
                                    </button>
                                </div>
                            @else
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-center"><p class="text-gray-500 text-sm mt-1">Dompet belum diaktifkan.</p></div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center w-full py-4 text-gray-500 text-sm">Tidak ada data santri.</div>
                @endforelse
            </div>
        </div>

        {{-- 2. SCROLLABLE TRANSACTION LOG AREA --}}
        <div class="flex-1 overflow-y-auto px-5 pt-4 pb-20 bg-gray-50">
            
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 text-base">Riwayat Transaksi</h3>
                @if(request('filter'))
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full font-bold uppercase">
                        {{ str_replace('_', ' ', request('filter')) }}
                    </span>
                @endif
            </div>

            <div class="space-y-3">
                @php
                    $allTransactions = collect();
                    foreach($santris as $s) {
                        if($s->dompet) {
                            $trx = $s->dompet->transaksiDompets();
                            if(request('filter') == 'hari_ini') $trx->whereDate('created_at', now());
                            elseif(request('filter') == 'minggu_ini') $trx->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                            elseif(request('filter') == 'bulan_ini') $trx->whereMonth('created_at', now()->month);
                            
                            $allTransactions = $allTransactions->merge($trx->limit(20)->get());
                        }
                    }
                    $allTransactions = $allTransactions->sortByDesc('created_at');
                @endphp

                @forelse($allTransactions as $trx)
                    @php
                        $tipe = trim($trx->tipe);
                        $tipePemasukan = ['topup_manual', 'topup_midtrans', 'setor_tunai', 'topup'];
                        $isPemasukan = in_array($tipe, $tipePemasukan);
                        
                        $iconColor = $isPemasukan ? 'bg-emerald-100 text-emerald-600' : 'bg-orange-100 text-orange-600';
                        $textColor = $isPemasukan ? 'text-emerald-600' : 'text-red-500';
                        $tanda = $isPemasukan ? '+' : '-';
                        
                        $labelTipe = match($tipe) {
                            'topup_manual' => 'Isi Saldo (Tunai)',
                            'topup_midtrans' => 'Isi Saldo (Online)',
                            'jajan' => 'Jajan',
                            'tarik_tunai' => 'Tarik Tunai',
                            default => ucwords(str_replace('_', ' ', $tipe)),
                        };
                        $nominalDisplay = number_format(abs($trx->nominal), 0, ',', '.');
                    @endphp

                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between active:scale-[0.99] transition-transform">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $iconColor }}">
                                @if($isPemasukan)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-800 truncate max-w-[160px] capitalize">
                                    {{ $trx->keterangan ?? $labelTipe }}
                                </p>
                                <p class="text-[10px] text-gray-400 mt-0.5">
                                    {{ $trx->created_at->format('d M Y • H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-sm {{ $textColor }}">{{ $tanda }}Rp {{ $nominalDisplay }}</p>
                            @if($trx->tipe == 'jajan')
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $trx->dompet->santri->full_name }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-gray-400 text-xs">Belum ada aktivitas transaksi.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Floating Action Button --}}
        <div class="fixed bottom-6 right-6 z-40">
            <a href="{{ route('orangtua.dompet.topup.create') }}" class="flex items-center justify-center w-14 h-14 bg-emerald-600 rounded-full shadow-lg hover:bg-emerald-700 transition text-white focus:outline-none focus:ring-4 focus:ring-emerald-300 active:scale-95">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </a>
        </div>

        {{-- 3. MODAL PENGATURAN KARTU (Bottom Sheet) --}}
        <div x-show="showSettingsModal" class="fixed inset-0 z-50 flex items-end justify-center sm:items-center" style="display: none;">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" x-show="showSettingsModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showSettingsModal = false"></div>
            <div class="bg-white w-full max-w-md mx-auto rounded-t-[25px] shadow-2xl p-6 pb-10 relative transform transition-all" x-show="showSettingsModal" x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-6"></div>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Pengaturan Kartu</h3>
                    <button @click="showSettingsModal = false" class="text-gray-400 hover:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <form x-bind:action="activeDompet ? activeDompet.url : '#'" method="POST">
                    @csrf @method('PUT')
                    <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold text-sm border border-emerald-200"><span x-text="activeDompet ? activeDompet.nama.substring(0,2) : ''"></span></div>
                        <div><p class="text-xs text-emerald-600 font-bold uppercase">Mengatur Kartu</p><p class="font-bold text-gray-800" x-text="activeDompet ? activeDompet.nama : ''"></p></div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Limit Jajan Harian (Rp)</label>
                        <div class="relative"><span class="absolute left-4 top-3.5 text-gray-400 font-bold">Rp</span><input type="number" name="daily_spending_limit" x-bind:value="activeDompet ? activeDompet.limit : ''" class="block w-full pl-10 pr-4 py-3 border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 font-bold text-gray-800 placeholder-gray-300" placeholder="0 (Tanpa Limit)"></div>
                    </div>
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-3">Status Keamanan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex items-center justify-center p-3 border rounded-xl cursor-pointer transition-all" :class="(activeDompet && activeDompet.status == 'active') ? 'bg-emerald-50 border-emerald-500 ring-1 ring-emerald-500' : 'bg-white border-gray-200 hover:bg-gray-50'"><input type="radio" name="status" value="active" class="sr-only" x-model="activeDompet.status" @change="activeDompet.status = 'active'"><div class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-green-500"></div><span class="text-sm font-bold" :class="(activeDompet && activeDompet.status == 'active') ? 'text-emerald-700' : 'text-gray-600'">Aktif</span></div></label>
                            <label class="relative flex items-center justify-center p-3 border rounded-xl cursor-pointer transition-all" :class="(activeDompet && activeDompet.status == 'blocked') ? 'bg-red-50 border-red-500 ring-1 ring-red-500' : 'bg-white border-gray-200 hover:bg-gray-50'"><input type="radio" name="status" value="blocked" class="sr-only" x-model="activeDompet.status" @change="activeDompet.status = 'blocked'"><div class="flex items-center gap-2"><svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg><span class="text-sm font-bold" :class="(activeDompet && activeDompet.status == 'blocked') ? 'text-red-700' : 'text-gray-600'">Blokir</span></div></label>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition active:scale-[0.98]">Simpan Pengaturan</button>
                </form>
            </div>
        </div>

        {{-- 4. MODAL FILTER (Bottom Sheet) --}}
        <div x-show="showFilterModal" class="fixed inset-0 z-50 flex items-end justify-center sm:items-center" style="display: none;">
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" x-show="showFilterModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showFilterModal = false"></div>
            <div class="bg-white w-full max-w-md mx-auto rounded-t-[25px] shadow-2xl p-6 pb-10 relative transform transition-all" x-show="showFilterModal" x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-6"></div>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Filter Transaksi</h3>
                    <button @click="showFilterModal = false" class="text-gray-400 hover:text-gray-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="space-y-2">
                    <a href="?filter=hari_ini" class="block w-full text-center py-3 rounded-xl border border-gray-200 font-medium hover:bg-emerald-50 hover:text-emerald-600 transition">Hari Ini</a>
                    <a href="?filter=minggu_ini" class="block w-full text-center py-3 rounded-xl border border-gray-200 font-medium hover:bg-emerald-50 hover:text-emerald-600 transition">Minggu Ini</a>
                    <a href="?filter=bulan_ini" class="block w-full text-center py-3 rounded-xl border border-gray-200 font-medium hover:bg-emerald-50 hover:text-emerald-600 transition">Bulan Ini</a>
                    <a href="?filter=" class="block w-full text-center py-3 rounded-xl border border-red-100 text-red-500 font-medium hover:bg-red-50 transition">Semua Transaksi</a>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                confirmButtonColor: '#059669',
                customClass: { popup: 'rounded-2xl' }
            });
        @endif
    </script>
    @endpush

</x-app-layout>