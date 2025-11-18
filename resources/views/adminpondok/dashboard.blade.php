<x-app-layout>
    {{-- Header dihapus --}}

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- WELCOME SECTION --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 relative">
                <div class="p-6 sm:p-8 flex justify-between items-center relative z-10">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Ahlan Wa Sahlan, {{ Auth::user()->name }}!</h2>
                        <p class="text-gray-500 mt-1">Berikut adalah ringkasan aktivitas pondok pesantren Anda hari ini.</p>
                    </div>
                    <div class="hidden md:block p-3 bg-emerald-50 rounded-full text-emerald-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>
                {{-- Dekorasi Background --}}
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-emerald-50 rounded-full opacity-50 blur-2xl"></div>
            </div>

            {{-- STATS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Card: Tunggakan (Red) --}}
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-red-500 p-6 relative overflow-hidden transition hover:shadow-md group">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Tunggakan</p>
                        <div class="mt-2">
                            <h3 class="text-3xl font-bold text-gray-900">
                                Rp {{ number_format($totalTunggakan, 0, ',', '.') }}
                            </h3>
                        </div>
                        <p class="text-sm text-red-600 mt-2 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Perlu Ditindaklanjuti
                        </p>
                    </div>
                    <div class="absolute right-2 top-2 text-red-50 group-hover:text-red-100 transition duration-300">
                        <svg class="w-24 h-24 -mr-4 -mt-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    </div>
                </div>
                
                {{-- Card: Pemasukan (Emerald) --}}
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-emerald-500 p-6 relative overflow-hidden transition hover:shadow-md group">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pemasukan Bulan Ini</p>
                        <div class="mt-2">
                            <h3 class="text-3xl font-bold text-emerald-600">
                                Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}
                            </h3>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Total pembayaran diterima.</p>
                    </div>
                    <div class="absolute right-2 top-2 text-emerald-50 group-hover:text-emerald-100 transition duration-300">
                        <svg class="w-24 h-24 -mr-4 -mt-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3.01 1.49 3.01 2.39 0 .5-.14 1.26-2.5 1.26-1.71 0-2.55-.77-2.65-1.8h-2.2c.09 1.97 1.37 3.33 3.65 3.8V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                    </div>
                </div>

                {{-- Card: Santri (Blue) --}}
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 p-6 relative overflow-hidden transition hover:shadow-md group">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Santri Aktif</p>
                        <div class="mt-2 flex items-baseline gap-2">
                            <h3 class="text-3xl font-bold text-gray-900">
                                {{ number_format($totalSantriAktif, 0, ',', '.') }}
                            </h3>
                            <span class="text-sm text-gray-500 font-medium">Santri</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Data siswa yang terdaftar aktif.</p>
                    </div>
                    <div class="absolute right-2 top-2 text-blue-50 group-hover:text-blue-100 transition duration-300">
                        <svg class="w-24 h-24 -mr-4 -mt-4" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- KOLOM KIRI: Aksi Cepat --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Aksi Cepat
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('adminpondok.tagihan.create') }}" class="group flex items-center justify-between p-4 bg-emerald-50 border border-emerald-100 rounded-lg hover:bg-emerald-100 hover:border-emerald-200 transition">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-white rounded-full text-emerald-600 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <span class="font-semibold text-emerald-800 group-hover:text-emerald-900">Generate Tagihan</span>
                                </div>
                                <svg class="w-5 h-5 text-emerald-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>

                            <a href="{{ route('adminpondok.santris.create') }}" class="group flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-emerald-300 hover:shadow-sm transition">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-50 rounded-full text-gray-600 group-hover:bg-emerald-50 group-hover:text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                    </div>
                                    <span class="font-medium text-gray-700 group-hover:text-emerald-700">Tambah Santri</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>

                            <a href="{{ route('adminpondok.orang-tuas.create') }}" class="group flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-emerald-300 hover:shadow-sm transition">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-50 rounded-full text-gray-600 group-hover:bg-emerald-50 group-hover:text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <span class="font-medium text-gray-700 group-hover:text-emerald-700">Tambah Orang Tua</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Riwayat Pembayaran --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden h-full">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800">Pembayaran Terbaru</h3>
                            <a href="{{ route('adminpondok.buku-besar.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Lihat Semua &rarr;</a>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse ($recentPayments as $payment)
                                <div class="p-4 hover:bg-gray-50 transition flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="p-2 rounded-full bg-green-100 text-green-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                Rp {{ number_format($payment->total_bayar, 0, ',', '.') }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $payment->tagihan->santri->full_name ?? ($payment->orangTua->name ?? 'N/A') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 capitalize">
                                            {{ str_replace('_', ' ', $payment->metode_pembayaran) }}
                                        </span>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $payment->tanggal_bayar->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500">
                                    <p>Belum ada transaksi pembayaran yang terverifikasi.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>