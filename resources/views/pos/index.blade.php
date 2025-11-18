<x-app-layout hide-nav>
    {{-- Kosongkan header default agar kita menggunakan header custom --}}
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24" x-data="posKasir()">
        
        {{-- 1. HEADER KASIR --}}
        <div class="bg-emerald-700 px-5 pt-6 pb-20 rounded-b-[30px] shadow-lg relative overflow-hidden">
            {{-- Dekorasi Background --}}
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
                <div class="w-40 h-40 bg-white opacity-5 rounded-full absolute -top-10 -right-10 blur-2xl"></div>
                <div class="w-32 h-32 bg-emerald-400 opacity-10 rounded-full absolute bottom-0 left-0 blur-xl"></div>
            </div>

            <div class="relative z-10 flex justify-between items-center mb-6">
                <div class="flex items-center gap-3">
                    {{-- Link kembali ke dashboard POS --}}
                    <a href="{{ route('pos.dashboard') }}" class="text-white/80 hover:text-white p-2 rounded-full hover:bg-white/10 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-white">Kasir Warung</h1>
                        <p class="text-emerald-200 text-xs font-medium">{{ Auth::user()->name }}</p>
                    </div>
                </div>
                
                {{-- Indikator Status (Visual) --}}
                <div class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-800/50 rounded-full backdrop-blur-sm border border-emerald-600/50">
                    <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                    <span class="text-[10px] font-medium text-emerald-100">Online</span>
                </div>
            </div>

            {{-- 2. SEARCH BAR (SCANNER) --}}
            <div class="relative z-10">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-emerald-500 group-focus-within:text-emerald-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    
                    {{-- Input Barcode --}}
                    <input x-model="barcode" 
                           @keydown.enter.prevent="findSantri" 
                           type="text" 
                           class="block w-full pl-11 pr-20 py-4 border-none rounded-2xl shadow-xl focus:ring-2 focus:ring-emerald-400 text-gray-900 placeholder-gray-400 font-medium text-lg" 
                           placeholder="Scan Kartu / Ketik NIS..." 
                           autofocus
                           x-ref="barcodeInput"
                           x-bind:disabled="loading">
                    
                    {{-- Tombol Cari --}}
                    <button @click="findSantri" 
                            class="absolute inset-y-2 right-2 px-4 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition shadow-md flex items-center disabled:opacity-70 disabled:cursor-not-allowed"
                            x-bind:disabled="loading">
                        <span x-show="!loading">Cari</span>
                        <svg x-show="loading" style="display: none;" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
                
                {{-- Notifikasi Error Inline --}}
                <div x-show="errorMessage" 
                     x-transition.opacity
                     class="mt-3 bg-red-100 border border-red-200 text-red-700 px-4 py-2 rounded-xl text-sm font-medium flex items-center gap-2 shadow-sm"
                     style="display: none;">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-text="errorMessage"></span>
                </div>
            </div>
        </div>

        {{-- 3. KONTEN UTAMA (Dynamic) --}}
        <div class="px-5 -mt-10 relative z-20">
            
            {{-- STATE 1: STANDBY (Belum ada data) --}}
            <template x-if="!santriDitemukan">
                <div class="bg-white rounded-3xl shadow-xl p-8 text-center border border-gray-100 min-h-[300px] flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-gray-100 animate-pulse">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h3 class="text-gray-800 font-bold text-xl mb-2">Siap Melayani</h3>
                    <p class="text-gray-500 text-sm max-w-xs mx-auto leading-relaxed">
                        Gunakan alat scanner atau ketik NIS santri secara manual untuk memulai transaksi.
                    </p>
                </div>
            </template>

            {{-- STATE 2: SANTRI DITEMUKAN --}}
            <template x-if="santriDitemukan">
                <div class="space-y-5 animate-fade-in-up">
                    
                    {{-- A. KARTU INFORMASI SANTRI --}}
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                        {{-- Header Kartu --}}
                        <div class="bg-gradient-to-r from-emerald-50 to-white p-6 border-b border-gray-100 flex items-center gap-5">
                            <div class="w-16 h-16 bg-white border-2 border-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl font-bold shadow-sm shrink-0">
                                <span x-text="namaSantri.substring(0, 2)"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h2 class="text-lg font-bold text-gray-900 leading-tight truncate" x-text="namaSantri"></h2>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded uppercase tracking-wide">Santri Aktif</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Saldo Display --}}
                        <div class="p-6 text-center">
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1">Sisa Saldo Dompet</p>
                            <h3 class="text-4xl font-black text-emerald-600 tracking-tight" x-text="formatRupiah(saldoSantri)"></h3>
                        </div>
                    </div>

                    {{-- B. FORM TRANSAKSI --}}
