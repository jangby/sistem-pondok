<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-900 flex flex-col p-5 font-sans" x-data="auditScanner()">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-white font-bold text-xl">Stock Opname</h1>
            <a href="{{ route('pengurus.inventaris.rekap.index') }}" class="text-gray-400 text-xs">Selesai</a>
        </div>

        {{-- STEP 1: SCAN BARCODE --}}
        <div x-show="step === 1">
            <div class="mb-6 text-center">
                <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-purple-500 animate-pulse">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M19 19h-5m-9-6h5a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zM6 21v-2a1 1 0 011-1h10V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16z"></path></svg>
                </div>
                <p class="text-gray-300">Scan Barcode Barang</p>
            </div>
            <input type="text" x-ref="scanInput" @keydown.enter="checkItem($el.value)" 
                class="w-full bg-gray-800 border-2 border-purple-500 text-white text-center rounded-2xl py-4 text-lg tracking-widest focus:ring-4 focus:ring-purple-900 outline-none" 
                placeholder="SCAN..." autofocus>
        </div>

        {{-- STEP 2: INPUT JUMLAH FISIK --}}
        <div x-show="step === 2" style="display: none;">
            <div class="bg-gray-800 p-5 rounded-3xl border border-gray-700 mb-6 text-center">
                <p class="text-gray-400 text-xs uppercase font-bold">Barang Ditemukan</p>
                <h2 class="text-2xl font-bold text-white mt-1" x-text="itemData.barang"></h2>
            </div>

            <label class="text-gray-400 text-xs uppercase font-bold block mb-2">Jumlah Fisik (Riil)</label>
            <input type="number" x-model="actualQty" @keydown.enter="submitAudit()"
                class="w-full bg-gray-800 border-2 border-green-500 text-white text-center rounded-2xl py-4 text-2xl font-bold focus:ring-4 focus:ring-green-900 outline-none" 
                placeholder="0">

            <button @click="submitAudit()" class="w-full bg-green-600 text-white font-bold py-4 rounded-2xl mt-6 shadow-lg active:scale-95">
                Simpan Hasil Audit
            </button>
            <button @click="reset()" class="w-full text-gray-500 text-sm mt-4">Batal / Scan Ulang</button>
        </div>

    </div>

    <script>
        function auditScanner() {
            return {
                step: 1,
                code: '',
                actualQty: '',
                itemData: {},

                init() {
                    setInterval(() => {
                        if(this.step === 1 && document.activeElement !== this.$refs.scanInput) {
                            this.$refs.scanInput.focus();
                        }
                    }, 1000);
                },

                checkItem(code) {
                    this.code = code;
                    // Di sini kita langsung lanjut ke input jumlah 
                    // (Idealnya cek DB via AJAX dulu, tapi agar cepat kita asumsi barang ada 
                    // atau kita kirim code saat submit akhir)
                    this.step = 2;
                },

                async submitAudit() {
                    try {
                        const response = await axios.post('{{ route('pengurus.inventaris.rekap.process') }}', {
                            code: this.code,
                            actual_qty: this.actualQty
                        });

                        if (response.data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Tercatat!',
                                text: 'Selisih: ' + response.data.selisih,
                                timer: 1500, showConfirmButton: false,
                                customClass: { popup: 'rounded-3xl' }
                            });
                            this.reset();
                        }
                    } catch (error) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Barang tidak ditemukan', customClass: { popup: 'rounded-3xl' } });
                        this.reset();
                    }
                },

                reset() {
                    this.step = 1;
                    this.code = '';
                    this.actualQty = '';
                    this.$refs.scanInput.value = '';
                    this.$refs.scanInput.focus();
                }
            }
        }
    </script>
</x-app-layout>