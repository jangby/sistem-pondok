<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-900 flex flex-col p-5 font-sans relative overflow-hidden" x-data="scannerApp()">
        
        {{-- Background Effects --}}
        <div class="absolute top-[-10%] right-[-10%] w-64 h-64 bg-orange-500/10 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-64 h-64 bg-emerald-900/30 rounded-full blur-[80px]"></div>

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6 relative z-10">
            <div>
                <h1 class="text-white font-bold text-xl tracking-tight">Scan Kegiatan</h1>
                <p class="text-orange-400/60 text-xs font-mono tracking-widest uppercase">Mode Input Cepat</p>
            </div>
            <a href="{{ route('pengurus.absensi.kegiatan') }}" class="bg-gray-800/50 backdrop-blur-sm px-4 py-2 rounded-xl text-gray-400 text-xs font-bold border border-gray-700 hover:bg-gray-800 hover:text-white transition">
                Selesai
            </a>
        </div>

        {{-- PILIH KEGIATAN --}}
        <div class="mb-6 relative z-10">
            <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">Pilih Kegiatan Aktif</label>
            <div class="relative">
                <select x-model="kegiatanId" class="w-full bg-gray-800 border border-gray-600 text-white rounded-2xl p-4 appearance-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition font-bold text-sm">
                    <option value="">-- Pilih Kegiatan Dulu --</option>
                    @foreach($kegiatans as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kegiatan }} ({{ \Carbon\Carbon::parse($k->jam_mulai)->format('H:i') }})</option>
                    @endforeach
                </select>
                <div class="absolute right-4 top-4 text-gray-400 pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>

        {{-- INPUT SCANNER --}}
        <div class="mb-4 relative group z-10">
            {{-- Glow Effect --}}
            <div class="absolute -inset-0.5 bg-gradient-to-r from-orange-600 to-red-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-500" x-show="kegiatanId"></div>
            
            <input type="text" x-ref="scanInput" 
                :disabled="!kegiatanId"
                @keydown.enter="addQueue($el.value); $el.value = ''" 
                class="relative w-full bg-gray-900 border-2 text-white text-center rounded-2xl py-5 text-xl tracking-[0.2em] font-mono font-bold outline-none transition-all shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed"
                :class="kegiatanId ? 'border-orange-500/50 focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20' : 'border-gray-700'"
                placeholder="TAP KARTU..." autofocus autocomplete="off">

            {{-- Status Dot --}}
            <div class="absolute right-4 top-6" x-show="kegiatanId">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                </span>
            </div>
        </div>

        {{-- INFO ANTRIAN --}}
        <div class="flex justify-between text-gray-500 text-[10px] uppercase font-bold mb-3 px-1 tracking-wider relative z-10">
            <span>Antrian: <span x-text="queue.length" class="text-white ml-1"></span></span>
            <span>Sukses: <span x-text="successCount" class="text-orange-400 ml-1"></span></span>
        </div>

        {{-- LOG SCAN --}}
        <div class="flex-1 bg-gray-800/40 backdrop-blur-xl rounded-[2rem] p-3 overflow-y-auto space-y-2 border border-gray-700/50 relative z-10 shadow-inner">
            
            <div x-show="logs.length === 0" class="absolute inset-0 flex flex-col items-center justify-center text-gray-600 opacity-50">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M19 19h-5m-9-6h5a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zM6 21v-2a1 1 0 011-1h10V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16z"></path></svg>
                <p class="text-xs font-bold tracking-widest uppercase">Menunggu Scan...</p>
            </div>

            <template x-for="log in logs" :key="log.id">
                <div class="p-3.5 rounded-2xl flex items-center justify-between transition-all duration-300" 
                     :class="log.status === 'success' ? 'bg-green-900/30 border border-green-800' : (log.status === 'error' ? 'bg-red-900/30 border border-red-800' : 'bg-gray-700/50 border border-gray-700')">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm shadow-lg"
                             :class="log.status === 'success' ? 'bg-green-500 text-black' : (log.status === 'error' ? 'bg-red-500 text-white' : 'bg-gray-600 text-gray-300')">
                            <span x-show="log.status === 'pending'" class="animate-spin">C</span>
                            <span x-show="log.status !== 'pending'" x-text="log.initial"></span>
                        </div>
                        <div>
                            <p class="text-white font-bold text-sm tracking-tight" x-text="log.message"></p>
                            <p class="text-[10px] font-medium uppercase tracking-wider" :class="log.status === 'error' ? 'text-red-400' : 'text-gray-400'" x-text="log.subtext"></p>
                        </div>
                    </div>
                    <span class="text-[10px] font-mono text-gray-500" x-text="log.time"></span>
                </div>
            </template>
        </div>
    </div>

    <script>
        function scannerApp() {
            return {
                kegiatanId: '',
                queue: [], logs: [], isProcessing: false, successCount: 0,

                init() {
                    // Auto focus hanya jika kegiatan sudah dipilih
                    setInterval(() => {
                        if(this.kegiatanId && document.activeElement !== this.$refs.scanInput) {
                            this.$refs.scanInput.focus();
                        }
                    }, 2000);
                    // Proses antrian
                    setInterval(() => { this.processQueue(); }, 400);
                },

                addQueue(code) {
                    if(!code) return;
                    if(!this.kegiatanId) {
                        alert('Pilih Kegiatan Terlebih Dahulu!');
                        return;
                    }
                    const id = Date.now();
                    this.logs.unshift({
                        id: id, status: 'pending', message: 'Memproses...', subtext: code,
                        time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}), initial: ''
                    });
                    this.queue.push({ code: code, logId: id });
                },

                async processQueue() {
                    if (this.isProcessing || this.queue.length === 0) return;
                    this.isProcessing = true;
                    const item = this.queue.shift();

                    try {
                        const response = await axios.post('{{ route('pengurus.absensi.kegiatan.process') }}', {
                            rfid: item.code,
                            kegiatan_id: this.kegiatanId
                        });
                        const data = response.data;
                        const index = this.logs.findIndex(l => l.id === item.logId);
                        
                        if(index !== -1) {
                            if(data.status === 'success') {
                                this.logs[index].status = 'success';
                                this.logs[index].message = data.santri;
                                this.logs[index].subtext = 'HADIR';
                                this.logs[index].initial = data.santri.charAt(0);
                                this.successCount++;
                            } else {
                                this.logs[index].status = 'error';
                                this.logs[index].message = data.santri || 'Gagal';
                                this.logs[index].subtext = data.message;
                                this.logs[index].initial = '!';
                            }
                        }
                    } catch (error) {
                        const index = this.logs.findIndex(l => l.id === item.logId);
                        if(index !== -1) {
                            this.logs[index].status = 'error';
                            this.logs[index].message = 'Error';
                            this.logs[index].subtext = 'Gagal Terhubung';
                            this.logs[index].initial = 'X';
                        }
                    }
                    this.isProcessing = false;
                    if(this.queue.length > 0) this.processQueue(); 
                }
            }
        }
    </script>
</x-app-layout>