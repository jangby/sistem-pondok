<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-32"
         x-data="{
            nominalTopup: '',
            biayaAdmin: 5000,
            paymentMethod: '',
            
            // Helper untuk set nominal dari chip
            setNominal(amount) {
                this.nominalTopup = amount;
            },

            get nominalTampil() {
                let nominal = parseInt(this.nominalTopup) || 0;
                return nominal;
            },

            get totalTagih() {
                if (this.nominalTampil < 10000) return 0;
                return this.nominalTampil + this.biayaAdmin;
            },

            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR', 
                    minimumFractionDigits: 0 
                }).format(angka);
            }
         }">
        
        {{-- 1. MOBILE HEADER --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('orangtua.dompet.index') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Isi Saldo</h1>
            </div>
        </div>

        {{-- 2. FORM UTAMA --}}
        <div class="px-5 -mt-16 relative z-20">
            <form method="POST" action="{{ route('orangtua.dompet.topup.store') }}">
                @csrf
                
                {{-- PILIH SANTRI --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6 p-6">
                    <label class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-3 block">Isi Saldo Untuk</label>
                    <div class="relative">
                        <select name="dompet_id" class="block w-full pl-4 pr-10 py-3 border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-gray-900 text-sm font-medium appearance-none" required>
                            @foreach ($santris as $santri)
                                @if($santri->dompet)
                                    <option value="{{ $santri->dompet->id }}" {{ old('dompet_id', request('dompet_id')) == $santri->dompet->id ? 'selected' : '' }}>
                                        {{ $santri->full_name }} (Saldo: Rp {{ number_format($santri->dompet->saldo, 0) }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- NOMINAL INPUT --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="text-sm font-bold text-gray-800 mb-4">Nominal Top Up</h3>
                    
                    <div class="mb-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-bold">Rp</span>
                            </div>
                            <input type="number" 
                                   name="nominal_topup" 
                                   x-model.number="nominalTopup"
                                   min="10000"
                                   class="block w-full pl-12 pr-4 py-3 border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-2xl font-bold text-gray-900 placeholder-gray-300 transition-colors" 
                                   placeholder="0"
                                   required>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 ml-1">Minimal top up Rp 10.000</p>
                        <x-input-error :messages="$errors->get('nominal_topup')" class="mt-2" />
                    </div>

                    {{-- Quick Amount Chips --}}
                    <div class="grid grid-cols-3 gap-3 mb-2">
                        <button type="button" @click="setNominal(20000)" class="py-2 px-2 rounded-lg border text-xs font-semibold transition-all" :class="nominalTopup == 20000 ? 'bg-emerald-50 border-emerald-500 text-emerald-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'">
                            20.000
                        </button>
                        <button type="button" @click="setNominal(50000)" class="py-2 px-2 rounded-lg border text-xs font-semibold transition-all" :class="nominalTopup == 50000 ? 'bg-emerald-50 border-emerald-500 text-emerald-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'">
                            50.000
                        </button>
                        <button type="button" @click="setNominal(100000)" class="py-2 px-2 rounded-lg border text-xs font-semibold transition-all" :class="nominalTopup == 100000 ? 'bg-emerald-50 border-emerald-500 text-emerald-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'">
                            100.000
                        </button>
                        <button type="button" @click="setNominal(200000)" class="py-2 px-2 rounded-lg border text-xs font-semibold transition-all" :class="nominalTopup == 200000 ? 'bg-emerald-50 border-emerald-500 text-emerald-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'">
                            200.000
                        </button>
                        <button type="button" @click="setNominal(500000)" class="py-2 px-2 rounded-lg border text-xs font-semibold transition-all" :class="nominalTopup == 500000 ? 'bg-emerald-50 border-emerald-500 text-emerald-700' : 'bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100'">
                            500.000
                        </button>
                    </div>
                </div>

                {{-- METODE PEMBAYARAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="text-sm font-bold text-gray-800 mb-4">Metode Pembayaran</h3>

                    {{-- Virtual Account --}}
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3 mt-2">Transfer Bank (VA)</p>
                    <div class="space-y-3">
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

                {{-- STICKY BOTTOM BAR --}}
                <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-100 p-4 pb-6 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] z-50">
                    <div class="max-w-3xl mx-auto">
                        <div class="flex justify-between items-center mb-3 px-1">
                            <span class="text-xs text-gray-500">Biaya Admin</span>
                            <span class="text-xs font-bold text-gray-700">Rp {{ number_format(5000, 0, ',', '.') }}</span>
                        </div>
                        <button type="submit" 
                                :disabled="totalTagih == 0 || paymentMethod == ''"
                                class="w-full flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl font-bold text-sm uppercase tracking-wider shadow-lg transition-all transform active:scale-[0.98]"
                                :class="(totalTagih > 0 && paymentMethod != '') ? 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-emerald-200' : 'bg-gray-300 text-gray-500 cursor-not-allowed'">
                            <span>Top Up Sekarang</span>
                            <span x-show="totalTagih > 0" x-text="'• ' + formatRupiah(totalTagih)"></span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>