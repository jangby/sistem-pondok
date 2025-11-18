<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-900 flex flex-col p-5 font-sans relative overflow-hidden" x-data="scannerApp()">
        
        <div class="flex justify-between items-center mb-6 relative z-10">
            <h1 class="text-white font-bold text-xl">Scan Jamaah</h1>
            <a href="{{ route('pengurus.absensi.jamaah') }}" class="text-gray-400 text-xs">Selesai</a>
        </div>

        {{-- PILIH SHOLAT --}}
        <div class="mb-6 relative z-10">
            <select x-model="sholat" class="w-full bg-gray-800 border border-gray-600 text-white rounded-2xl p-4 text-sm font-bold focus:ring-emerald-500">
                <option value="">-- Pilih Waktu Sholat --</option>
                <option value="Subuh">Subuh</option>
                <option value="Dzuhur">Dzuhur</option>
                <option value="Ashar">Ashar</option>
                <option value="Maghrib">Maghrib</option>
                <option value="Isya">Isya</option>
            </select>
        </div>

        {{-- INPUT --}}
        <div class="mb-4 relative z-10">
            <input type="text" x-ref="scanInput" :disabled="!sholat" @keydown.enter="addQueue($el.value); $el.value = ''" 
                class="w-full bg-gray-900 border-2 text-white text-center rounded-2xl py-5 text-xl tracking-widest outline-none disabled:opacity-50"
                :class="sholat ? 'border-emerald-500 focus:border-emerald-400' : 'border-gray-700'"
                placeholder="TAP KARTU..." autofocus autocomplete="off">
        </div>

        {{-- LOG --}}
        <div class="flex-1 bg-gray-800/40 backdrop-blur-xl rounded-[2rem] p-3 overflow-y-auto space-y-2 border border-gray-700/50 relative z-10">
            <template x-for="log in logs" :key="log.id">
                <div class="p-3.5 rounded-2xl flex items-center justify-between transition-all duration-300" 
                     :class="log.status === 'success' ? 'bg-emerald-900/30 border border-emerald-800' : (log.status === 'error' ? 'bg-red-900/30 border border-red-800' : 'bg-gray-700/50')">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm shadow-lg"
                             :class="log.status === 'success' ? 'bg-emerald-500 text-emerald-950' : (log.status === 'error' ? 'bg-red-500 text-white' : 'bg-gray-600 text-gray-300')">
                            <span x-show="log.status !== 'pending'" x-text="log.initial"></span>
                            <span x-show="log.status === 'pending'">...</span>
                        </div>
                        <div>
                            <p class="text-white font-bold text-sm tracking-tight" x-text="log.message"></p>
                            <p class="text-[10px] font-medium uppercase tracking-wider" 
                               :class="log.status === 'error' ? 'text-red-400' : 'text-emerald-400'" 
                               x-text="log.subtext"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

    </div>

    <script>
        function scannerApp() {
            return {
                sholat: '',
                queue: [], logs: [], isProcessing: false,

                init() {
                    setInterval(() => { if(this.sholat && document.activeElement !== this.$refs.scanInput) this.$refs.scanInput.focus(); }, 2000);
                    setInterval(() => { this.processQueue(); }, 400);
                },

                addQueue(code) {
                    if(!code || !this.sholat) return;
                    const id = Date.now();
                    this.logs.unshift({ id: id, status: 'pending', message: 'Memproses...', subtext: code, initial: '' });
                    this.queue.push({ code: code, logId: id });
                },

                async processQueue() {
                    if (this.isProcessing || this.queue.length === 0) return;
                    this.isProcessing = true;
                    const item = this.queue.shift();

                    try {
                        const response = await axios.post('{{ route('pengurus.absensi.jamaah.process') }}', {
                            rfid: item.code,
                            sholat: this.sholat
                        });
                        const data = response.data;
                        const index = this.logs.findIndex(l => l.id === item.logId);
                        
                        if(index !== -1) {
                            if(data.status === 'success') {
                                this.logs[index].status = 'success';
                                this.logs[index].message = data.santri;
                                this.logs[index].subtext = 'HADIR';
                                this.logs[index].initial = data.santri.charAt(0);
                            } else {
                                this.logs[index].status = 'error';
                                this.logs[index].message = data.santri || 'Gagal';
                                this.logs[index].subtext = data.message; // Pesan Haid muncul disini
                                this.logs[index].initial = '!';
                            }
                        }
                    } catch (error) { /* ... */ }
                    this.isProcessing = false;
                    if(this.queue.length > 0) this.processQueue(); 
                }
            }
        }
    </script>
</x-app-layout>