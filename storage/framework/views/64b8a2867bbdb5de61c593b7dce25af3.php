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
        
        
        <div class="flex justify-between items-center mb-6 relative z-10">
            <div>
                <h1 class="text-white font-bold text-xl tracking-tight">Absen Ketua Asrama</h1>
                <p class="text-purple-400/60 text-xs font-mono tracking-widest uppercase">Mode Cepat</p>
            </div>
            <a href="<?php echo e(route('pengurus.asrama.index')); ?>" class="bg-gray-800/50 backdrop-blur-sm px-4 py-2 rounded-xl text-gray-400 text-xs font-bold border border-gray-700 hover:bg-gray-800 hover:text-white transition">
                Selesai
            </a>
        </div>

        
        <div class="grid grid-cols-2 gap-3 mb-6 relative z-10">
            <a href="<?php echo e(route('pengurus.asrama.ketua.rekap')); ?>" class="bg-gray-800 p-3 rounded-2xl border border-gray-700 text-center active:scale-95 transition hover:border-purple-500 group">
                <span class="block text-white font-bold text-sm group-hover:text-purple-400">Laporan</span>
            </a>
            <a href="<?php echo e(route('pengurus.asrama.ketua.settings')); ?>" class="bg-gray-800 p-3 rounded-2xl border border-gray-700 text-center active:scale-95 transition hover:border-purple-500 group">
                <span class="block text-white font-bold text-sm group-hover:text-purple-400">Pengaturan</span>
            </a>
        </div>

        
        <div class="mb-6 relative group z-10">
            <input type="text" x-ref="scanInput" @keydown.enter="addQueue($el.value); $el.value = ''" 
                class="relative w-full bg-gray-900 border-2 border-purple-500 text-white text-center rounded-2xl py-5 text-xl tracking-[0.2em] font-mono font-bold focus:ring-4 focus:ring-purple-900 outline-none placeholder-gray-600" 
                placeholder="TAP KARTU..." autofocus autocomplete="off">
        </div>

        
        <div class="flex-1 bg-gray-800/40 backdrop-blur-xl rounded-[2rem] p-3 overflow-y-auto space-y-2 border border-gray-700/50 relative z-10">
            <div x-show="logs.length === 0" class="absolute inset-0 flex flex-col items-center justify-center text-gray-600 opacity-50">
                <p class="text-xs font-bold tracking-widest uppercase">Menunggu Scan...</p>
            </div>
            <template x-for="log in logs" :key="log.id">
                <div class="p-3.5 rounded-2xl flex items-center justify-between transition-all duration-300" 
                     :class="log.status === 'success' ? 'bg-green-900/30 border border-green-800' : (log.status === 'error' ? 'bg-red-900/30 border border-red-800' : 'bg-gray-700')">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm shadow-lg"
                             :class="log.status === 'success' ? 'bg-green-500 text-black' : (log.status === 'error' ? 'bg-red-500 text-white' : 'bg-gray-600 text-white')">
                            <span x-show="log.status !== 'pending'" x-text="log.initial"></span>
                            <span x-show="log.status === 'pending'">...</span>
                        </div>
                        <div>
                            <p class="text-white font-bold text-sm" x-text="log.message"></p>
                            <p class="text-[10px]" :class="log.status === 'error' ? 'text-red-400' : 'text-green-400'" x-text="log.subtext"></p>
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
                queue: [], logs: [], isProcessing: false,
                init() {
                    setInterval(() => { if(document.activeElement !== this.$refs.scanInput) this.$refs.scanInput.focus(); }, 2000);
                    setInterval(() => { this.processQueue(); }, 400);
                },
                addQueue(code) {
                    if(!code) return;
                    const id = Date.now();
                    this.logs.unshift({ id: id, status: 'pending', message: 'Memproses...', subtext: code, time: new Date().toLocaleTimeString(), initial: '' });
                    this.queue.push({ code: code, logId: id });
                },
                async processQueue() {
                    if (this.isProcessing || this.queue.length === 0) return;
                    this.isProcessing = true;
                    const item = this.queue.shift();
                    try {
                        const response = await axios.post('<?php echo e(route('pengurus.asrama.ketua.process')); ?>', { rfid: item.code });
                        const data = response.data;
                        const index = this.logs.findIndex(l => l.id === item.logId);
                        if(index !== -1) {
                            this.logs[index].status = data.status === 'success' ? 'success' : 'error';
                            this.logs[index].message = data.santri || 'Gagal';
                            this.logs[index].subtext = data.message;
                            this.logs[index].initial = data.status === 'success' ? data.santri.charAt(0) : '!';
                        }
                    } catch (error) { /* Handle Error */ }
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/asrama/absensi_ketua/index.blade.php ENDPATH**/ ?>