<form method="POST" action="{{ route('pos.process-transaction') }}" class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 relative overflow-hidden" autocomplete="off">
    @csrf
    <input type="hidden" name="dompet_id" x-model="dompetId">

    {{-- 
       TRICK ANTI-AUTOFILL:
       Input palsu ini tidak terlihat (opacity 0, z-index -1) tapi ada di DOM.
       Browser akan "membuang" sugesti username/password ke sini.
    --}}
    <div style="position: absolute; top: -1000px; left: -1000px; opacity: 0;">
        <input type="text" name="fake_username" autocomplete="username">
        <input type="password" name="fake_password" autocomplete="current-password">
    </div>
    
    {{-- Input Nominal --}}
    <div class="mb-6">
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Nominal Belanja</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <span class="text-gray-400 font-bold text-xl">Rp</span>
            </div>
            <input id="nominal" 
                   class="block w-full pl-14 pr-4 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-3xl font-bold text-gray-900 placeholder-gray-300 transition-all focus:bg-white" 
                   type="number" 
                   name="nominal" 
                   min="100" 
                   placeholder="0"
                   required 
                   x-ref="nominalInput"
                   autocomplete="off"
                   data-lpignore="true"> {{-- Atribut tambahan untuk LastPass/Manager lain --}}
        </div>
    </div>

    {{-- Input PIN --}}
    <div class="mb-8">
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">PIN Otorisasi</label>
        <div class="relative">
            {{-- 
                PERUBAHAN UTAMA:
                1. type="text": Browser tidak akan menganggap ini password.
                2. class="text-security-disc": CSS mengubah tampilan huruf menjadi titik (•).
                3. inputmode="numeric" & pattern: Memaksa keyboard HP muncul angka.
            --}}
            <input id="pin" 
                   class="block w-full px-4 py-4 bg-gray-50 border-gray-200 rounded-2xl focus:ring-emerald-500 focus:border-emerald-500 text-xl font-bold text-gray-900 tracking-[0.5em] text-center placeholder-gray-300 transition-all focus:bg-white text-security-disc" 
                   type="password" 
                   name="pin" 
                   maxlength="6" 
                   inputmode="numeric"
                   pattern="[0-9]*"
                   placeholder="••••••"
                   required
                   autocomplete="new-password"
                   data-lpignore="true">
        </div>
        <p class="text-center text-xs text-gray-400 mt-2">Masukkan 6 digit PIN santri</p>
    </div>

    {{-- Tombol Aksi --}}
    <div class="grid grid-cols-12 gap-4">
        <button type="button" @click="resetForm" class="col-span-4 py-4 rounded-2xl border-2 border-gray-200 text-gray-500 font-bold hover:bg-gray-50 transition flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            Batal
        </button>
        <button type="submit" class="col-span-8 py-4 rounded-2xl bg-emerald-600 text-white font-bold shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition active:scale-[0.98] flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Proses Bayar
        </button>
    </div>
</form>

                </div>
            </template>

        </div>
    </div>

    {{-- Include Menu Navigasi Bawah --}}
    @include('layouts.pos-nav')

    @push('scripts')
    {{-- SweetAlert (Jika ada flash message dari controller) --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                timer: 2500,
                showConfirmButton: false,
                customClass: { popup: 'rounded-3xl' }
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session("error") }}',
                customClass: { popup: 'rounded-3xl' }
            });
        </script>
    @endif

    <script>
        function posKasir() {
            return {
                barcode: '',
                loading: false,
                errorMessage: '',
                santriDitemukan: false,
                
                // Data santri
                namaSantri: '',
                saldoSantri: 0,
                dompetId: null,

                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
                },

                async findSantri() {
                    if (this.barcode === '') return;
                    this.loading = true;
                    this.errorMessage = '';
                    this.santriDitemukan = false;

                    try {
                        const response = await fetch("{{ route('pos.find-santri') }}", {
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
                        
                        // Jika Sukses
                        this.namaSantri = data.nama_santri;
                        this.saldoSantri = data.saldo;
                        this.dompetId = data.dompet_id;
                        this.santriDitemukan = true;
                        
                        // Auto-focus ke input nominal
                        this.$nextTick(() => {
                            if(this.$refs.nominalInput) this.$refs.nominalInput.focus();
                        });

                    } catch (error) {
                        this.errorMessage = error.message;
                        // Reset focus ke barcode agar bisa scan ulang
                        this.$nextTick(() => {
                            this.$refs.barcodeInput.focus();
                            this.$refs.barcodeInput.select();
                        });
                    } finally {
                        this.loading = false;
                        this.barcode = ''; // Reset input barcode
                    }
                },

                resetForm() {
                    this.santriDitemukan = false;
                    this.namaSantri = '';
                    this.saldoSantri = 0;
                    this.dompetId = null;
                    this.errorMessage = '';
                    
                    // Focus kembali ke scan
                    this.$nextTick(() => {
                        this.$refs.barcodeInput.focus();
                    });
                }
            }
        }
    </script>
    <style>
        /* Animasi Halus untuk Munculnya Kartu */
        .animate-fade-in-up {
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translate3d(0, 20px, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
    </style>
    @endpush
</x-app-layout>