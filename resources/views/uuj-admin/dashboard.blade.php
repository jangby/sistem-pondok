<x-app-layout>
    {{-- Header dihapus untuk tampilan lebih bersih --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- WELCOME SECTION --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 relative">
                <div class="p-6 sm:p-8 flex justify-between items-center relative z-10">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Dashboard Uang Jajan</h2>
                        <p class="text-gray-500 mt-1">Pantau aktivitas transaksi dompet santri dan saldo warung.</p>
                    </div>
                    <div class="hidden md:block p-3 bg-emerald-50 rounded-full text-emerald-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                {{-- Dekorasi Background --}}
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-emerald-50 rounded-full opacity-50 blur-2xl"></div>
            </div>

            {{-- STATS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Card: Total Saldo Santri --}}
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 p-6 relative overflow-hidden transition hover:shadow-md group">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Saldo Santri</p>
                        <div class="mt-2">
                            <h3 class="text-3xl font-bold text-gray-900">
                                Rp {{ number_format($totalSaldoSantri, 0, ',', '.') }}
                            </h3>
                        </div>
                        <p class="text-sm text-blue-600 mt-2 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            {{ $totalSantriAktif }} Dompet Aktif
                        </p>
                    </div>
                    <div class="absolute right-2 top-2 text-blue-50 group-hover:text-blue-100 transition duration-300">
                        <svg class="w-24 h-24 -mr-4 -mt-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                </div>
                
                {{-- Card: Transaksi Hari Ini --}}
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-emerald-500 p-6 relative overflow-hidden transition hover:shadow-md group">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Transaksi Hari Ini</p>
                        <div class="mt-2">
                            <h3 class="text-3xl font-bold text-emerald-600">
                                {{ $transaksiHariIni }}
                            </h3>
                        </div>
                        <p class="text-sm text-emerald-600 mt-2 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            Aktivitas Harian
                        </p>
                    </div>
                    <div class="absolute right-2 top-2 text-emerald-50 group-hover:text-emerald-100 transition duration-300">
                        <svg class="w-24 h-24 -mr-4 -mt-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                    </div>
                </div>

                {{-- Card: Saldo Warung --}}
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-yellow-500 p-6 relative overflow-hidden transition hover:shadow-md group">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Saldo Warung (Total)</p>
                        <div class="mt-2">
                            <h3 class="text-3xl font-bold text-gray-900">
                                Rp {{ number_format($totalSaldoWarung, 0, ',', '.') }}
                            </h3>
                        </div>
                        <p class="text-sm text-yellow-600 mt-2 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            {{ $totalWarung }} Warung Aktif
                        </p>
                    </div>
                    <div class="absolute right-2 top-2 text-yellow-50 group-hover:text-yellow-100 transition duration-300">
                        <svg class="w-24 h-24 -mr-4 -mt-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
                    </div>
                </div>

            </div>

            {{-- 2. MAIN CONTENT GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KOLOM KIRI: Aksi Cepat & Warung --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Aksi Cepat --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Aksi Cepat
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('uuj-admin.topup.create') }}" class="group flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 rounded-lg hover:bg-emerald-100 hover:border-emerald-200 transition">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white rounded-full text-emerald-600 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </div>
                                    <span class="font-semibold text-emerald-800 group-hover:text-emerald-900">Top-up Manual</span>
                                </div>
                                <svg class="w-5 h-5 text-emerald-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>

                            <a href="{{ route('uuj-admin.tarik.create') }}" class="group flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-emerald-300 hover:shadow-sm transition">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-50 rounded-full text-gray-600 group-hover:bg-emerald-50 group-hover:text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <span class="font-medium text-gray-700 group-hover:text-emerald-700">Tarik Tunai</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>

                            <a href="{{ route('uuj-admin.payout.index') }}" class="group flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-emerald-300 hover:shadow-sm transition">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-50 rounded-full text-gray-600 group-hover:bg-emerald-50 group-hover:text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    </div>
                                    <span class="font-medium text-gray-700 group-hover:text-emerald-700">Konfirmasi Payout</span>
                                </div>
                                @if($pendingPayoutCount > 0)
                                    <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-1 rounded-full">{{ $pendingPayoutCount }}</span>
                                @else
                                    <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                @endif
                            </a>
                        </div>
                    </div>

                    {{-- Daftar Warung --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800">Warung / Kantin</h3>
                            <a href="{{ route('uuj-admin.warung.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Kelola</a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse ($warungs as $warung)
                                <div class="p-4 hover:bg-gray-50 transition flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-yellow-50 rounded-full flex items-center justify-center text-yellow-600 font-bold text-sm">
                                            {{ substr($warung->nama_warung, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $warung->nama_warung }}</p>
                                            <p class="text-xs text-gray-500">{{ $warung->user->name ?? 'No Admin' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400">Saldo</p>
                                        <p class="font-bold text-gray-800">Rp {{ number_format($warung->saldo, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-500 text-sm">Belum ada warung terdaftar.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Aktivitas Transaksi --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden h-full">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800">Aktivitas Terbaru</h3>
                            <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded">Realtime</span>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse ($recentTransactions as $trx)
                                <div class="p-4 hover:bg-gray-50 transition flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="p-2 rounded-full 
                                            {{ in_array($trx->tipe, ['topup_manual', 'topup_midtrans']) ? 'bg-emerald-100 text-emerald-600' : 'bg-orange-100 text-orange-600' }}">
                                            @if(in_array($trx->tipe, ['topup_manual', 'topup_midtrans']))
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 capitalize">
                                                {{ str_replace('_', ' ', $trx->tipe) }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $trx->dompet->santri->full_name ?? 'Unknown' }}
                                                @if($trx->warung)
                                                    <span class="text-gray-400 text-xs">• di {{ $trx->warung->nama_warung }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold {{ in_array($trx->tipe, ['topup_manual', 'topup_midtrans']) ? 'text-emerald-600' : 'text-red-500' }}">
                                            {{ in_array($trx->tipe, ['topup_manual', 'topup_midtrans']) ? '+' : '-' }}Rp {{ number_format(abs($trx->nominal), 0, ',', '.') }}
                                        </span>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $trx->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-12 text-center flex flex-col items-center justify-center text-gray-500">
                                    <div class="bg-gray-50 p-4 rounded-full mb-3">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p>Belum ada aktivitas transaksi hari ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>