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

    <div class="min-h-screen bg-gray-50 pb-24">
        
        
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                
                <a href="<?php echo e(route('sekolah.guru.jadwal.show', $absensiPelajaran->jadwalPelajaran->id)); ?>" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Absensi Siswa</h1>
            </div>
        </div>

        
        <div class="px-5 -mt-16 relative z-20 space-y-5">
            
            
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 relative overflow-hidden">
                <div class="absolute right-0 top-0 w-16 h-16 bg-indigo-50 rounded-bl-full -mr-2 -mt-2 opacity-50"></div>
                
                <span class="inline-block px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-[10px] font-bold uppercase tracking-wider mb-2">
                    <?php echo e($absensiPelajaran->jadwalPelajaran->kelas->nama_kelas); ?>

                </span>
                <h3 class="text-xl font-bold text-gray-800 leading-tight">
                    <?php echo e($absensiPelajaran->jadwalPelajaran->mataPelajaran->nama_mapel); ?>

                </h3>
                <p class="text-xs text-gray-500 mt-1 font-medium flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <?php echo e($absensiPelajaran->tanggal->locale('id_ID')->isoFormat('dddd, D MMMM Y')); ?>

                </p>
            </div>

            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="text-center mb-4">
                    <p id="status-scan" class="text-sm font-bold text-gray-500 animate-pulse">
                        Siap memindai kartu...
                    </p>
                    <p class="text-[10px] text-gray-400">Tempelkan kartu RFID atau gunakan opsi kamera di bawah</p>
                </div>

                
                <div class="relative mb-4">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <input type="text" id="rfid-input" 
                           class="block w-full pl-10 pr-3 py-3 border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-colors bg-gray-50 focus:bg-white" 
                           placeholder="Klik di sini untuk input manual/RFID" autofocus>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    
                    <button type="button" id="btn-qr-scan" 
                            class="flex items-center justify-center gap-2 py-3 bg-gray-800 text-white rounded-xl font-bold text-sm hover:bg-gray-700 active:scale-[0.98] transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4c1 0 2-1 2-2v-4c0-1-1-2-2-2h-4c-1 0-2 1-2 2v4c0 1 1 2 2 2zm0 0c-1 0-2 1-2 2v4c0 1 1 2 2 2h4c1 0 2-1 2-2v-4c0-1-1-2-2-2h-4z"></path></svg>
                        <span>Scan QR Code</span>
                    </button>

                    
                    <a href="<?php echo e(route('sekolah.guru.absensi_wajah.scan', $absensiPelajaran->jadwalPelajaran->id)); ?>" 
                       class="flex items-center justify-center gap-2 py-3 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 active:scale-[0.98] transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Scan Wajah</span>
                    </a>
                </div>

                
                <div id="qr-reader" class="w-full rounded-xl overflow-hidden hidden mt-4 border-2 border-emerald-500"></div>
            </div>

            
            <div>
                <div class="flex justify-between items-end mb-3 px-1">
                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Daftar Santri</h4>
                    <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-bold">
                        Total: <?php echo e(count($santriList)); ?>

                    </span>
                </div>

                <div id="santri-list-container" class="space-y-2">
                    <?php $__empty_1 = true; $__currentLoopData = $santriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div id="santri-<?php echo e($item['santri_id']); ?>" 
                             class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl shadow-sm transition-all duration-300
                             <?php echo e($item['status_absensi'] == 'hadir' ? 'border-l-4 border-l-green-500 bg-green-50/50' : ''); ?>

                             <?php echo e(($item['status_absensi'] == 'sakit' || $item['status_absensi'] == 'izin') ? 'opacity-75 bg-gray-50' : ''); ?>">
                            
                            <div class="flex items-center gap-3">
                                
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold shrink-0
                                    <?php echo e($item['status_absensi'] == 'hadir' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500'); ?>">
                                    <?php echo e(substr($item['full_name'], 0, 2)); ?>

                                </div>

                                <div>
                                    <p class="text-sm font-bold text-gray-800 line-clamp-1"><?php echo e($item['full_name']); ?></p>
                                    <p class="text-[10px] text-gray-400 font-mono"><?php echo e($item['nis']); ?></p>
                                </div>
                            </div>

                            <div class="flex-shrink-0">
                                <?php if($item['status_absensi'] == 'hadir'): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-green-100 text-green-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        HADIR
                                    </span>
                                <?php elseif($item['status_absensi'] == 'sakit'): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-yellow-100 text-yellow-700">
                                        SAKIT
                                    </span>
                                <?php elseif($item['status_absensi'] == 'izin'): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-blue-100 text-blue-700">
                                        IZIN
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-gray-100 text-gray-400">
                                        BELUM
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-8 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                            <p class="text-gray-400 text-xs">Tidak ada data santri.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        // --- KONFIGURASI ---
        const ABSENSI_PELAJARAN_ID = <?php echo e($absensiPelajaran->id); ?>;
        const STORE_URL = "<?php echo e(route('sekolah.guru.absensi.siswa.store')); ?>";
        const CSRF_TOKEN = "<?php echo e(csrf_token()); ?>";

        // --- ELEMEN DOM ---
        const rfidInput = document.getElementById('rfid-input');
        const btnQrScan = document.getElementById('btn-qr-scan');
        const qrReaderElement = document.getElementById('qr-reader');
        const statusScan = document.getElementById('status-scan');
        
        // --- SISTEM ANTRIAN (QUEUE) ---
        let scanQueue = [];
        let isProcessing = false;
        let scannedCodes = new Set(); 
        let isScanning = false; // Status kamera QR
        let html5QrCode = null;

        // ============================================================
        // 1. LOGIKA DETEKSI "ENTER" DARI ALAT RFID/BARCODE
        // ============================================================
        rfidInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault(); 
                const kodeKartu = rfidInput.value.trim();
                if (kodeKartu) {
                    tambahKeAntrian(kodeKartu);
                }
            }
        });

        // ============================================================
        // 2. AUTO-FOCUS AGRESIF (PENTING UNTUK ALAT USB)
        // ============================================================
        document.addEventListener('click', (e) => {
            const target = e.target;
            const isInteractive = target.closest('button') || target.closest('a') || target.closest('input');
            
            if (!isInteractive && !isScanning) {
                rfidInput.focus();
            }
        });

        window.onload = () => rfidInput.focus();

        // ============================================================
        // 3. FUNGSI ANTRIAN & PEMROSESAN
        // ============================================================
        function tambahKeAntrian(kartuId) {
            if (scannedCodes.has(kartuId)) {
                updateStatusUI('Kartu ini sudah absen.', 'text-orange-500');
                rfidInput.value = ''; 
                return;
            }
            
            scannedCodes.add(kartuId);
            scanQueue.push(kartuId);

            updateStatusUI(`Memproses antrian (${scanQueue.length})...`, 'text-blue-600 font-bold animate-pulse');

            rfidInput.value = '';
            rfidInput.focus();

            if (!isProcessing) {
                prosesAntrian();
            }
        }

        async function prosesAntrian() {
            if (scanQueue.length === 0) {
                isProcessing = false;
                updateStatusUI('Siap memindai kartu...', 'text-gray-500');
                return;
            }

            isProcessing = true;
            const kartuId = scanQueue[0]; 

            try {
                const response = await fetch(STORE_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        absensi_pelajaran_id: ABSENSI_PELAJARAN_ID,
                        kartu_id: kartuId
                    })
                });

                const data = await response.json();

                if (!response.ok) throw new Error(data.message || 'Gagal');

                // SUKSES
                updateTampilanBarisSantri(data.santri_id);
                tampilkanToastSuccess(data.nama_santri);
                
                scanQueue.shift(); 

            } catch (error) {
                console.error('Gagal:', error);
                
                scanQueue.shift(); 
                scannedCodes.delete(kartuId); 

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Gagal Scan',
                    text: error.message,
                    showConfirmButton: false,
                    timer: 2000
                });
            }

            prosesAntrian();
        }

        // ============================================================
        // 4. FUNGSI HELPER UI
        // ============================================================
        function updateStatusUI(text, className) {
            statusScan.textContent = text;
            statusScan.className = 'text-center text-sm mb-2 ' + className;
        }

        function updateTampilanBarisSantri(santriId) {
            const row = document.getElementById(`santri-${santriId}`);
            if (row) {
                row.className = 'flex items-center justify-between p-3 bg-green-100 border border-green-300 rounded-xl shadow-sm transition-all duration-500 transform scale-105';
                
                setTimeout(() => {
                    row.classList.remove('scale-105');
                    row.classList.add('bg-green-50'); 
                }, 300);

                const avatar = row.querySelector('.rounded-full');
                if(avatar) avatar.className = 'w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold shrink-0 bg-green-600 text-white';

                const badgeDiv = row.querySelector('.flex-shrink-0');
                badgeDiv.innerHTML = `
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-green-600 text-white shadow-sm">
                        HADIR
                    </span>
                `;
            }
        }

        function tampilkanToastSuccess(nama) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            })
            Toast.fire({ icon: 'success', title: 'Hadir: ' + nama })
        }

        // ============================================================
        // 5. LOGIKA QR CODE
        // ============================================================
        btnQrScan.addEventListener('click', () => {
            if (isScanning) stopQrScan();
            else startQrScan();
        });

        function startQrScan() {
            html5QrCode = new Html5Qrcode("qr-reader");
            qrReaderElement.classList.remove('hidden');
            btnQrScan.textContent = 'Tutup Kamera';
            btnQrScan.className = 'w-full flex items-center justify-center gap-2 py-3 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 shadow-lg';
            
            isScanning = true;
            rfidInput.disabled = true;

            html5QrCode.start(
                { facingMode: "environment" }, 
                { fps: 10, qrbox: { width: 250, height: 250 } }, 
                (decodedText) => {
                    tambahKeAntrian(decodedText);
                }
            ).catch(err => {
                Swal.fire('Error', 'Gagal akses kamera', 'error');
                stopQrScan();
            });
        }

        function stopQrScan() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    qrReaderElement.classList.add('hidden');
                    btnQrScan.textContent = 'Scan QR Code';
                    btnQrScan.className = 'w-full flex items-center justify-center gap-2 py-3 bg-gray-800 text-white rounded-xl font-bold text-sm hover:bg-gray-700 active:scale-[0.98] transition-all shadow-lg shadow-gray-200';
                    
                    isScanning = false;
                    rfidInput.disabled = false;
                    rfidInput.focus();
                });
            }
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/guru/absensi-siswa/index.blade.php ENDPATH**/ ?>