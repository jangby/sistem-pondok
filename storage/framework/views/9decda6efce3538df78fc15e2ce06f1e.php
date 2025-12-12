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
    <div class="min-h-screen bg-gray-50/50 font-sans pb-24">

        
        <div class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-gray-100 transition-all duration-300">
            <div class="max-w-3xl mx-auto px-5 py-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Kontrol Komputer</h1>
                        <p class="text-xs text-gray-500 font-medium">Lab Komputer Utama</p>
                    </div>
                    <div class="text-xs font-bold px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100 shadow-sm">
                        <?php echo e($computers->count()); ?> Unit
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="searchPc" onkeyup="filterComputers()" 
                        class="block w-full pl-10 pr-4 py-3 bg-gray-100/50 border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all shadow-sm placeholder-gray-400" 
                        placeholder="Cari nomor PC (misal: 01)...">
                </div>
            </div>
        </div>

        
        <div class="max-w-3xl mx-auto px-4 mt-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="pcListContainer">
                <?php $__empty_1 = true; $__currentLoopData = $computers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Logika sederhana: Online jika last_seen < 2 menit yang lalu
                        $isOnline = $pc->last_seen >= now()->subMinutes(2);
                        // Warna status
                        $statusColor = $isOnline ? 'bg-green-500' : 'bg-gray-300';
                        $statusText = $isOnline ? 'text-green-600' : 'text-gray-400';
                        $cardBorder = $isOnline ? 'border-green-200 shadow-green-100' : 'border-gray-100';
                    ?>

                    <div onclick="openModalDetail('<?php echo e($pc->pc_name); ?>', '<?php echo e($pc->password); ?>', '<?php echo e($isOnline); ?>', '<?php echo e($pc->ip_address); ?>')" 
                         class="pc-item relative bg-white p-5 rounded-2xl shadow-sm border <?php echo e($cardBorder); ?> hover:shadow-md active:scale-[0.98] transition-all cursor-pointer group overflow-hidden">
                        
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 <?php echo e($statusColor); ?>"></div>

                        <div class="flex justify-between items-center pl-2">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl <?php echo e($isOnline ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-400'); ?> flex items-center justify-center text-xl shadow-inner">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="pc-name text-lg font-bold text-gray-800 tracking-tight"><?php echo e($pc->pc_name); ?></h3>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="relative flex h-2 w-2">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full <?php echo e($statusColor); ?> opacity-75 <?php echo e($isOnline ? '' : 'hidden'); ?>"></span>
                                          <span class="relative inline-flex rounded-full h-2 w-2 <?php echo e($statusColor); ?>"></span>
                                        </span>
                                        <p class="text-xs font-semibold <?php echo e($statusText); ?>">
                                            <?php echo e($isOnline ? 'Online' : 'Offline'); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Pass</span>
                                <div class="font-mono text-xl font-black text-gray-700 tracking-widest">
                                    <?php echo e($pc->password ?? '--'); ?>

                                </div>
                            </div>
                        </div>

                        <?php if($pc->pending_command): ?>
                        <div class="absolute top-2 right-2">
                            <span class="flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-gray-900 font-medium">Belum ada data komputer</h3>
                        <p class="text-gray-500 text-sm mt-1">Silakan tambahkan data di database.</p>
                    </div>
                <?php endif; ?>
                
                <div id="noResult" class="hidden col-span-full text-center py-10">
                    <p class="text-gray-500 font-medium">Komputer tidak ditemukan.</p>
                </div>
            </div>
        </div>
    </div>

    
    <div id="pcModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalDetail()"></div>

        <div class="fixed inset-x-0 bottom-0 z-50 w-full bg-white rounded-t-[32px] shadow-2xl transform transition-all sm:max-w-md sm:mx-auto sm:rounded-2xl sm:bottom-auto sm:top-1/2 sm:-translate-y-1/2 overflow-hidden">
            
            <div class="h-24 bg-gradient-to-r from-indigo-600 to-blue-600 relative">
                <div class="absolute -bottom-10 left-1/2 transform -translate-x-1/2">
                    <div class="w-20 h-20 bg-white rounded-full p-1.5 shadow-lg">
                        <div class="w-full h-full bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600">
                             <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                </div>
                <button onclick="closeModalDetail()" class="absolute top-4 right-4 text-white/80 hover:text-white bg-white/10 rounded-full p-1">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="pt-12 pb-8 px-6 text-center">
                <h3 class="text-2xl font-black text-gray-900 tracking-tight" id="modalPcName">PC-00</h3>
                <p class="text-sm font-medium mt-1" id="modalStatus">Checking...</p>

                <div class="mt-6 mb-6 bg-gray-50 rounded-2xl p-5 border border-gray-100 relative group cursor-pointer" onclick="printTicket()">
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">PASSWORD LOGIN</p>
                    <div class="flex items-center justify-center gap-3">
                        <h2 class="text-4xl font-black text-gray-800 tracking-[0.2em] font-mono" id="modalPassword">----</h2>
                    </div>
                    <div class="mt-3 flex items-center justify-center text-indigo-600 text-xs font-bold uppercase tracking-wide gap-1 group-hover:scale-105 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Klik Untuk Cetak
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button class="flex flex-col items-center justify-center p-3 rounded-xl bg-orange-50 text-orange-600 border border-orange-100 hover:bg-orange-100 transition-colors">
                        <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span class="text-xs font-bold">Restart</span>
                    </button>
                    <button class="flex flex-col items-center justify-center p-3 rounded-xl bg-red-50 text-red-600 border border-red-100 hover:bg-red-100 transition-colors">
                        <svg class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        <span class="text-xs font-bold">Shutdown</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        // --- SEARCH FUNCTION ---
        function filterComputers() {
            let input = document.getElementById('searchPc').value.toUpperCase();
            let container = document.getElementById('pcListContainer');
            let items = container.getElementsByClassName('pc-item');
            let found = 0;

            for (let i = 0; i < items.length; i++) {
                let name = items[i].getElementsByClassName("pc-name")[0].innerText;
                if (name.toUpperCase().indexOf(input) > -1) {
                    items[i].style.display = ""; // Reset display
                    found++;
                } else {
                    items[i].style.display = "none";
                }
            }
            // Toggle No Result message
            document.getElementById("noResult").style.display = (found === 0) ? "block" : "none";
        }

        // --- MODAL LOGIC ---
        function openModalDetail(name, password, isOnline, ip) {
            const modal = document.getElementById('pcModal');
            
            // Set Data
            document.getElementById('modalPcName').innerText = name;
            document.getElementById('modalPassword').innerText = password;
            
            // Status Styling
            let statusEl = document.getElementById('modalStatus');
            if (isOnline == '1') {
                statusEl.innerHTML = `<span class="text-green-600 flex items-center justify-center gap-1">● Online <span class="text-gray-400 font-normal">(${ip})</span></span>`;
            } else {
                statusEl.innerHTML = `<span class="text-gray-400">● Offline</span>`;
            }

            modal.classList.remove('hidden');
        }

        function closeModalDetail() {
            document.getElementById('pcModal').classList.add('hidden');
        }

        // --- FITUR CETAK THERMAL REAL (Tanpa Android Interface) ---
        function printTicket() {
            let pcName = document.getElementById('modalPcName').innerText;
            let password = document.getElementById('modalPassword').innerText;
            
            // Desain Struk Thermal (Lebar 58mm / 80mm friendly)
            const ticketContent = `
            <html>
            <head>
                <title>PRINT-${pcName}</title>
                <style>
                    @page { margin: 0; size: 80mm auto; } /* Atur ukuran kertas thermal */
                    body { 
                        font-family: 'Courier New', monospace; 
                        margin: 0; 
                        padding: 10px; 
                        text-align: center;
                        background: #fff;
                    }
                    .header { 
                        font-size: 16px; 
                        font-weight: bold; 
                        border-bottom: 2px dashed #000; 
                        padding-bottom: 10px;
                        margin-bottom: 10px;
                    }
                    .pc-large { 
                        font-size: 24px; 
                        font-weight: bold; 
                        margin: 5px 0;
                    }
                    .box-pass {
                        border: 2px solid #000;
                        padding: 10px;
                        margin: 10px 0;
                        border-radius: 5px;
                    }
                    .pass-label { font-size: 12px; text-transform: uppercase; }
                    .pass-value { font-size: 32px; font-weight: bold; letter-spacing: 2px; }
                    .footer { font-size: 10px; margin-top: 15px; }
                </style>
            </head>
            <body>
                <div class="header">
                    LAB KOMPUTER<br>
                    TIKET LOGIN
                </div>
                
                <div class="pc-large">${pcName}</div>
                
                <div class="box-pass">
                    <div class="pass-label">PASSWORD</div>
                    <div class="pass-value">${password}</div>
                </div>

                <div class="footer">
                    Gunakan password di atas<br>untuk login ke komputer ini.<br>
                    ${new Date().toLocaleString()}
                </div>

                <script>
                    window.onload = function() { 
                        window.print(); 
                        setTimeout(function(){ window.close(); }, 1000);
                    }
                <\/script>
            </body>
            </html>
            `;

            // Buka Popup untuk Print
            const printWindow = window.open('', '_blank', 'height=600,width=400');
            printWindow.document.write(ticketContent);
            printWindow.document.close();
        }
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/petugas/lab-komputer/list.blade.php ENDPATH**/ ?>