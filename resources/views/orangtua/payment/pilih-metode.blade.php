<x-app-layout hide-nav>
    {{-- Header default dikosongkan --}}
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-32"
         x-data="{
            sisaTagihan: {{ $sisaTagihan }},
            nominalBayar: {{ $sisaTagihan }}, // Default lunas
            biayaAdmin: 5000,
            paymentMethod: '', // Untuk tracking pilihan visual
            
            // Hitung nominal valid (min 10rb, max sisa tagihan)
            get nominalTampil() {
                let nominal = parseInt(this.nominalBayar) || 0;
                if (nominal > this.sisaTagihan) return this.sisaTagihan;
                if (nominal < 10000) return 0;
                return nominal;
            },

            // Hitung total akhir (+ admin)
            get totalTagih() {
                if (this.nominalTampil === 0) return 0;
                return this.nominalTampil + this.biayaAdmin;
            },

            // Format Rupiah
            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR', 
                    minimumFractionDigits: 0 
                }).format(angka);
            }
         }">

        {{-- 1. HEADER APLIKASI --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('orangtua.tagihan.show', $tagihan->id) }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Pilih Metode Bayar</h1>
            </div>
        </div>

        {{-- 2. KONTEN UTAMA --}}
        <div class="px-5 -mt-16 relative z-20">
            <form method="POST" action="{{ route('orangtua.tagihan.proses-pembayaran', $tagihan->id) }}">
                @csrf
                
                {{-- KARTU TOTAL BAYAR --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                    <div class="p-6 text-center border-b border-gray-50">
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-2">Total Pembayaran</p>
                        {{-- Total dinamis dari AlpineJS --}}
                        <h2 class="text-3xl font-black text-emerald-600 tracking-tight" x-text="formatRupiah(totalTagih)"></h2>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase font-medium tracking-wide">+ Biaya Admin Rp {{ number_format(5000, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500">Tagihan</span>
                            <span class="font-medium text-gray-900 truncate max-w-[150px]">{{ $tagihan->jenisPembayaran->name }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Sisa Tagihan</span>
                            <span class="font-bold text-red-500">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- INPUT NOMINAL (CICILAN) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">1</div>
                        Nominal Pembayaran
                    </h3>
                    
                    <div>
                        <label class="block text-xs text-gray-500 mb-2">Masukkan jumlah (Min Rp 10.000)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-bold">Rp</span>
                            </div>
                            <input type="number" 
                                   name="nominal_bayar" 
                                   x-model.number="nominalBayar"
                                   min="10000"
                                   :max="$sisaTagihan"
                                   x-on:input="if (nominalBayar > sisaTagihan) nominalBayar = sisaTagihan"
                                   class="block w-full pl-12 pr-4 py-3 border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-lg font-bold text-gray-900 placeholder-gray-300 transition-colors" 
                                   placeholder="0"
                                   required>
                        </div>
                        <div class="mt-2 flex justify-end">
                            <button type="button" @click="nominalBayar = sisaTagihan" class="text-xs font-bold text-emerald-600 hover:underline">
                                Bayar Lunas (Semua)
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('nominal_bayar')" class="mt-2" />
                    </div>
                </div>

                {{-- PILIH METODE --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">2</div>
                        Metode Pembayaran
                    </h3>

                    {{-- Virtual Account --}}
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3 mt-2">Virtual Account (Otomatis)</p>
                    <div class="space-y-3">
                        {{-- BCA --}}
                        <label class="relative flex items-center p-4 border rounded-xl cursor-pointer transition-all duration-200 hover:bg-emerald-50 group"
                               :class="paymentMethod === 'bca_va' ? 'border-emerald-500 bg-emerald-50 ring-1 ring-emerald-500 shadow-sm' : 'border-gray-200'">
                            <input type="radio" name="payment_method" value="bca_va" class="sr-only" x-model="paymentMethod" required>
                            <div class="flex-1 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center text-white font-bold text-[10px] shadow-sm">BCA</div>
                                    <span class="font-medium text-sm text-gray-700 group-hover:text-emerald-800">BCA Virtual Account</span>
                                </div>
                                <div x-show="paymentMethod === 'bca_va'" class="text-emerald-600" x-transition>
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                            </div>
                        </label>

                        {{-- BNI --}}
                        <label class="relative flex items-center p-4 border rounded-xl cursor-pointer transition-all duration-200 hover:bg-emerald-50 group"
                               :class="paymentMethod === 'bni_va' ? 'border-emerald-500 bg-emerald-50 ring-1 ring-emerald-500 shadow-sm' : 'border-gray-200'">
                            <input type="radio" name="payment_method" value="bni_va" class="sr-only" x-model="paymentMethod">
                            <div class="flex-1 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-orange-600 rounded-lg flex items-center justify-center text-white font-bold text-[10px] shadow-sm">BNI</div>
                                    <span class="font-medium text-sm text-gray-700 group-hover:text-emerald-800">BNI Virtual Account</span>
                                </div>
                                <div x-show="paymentMethod === 'bni_va'" class="text-emerald-600" x-transition>
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                            </div>
                        </label>
                    </div>

                    {{-- E-Wallet --}}
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3 mt-6">E-Wallet / QRIS</p>
                    <div class="space-y-3">
                        {{-- QRIS --}}
                        <label class="relative flex items-center p-4 border rounded-xl cursor-pointer transition-all duration-200 hover:bg-emerald-50 group"
                               :class="paymentMethod === 'qris' ? 'border-emerald-500 bg-emerald-50 ring-1 ring-emerald-500 shadow-sm' : 'border-gray-200'">
                            <input type="radio" name="payment_method" value="qris" class="sr-only" x-model="paymentMethod">
                            <div class="flex-1 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-white font-bold text-[10px] shadow-sm">QRIS</div>
                                    <div class="flex flex-col">
                                        <span class="font-medium text-sm text-gray-700 group-hover:text-emerald-800">Scan QRIS</span>
                                        <span class="text-[10px] text-gray-400">GoPay, OVO, Dana, ShopeePay</span>
                                    </div>
                                </div>
                                <div x-show="paymentMethod === 'qris'" class="text-emerald-600" x-transition>
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                            </div>
                        </label>

                        {{-- GoPay --}}
                        <label class="relative flex items-center p-4 border rounded-xl cursor-pointer transition-all duration-200 hover:bg-emerald-50 group"
                               :class="paymentMethod === 'gopay' ? 'border-emerald-500 bg-emerald-50 ring-1 ring-emerald-500 shadow-sm' : 'border-gray-200'">
                            <input type="radio" name="payment_method" value="gopay" class="sr-only" x-model="paymentMethod">
                            <div class="flex-1 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-sky-500 rounded-lg flex items-center justify-center text-white font-bold text-[10px] shadow-sm">GOPAY</div>
                                    <span class="font-medium text-sm text-gray-700 group-hover:text-emerald-800">GoPay App</span>
                                </div>
                                <div x-show="paymentMethod === 'gopay'" class="text-emerald-600" x-transition>
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                            </div>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                </div>

                {{-- TOMBOL BAYAR STICKY --}}
                <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-100 p-4 pb-6 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] z-50">
                    <div class="max-w-3xl mx-auto">
                        <button type="submit" 
                                :disabled="totalTagih == 0 || paymentMethod == ''"
                                class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl font-bold text-sm uppercase tracking-wider shadow-lg transition-all transform active:scale-[0.98]"
                                :class="(totalTagih > 0 && paymentMethod != '') ? 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-emerald-200' : 'bg-gray-300 text-gray-500 cursor-not-allowed'">
                            <span>Bayar</span>
                            {{-- Tampilkan total di tombol --}}
                            <span x-text="formatRupiah(totalTagih)"></span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>