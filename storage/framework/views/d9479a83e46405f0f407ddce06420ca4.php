<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hide-nav' => true]); ?>
     <?php $__env->slot('header', null, []); ?>  <?php $__env->endSlot(); ?>

    <div class="min-h-screen bg-gray-900 flex flex-col p-5 font-sans relative overflow-hidden" x-data="scannerApp()">
        
        
        <div class="absolute top-[-10%] left-[-10%] w-64 h-64 bg-emerald-500/20 rounded-full blur-[80px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-64 h-64 bg-emerald-900/30 rounded-full blur-[80px]"></div>

        
        <div class="flex justify-between items-center mb-8 relative z-10">
            <div>
                <h1 class="text-white font-bold text-xl tracking-tight">Scanner Asrama</h1>
                <p class="text-emerald-400/60 text-xs font-mono tracking-widest uppercase">Mode Input Cepat</p>
            </div>
            <a href="<?php echo e(route('pengurus.absensi.asrama')); ?>" class="bg-gray-800/50 backdrop-blur-sm px-4 py-2 rounded-xl text-gray-400 text-xs font-bold border border-gray-700 hover:bg-gray-800 hover:text-white transition">
                Selesai
            </a>
        </div>

        
        <div class="mb-8 relative group z-10">
            
            <div class="absolute -inset-1 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
            
            <input type="text" x-ref="scanInput" @keydown.enter="addQueue($el.value); $el.value = ''" 
                class="relative w-full bg-gray-900 border-2 border-gray-700 group-hover:border-emerald-500/50 text-white text-center rounded-2xl py-5 text-xl tracking-[0.2em] font-mono font-bold focus:ring-4 focus:ring-emerald-500/20 outline-none placeholder-gray-700 transition-all shadow-2xl" 
                placeholder="TAP KARTU..." autofocus autocomplete="off">
            
            
            <div class="absolute right-4 top-6">
                <div class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </div>
            </div>
        </div>

        
        <div class="flex justify-between items-center text-gray-500 text-[10px] uppercase font-bold mb-3 px-1 tracking-wider relative z-10">
            <span>Antrian Proses: <span x-text="queue.length" class="text-white ml-1"></span></span>
            <span>Sukses: <span x-text="successCount" class="text-emerald-400 ml-1"></span></span>
        </div>

        
        <div class="flex-1 bg-gray-800/40 backdrop-blur-xl rounded-[2rem] p-3 overflow-y-auto space-y-2 border border-gray-700/50 relative z-10 shadow-inner">
            
            
            <div x-show="logs.length === 0" class="absolute inset-0 flex flex-col items-center justify-center text-gray-600 opacity-50">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M19 19h-5m-9-6h5a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zM6 21v-2a1 1 0 011-1h10V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16z"></path></svg>
                <p class="text-xs font-bold tracking-widest uppercase">Menunggu Scan Kartu</p>
            </div>

            
            <template x-for="log in logs" :key="log.id">
                <div class="p-3.5 rounded-2xl flex items-center justify-between transition-all duration-300" 
                     :class="log.status === 'success' ? 'bg-emerald-500/10 border border-emerald-500/20' : (log.status === 'error' ? 'bg-red-500/10 border border-red-500/20' : 'bg-gray-700/50 border border-gray-700')">
                    
                    <div class="flex items-center gap-3">
                        
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm shadow-lg"
                             :class="log.status === 'success' ? 'bg-emerald-500 text-emerald-950' : (log.status === 'error' ? 'bg-red-500 text-white' : 'bg-gray-600 text-gray-300')">
                            <span x-show="log.status === 'pending'" class="animate-spin">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </span>
                            <span x-show="log.status !== 'pending'" x-text="log.initial"></span>
                        </div>
                        
                        <div>
                            <p class="text-white font-bold text-sm tracking-tight" x-text="log.message"></p>
                            <p class="text-[10px] font-medium uppercase tracking-wider" 
                               :class="log.status === 'error' ? 'text-red-400' : (log.status === 'success' ? 'text-emerald-400' : 'text-gray-400')" 
                               x-text="log.subtext"></p>
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
                queue: [],
                logs: [],
                isProcessing: false,
                successCount: 0,

                init() {
                    setInterval(() => {
                        if(document.activeElement !== this.$refs.scanInput) {
                            this.$refs.scanInput.focus();
                        }
                    }, 2000);

                    setInterval(() => {
                        this.processQueue();
                    }, 400);
                },

                addQueue(code) {
                    if(!code) return;
                    const id = Date.now();
                    this.logs.unshift({
                        id: id,
                        status: 'pending',
                        message: 'Memproses...',
                        subtext: code,
                        time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}),
                        initial: ''
                    });
                    this.queue.push({ code: code, logId: id });
                },

                async processQueue() {
                    if (this.isProcessing || this.queue.length === 0) return;

                    this.isProcessing = true;
                    const item = this.queue.shift();

                    try {
                        const response = await axios.post('<?php echo e(route('pengurus.absensi.asrama.process')); ?>', {
                            rfid: item.code
                        });

                        const data = response.data;
                        const index = this.logs.findIndex(l => l.id === item.logId);
                        
                        if(index !== -1) {
                            if(data.status === 'success') {
                                this.logs[index].status = 'success';
                                this.logs[index].message = data.santri;
                                this.logs[index].subtext = 'BERHASIL';
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
                            this.logs[index].message = 'Error Sistem';
                            this.logs[index].subtext = 'Cek Koneksi';
                            this.logs[index].initial = 'X';
                        }
                    }

                    this.isProcessing = false;
                    if(this.queue.length > 0) this.processQueue(); 
                }
            }
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/absensi/asrama/scan.blade.php ENDPATH**/ ?>