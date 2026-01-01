<x-app-layout>
    <x-slot name="navigation">
        @include('layouts.petugas-nav')
    </x-slot>

    {{-- STYLE KHUSUS UNTUK PRINT --}}
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            /* Memaksa browser mencetak background color */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- PESAN SUKSES (Hanya tampil di layar) --}}
            <div class="no-print bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between items-center">
                <div>
                    <p class="font-bold">Pendaftaran Berhasil Disimpan!</p>
                    <p class="text-sm">Silakan cetak kartu di bawah ini dan berikan kepada Orang Tua santri.</p>
                </div>
            </div>

            {{-- AREA YANG AKAN DI-PRINT --}}
            <div id="print-area" class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-200 relative">
                
                {{-- 1. HEADER KARTU --}}
                <div class="bg-gradient-to-r from-emerald-700 to-teal-600 p-6 text-white flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        {{-- Placeholder Logo (Bisa diganti <img>) --}}
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border-2 border-white/30">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold uppercase tracking-wide">{{ config('app.name', 'Sistem Pondok') }}</h1>
                            <p class="text-emerald-100 text-sm">Bukti Pendaftaran & Akses Sistem</p>
                        </div>
                    </div>
                    <div class="text-right hidden sm:block">
                        <span class="block text-xs text-emerald-200 uppercase tracking-widest">Tahun Ajaran</span>
                        <span class="block text-xl font-bold">{{ $calonSantri->ppdbSetting->tahun_ajaran ?? date('Y') }}</span>
                    </div>
                </div>

                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-8">
                        
                        {{-- 2. INFORMASI PENDAFTARAN (KIRI) --}}
                        <div class="flex-1 space-y-4">
                            <h3 class="text-emerald-700 font-bold border-b border-gray-200 pb-2 mb-4">DATA CALON SANTRI</h3>
                            
                            <div class="grid grid-cols-2 gap-y-3 text-sm">
                                <div class="text-gray-500">No. Pendaftaran</div>
                                <div class="font-mono font-bold text-gray-800 text-lg">{{ $calonSantri->no_pendaftaran }}</div>

                                <div class="text-gray-500">Nama Lengkap</div>
                                <div class="font-bold text-gray-800 uppercase">{{ $calonSantri->nama_lengkap }}</div>

                                <div class="text-gray-500">NIK</div>
                                <div class="font-medium text-gray-800">{{ $calonSantri->nik }}</div>

                                <div class="text-gray-500">Jenjang</div>
                                <div>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-0.5 rounded border border-blue-200">
                                        {{ $calonSantri->jenjang }}
                                    </span>
                                </div>

                                <div class="text-gray-500">Gelombang</div>
                                <div class="font-medium text-gray-800">{{ $calonSantri->ppdbSetting->nama_gelombang ?? '-' }}</div>

                                <div class="text-gray-500">Jalur Daftar</div>
                                <div class="font-medium text-emerald-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Offline / Walk-in
                                </div>

                                <div class="text-gray-500">Tgl. Daftar</div>
                                <div class="font-medium text-gray-800">{{ $calonSantri->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>

                        {{-- 3. AKUN LOGIN (KANAN - HIGHLIGHT) --}}
                        <div class="flex-1">
                            <div class="bg-yellow-50 border-2 border-dashed border-yellow-300 rounded-xl p-6 h-full flex flex-col justify-center relative">
                                <div class="absolute top-0 right-0 bg-yellow-300 text-yellow-900 text-xs font-bold px-3 py-1 rounded-bl-lg">
                                    PENTING
                                </div>
                                
                                <h3 class="text-center font-bold text-gray-700 mb-4 uppercase tracking-wider text-sm">Akun Wali Santri</h3>
                                <p class="text-xs text-center text-gray-500 mb-4">Gunakan akun ini untuk login di website, melihat pengumuman, dan membayar tagihan.</p>

                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1 text-center">URL Website</label>
                                        <div class="bg-white border border-gray-200 rounded p-2 text-center text-sm font-mono text-gray-600 truncate">
                                            {{ config('app.url') }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1 text-center">Username / Email</label>
                                        <div class="bg-white border border-yellow-200 rounded p-2 text-center font-mono font-bold text-blue-700 text-lg shadow-sm">
                                            {{ $credentials['username'] }}
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1 text-center">Password</label>
                                        <div class="bg-white border border-yellow-200 rounded p-2 text-center font-mono font-bold text-red-600 text-lg shadow-sm tracking-widest">
                                            {{ $credentials['password'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. FOOTER KARTU --}}
                    <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-end gap-4">
                        <div class="text-xs text-gray-400 italic max-w-sm">
                            * Kartu ini adalah bukti pendaftaran yang sah. Harap disimpan dengan baik.
                            <br>Dicetak oleh: {{ Auth::user()->name }} pada {{ now()->format('d/m/Y H:i') }}
                        </div>
                        
                        {{-- Area Tanda Tangan (Opsional) --}}
                        <div class="text-center w-40">
                            <p class="text-xs text-gray-500 mb-10">Petugas Pendaftaran,</p>
                            <div class="border-b border-gray-300"></div>
                            <p class="text-xs font-bold text-gray-700 mt-1">{{ Auth::user()->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. TOMBOL AKSI (HIDDEN ON PRINT) --}}
            <div class="no-print mt-8 flex flex-col md:flex-row justify-center gap-4">
                <button onclick="window.print()" class="flex items-center justify-center gap-2 bg-gray-800 text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-900 transition shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Kartu (Print)
                </button>

                <a href="{{ route('petugas.pendaftaran.create') }}" class="flex items-center justify-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Input Pendaftar Baru
                </a>

                <a href="{{ route('petugas.dashboard') }}" class="flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-50 transition shadow">
                    Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>
</x-app-layout>