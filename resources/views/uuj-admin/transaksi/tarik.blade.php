<x-app-layout>
    <x-slot name="header"></x-slot>

    {{-- Tambahkan CSS khusus text-security untuk PIN --}}
    @push('scripts')
    <style>
        .text-security-disc {
            -webkit-text-security: disc;
            text-security: disc;
        }
    </style>
    @endpush

    <div class="py-6" x-data="tarikTunaiApp()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('uuj-admin.dashboard') }}" class="text-sm text-gray-500 hover:text-emerald-600 flex items-center gap-1 font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                
                {{-- HEADER --}}
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-xl font-bold text-gray-800">Tarik Tunai Saldo (Secure)</h2>
                    <p class="text-sm text-gray-500">Membutuhkan scan kartu dan PIN santri untuk keamanan.</p>
                </div>

                <div class="p-8 text-gray-900">
                    
                    {{-- ALERT ERROR --}}
                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- 1. FORM SCANNER --}}
                    <div x-show="!santriDitemukan" class="text-center py-8 transition-all">
                        <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-emerald-100">
                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Scan Kartu Santri</h3>
                        <p class="text-sm text-gray-500 mb-6">Silakan scan barcode kartu santri atau ketik NIS.</p>
                        
                        <div class="max-w-md mx-auto relative">
                            <input type="text" 
                                   x-model="barcode" 
                                   @keydown.enter.prevent="cariSantri"
                                   class="block w-full pl-4 pr-12 py-3 border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-center text-lg font-bold tracking-widest" 
                                   placeholder="KODE KARTU / NIS" 
                                   autofocus
                                   :disabled="loading">
                            
                            <button @click="cariSantri" 
                                    class="absolute right-2 top-2 bottom-2 px-4 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center"
                                    :disabled="loading">
                                <span x-show="!loading">Cari</span>
                                <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </div>
                        <p x-show="errorMessage" x-text="errorMessage" class="text-red-600 mt-3 text-sm font-medium"></p>
                    </div>

                    {{-- 2. HASIL PENCARIAN & FORM TARIK --}}
                    <div x-show="santriDitemukan" style="display: none;" class="animate-fade-in-up">
                        <form method="POST" action="{{ route('uuj-admin.tarik.store') }}" autocomplete="off">
                            @csrf
                            <input type="hidden" name="dompet_id" x-model="dompetId">

                            {{-- Dummy inputs untuk mencegah autofill browser --}}
                            <div style="position: absolute; top: -9999px;">
                                <input type="text" name="fake_user" autocomplete="username">
                                <input type="password" name="fake_pass" autocomplete="new-password">
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                                
                                {{-- KARTU IDENTITAS --}}
                                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
                                    
                                    <div class="relative z-10 flex items-center gap-4 mb-6">
                                        <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-2xl font-bold border border-white/30">
                                            <span x-text="namaSantri.substring(0, 2)"></span>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold" x-text="namaSantri"></h3>
                                            <p class="text-emerald-100 text-sm font-mono" x-text="'NIS: ' + nis"></p>
                                            <p class="text-emerald-100 text-sm" x-text="'Kelas: ' + kelas"></p>
                                        </div>
                                    </div>

                                    <div class="relative z-10 pt-4 border-t border-white/20">
                                        <p class="text-xs text-emerald-100 uppercase font-bold tracking-wider">Saldo Tersedia</p>
                                        <p class="text-3xl font-bold tracking-tight" x-text="formatRupiah(saldo)"></p>
                                    </div>
                                </div>

                                {{-- FORM INPUT --}}
                                <div class="space-y-5">
                                    <div>
                                        <x-input-label for="nominal" :value="__('Nominal Penarikan')" />
                                        <div class="relative mt-1">
                                            <span class="absolute left-4 top-3.5 text-gray-400 font-bold">Rp</span>
                                            <input type="number" name="nominal" x-model="nominal" 
                                                   class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 font-bold text-lg text-red-600" 
                                                   placeholder="0" min="1000" required autofocus>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Minimal penarikan Rp 1.000</p>
                                    </div>

                                    <div>
                                        <x-input-label for="pin" :value="__('PIN Santri (Otorisasi)')" />
                                        <input type="text" name="pin" 
                                               class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-lg font-bold text-center tracking-[0.5em] text-security-disc" 
                                               maxlength="6" inputmode="numeric" pattern="[0-9]*" placeholder="******" required autocomplete="new-password">
                                    </div>

                                    <div>
                                        <x-input-label for="keterangan" :value="__('Alasan Penarikan')" />
                                        <input type="text" name="keterangan" value="Tarik Tunai" 
                                               class="block w-full px-4 py-2 border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                                <button type="button" @click="resetForm" class="text-gray-500 font-medium hover:text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                                    &larr; Batal / Ganti Santri
                                </button>
                                <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-red-200 hover:bg-red-700 transition transform active:scale-95 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Konfirmasi Tarik Tunai
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function tarikTunaiApp() {
            return {
                barcode: '',
                loading: false,
                errorMessage: '',
                santriDitemukan: false,
                
                // Data santri
                dompetId: null,
                namaSantri: '',
                nis: '',
                kelas: '',
                saldo: 0,
                nominal: '',

                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
                },

                async cariSantri() {
                    if (this.barcode === '') return;
                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        // Gunakan route name yang sudah kita buat di web.php
                        const response = await fetch("{{ route('uuj-admin.tarik.find-santri') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ barcode: this.barcode })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Santri tidak ditemukan');
                        }
                        
                        this.dompetId = data.dompet_id;
                        this.namaSantri = data.nama_santri;
                        this.nis = data.nis;
                        this.kelas = data.kelas;
                        this.saldo = data.saldo;
                        this.santriDitemukan = true;

                    } catch (error) {
                        this.errorMessage = error.message;
                        this.barcode = ''; // Reset input
                        // Focus balik ke input (opsional)
                    } finally {
                        this.loading = false;
                    }
                },

                resetForm() {
                    this.santriDitemukan = false;
                    this.barcode = '';
                    this.errorMessage = '';
                    this.dompetId = null;
                    this.nominal = '';
                }
            }
        }
    </script>
    @endpush
</x-app-layout>