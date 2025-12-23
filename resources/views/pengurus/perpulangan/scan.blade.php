<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <audio id="audio-success" src="https://assets.mixkit.co/active_storage/sfx/2578/2578-preview.mp3"></audio>
    <audio id="audio-error" src="https://assets.mixkit.co/active_storage/sfx/2572/2572-preview.mp3"></audio>

    <div class="min-h-screen bg-gray-900 font-sans relative overflow-hidden flex flex-col">
        
        <div class="absolute top-0 w-full z-20 p-4 flex justify-between items-center bg-gradient-to-b from-black/80 to-transparent">
            <a href="{{ route('pengurus.perpulangan.index') }}" class="text-white/80 hover:text-white p-2 bg-black/20 backdrop-blur rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-white font-bold text-lg tracking-wide">Scanner Gerbang</h1>
            <div class="w-10"></div> </div>

        <div class="flex-1 relative bg-black flex flex-col justify-center">
            <div id="reader" class="w-full h-full object-cover"></div>
            
            <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                <div class="w-64 h-64 border-2 border-emerald-500/50 rounded-3xl relative">
                    <div class="absolute top-0 left-0 w-10 h-10 border-t-4 border-l-4 border-emerald-400 rounded-tl-3xl -mt-1 -ml-1"></div>
                    <div class="absolute top-0 right-0 w-10 h-10 border-t-4 border-r-4 border-emerald-400 rounded-tr-3xl -mt-1 -mr-1"></div>
                    <div class="absolute bottom-0 left-0 w-10 h-10 border-b-4 border-l-4 border-emerald-400 rounded-bl-3xl -mb-1 -ml-1"></div>
                    <div class="absolute bottom-0 right-0 w-10 h-10 border-b-4 border-r-4 border-emerald-400 rounded-br-3xl -mb-1 -mr-1"></div>
                    
                    <div class="absolute w-full h-1 bg-emerald-500/80 shadow-[0_0_15px_rgba(16,185,129,0.8)] animate-scan"></div>
                </div>
                <p class="absolute mt-80 text-white/70 text-sm font-medium bg-black/30 px-4 py-1 rounded-full backdrop-blur-sm">
                    Arahkan QR Code ke dalam kotak
                </p>
            </div>
        </div>

        <div class="bg-white rounded-t-[30px] p-6 pb-10 relative z-30 min-h-[30vh] shadow-[0_-10px_40px_rgba(0,0,0,0.3)] transition-all duration-300" id="bottom-panel">
            
            <div class="w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-6"></div>

            <div class="flex bg-gray-100 p-1.5 rounded-2xl mb-6 relative">
                <button onclick="setMode('keluar')" id="btn-keluar" class="flex-1 py-3 text-sm font-bold rounded-xl transition-all duration-200 text-center relative z-10 text-white bg-rose-500 shadow-lg shadow-rose-500/30">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        SCAN KELUAR
                    </span>
                </button>
                <button onclick="setMode('masuk')" id="btn-masuk" class="flex-1 py-3 text-sm font-bold rounded-xl transition-all duration-200 text-center relative z-10 text-gray-500 hover:text-gray-700">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        SCAN MASUK
                    </span>
                </button>
            </div>

            <div id="result-container" class="hidden animate-fade-in-up">
                <div id="result-card" class="bg-gradient-to-br from-emerald-50 to-white border border-emerald-100 rounded-2xl p-5 flex items-center gap-4 shadow-sm relative overflow-hidden">
                    <div id="status-icon" class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>

                    <div class="flex-1 min-w-0 z-10">
                        <p id="result-msg" class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-0.5">Berhasil</p>
                        <h3 id="result-name" class="text-lg font-black text-gray-800 leading-tight truncate">Nama Santri</h3>
                        <p id="result-detail" class="text-xs text-gray-500 mt-1">Kelas 1A | Asrama A</p>
                    </div>
                    
                    <div class="absolute top-4 right-4 text-[10px] font-mono text-gray-400 bg-white/50 px-2 py-1 rounded-md" id="result-time">
                        12:00
                    </div>
                </div>
            </div>

            <div id="error-container" class="hidden animate-shake">
                <div class="bg-red-50 border border-red-100 rounded-2xl p-4 flex items-center gap-3 text-red-700">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p id="error-msg" class="text-sm font-bold">Error message here</p>
                </div>
            </div>

            <div id="loading" class="hidden text-center py-4">
                <svg class="animate-spin h-8 w-8 text-emerald-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-xs text-gray-400 mt-2">Memproses data...</p>
            </div>

        </div>
    </div>

    <style>
        @keyframes scan {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        .animate-scan {
            animation: scan 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.4s ease-out forwards;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .animate-shake {
            animation: shake 0.3s ease-in-out;
        }
    </style>

    <script>
        let currentMode = 'keluar';
        let isProcessing = false;
        let html5QrcodeScanner = null;
        let lastScannedToken = null; // Mencegah scan berulang instan

        // Init Audio
        const audioSuccess = document.getElementById('audio-success');
        const audioError = document.getElementById('audio-error');

        // 1. SETUP SCANNER
        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing || decodedText === lastScannedToken) return;

            processScan(decodedText);
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
        }

        // Start Camera
        html5QrcodeScanner = new Html5Qrcode("reader");
        html5QrcodeScanner.start(
            { facingMode: "environment" }, 
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            console.error("Gagal memulai kamera", err);
            alert("Tidak dapat mengakses kamera. Pastikan izin diberikan.");
        });


        // 2. LOGIC SWITCH MODE
        function setMode(mode) {
            currentMode = mode;
            const btnKeluar = document.getElementById('btn-keluar');
            const btnMasuk = document.getElementById('btn-masuk');

            if (mode === 'keluar') {
                btnKeluar.className = "flex-1 py-3 text-sm font-bold rounded-xl transition-all duration-200 text-center relative z-10 text-white bg-rose-500 shadow-lg shadow-rose-500/30";
                btnMasuk.className = "flex-1 py-3 text-sm font-bold rounded-xl transition-all duration-200 text-center relative z-10 text-gray-500 hover:text-gray-700";
            } else {
                btnKeluar.className = "flex-1 py-3 text-sm font-bold rounded-xl transition-all duration-200 text-center relative z-10 text-gray-500 hover:text-gray-700";
                btnMasuk.className = "flex-1 py-3 text-sm font-bold rounded-xl transition-all duration-200 text-center relative z-10 text-white bg-emerald-600 shadow-lg shadow-emerald-600/30";
            }
            
            // Clear previous result
            resetUI();
        }

        // 3. PROCESS DATA (AJAX)
        async function processScan(token) {
            isProcessing = true;
            lastScannedToken = token;
            
            // UI Loading
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('result-container').classList.add('hidden');
            document.getElementById('error-container').classList.add('hidden');

            try {
                const response = await fetch("{{ route('pengurus.perpulangan.scan.process') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        qr_token: token,
                        mode: currentMode
                    })
                });

                const data = await response.json();

                document.getElementById('loading').classList.add('hidden');

                if (response.ok && data.status === 'success') {
                    showSuccess(data);
                } else {
                    showError(data.message || 'Terjadi kesalahan sistem');
                }

            } catch (error) {
                console.error(error);
                document.getElementById('loading').classList.add('hidden');
                showError("Gagal menghubungi server");
            }

            // Cooldown sebelum bisa scan lagi (2.5 detik)
            setTimeout(() => {
                isProcessing = false;
                lastScannedToken = null;
            }, 2500);
        }

        // 4. UI FEEDBACK HELPERS
        function showSuccess(data) {
            // Play Sound
            audioSuccess.currentTime = 0;
            audioSuccess.play();

            // Populate Data
            document.getElementById('result-name').innerText = data.data.nama;
            document.getElementById('result-detail').innerText = `${data.data.kelas} | ${data.data.asrama}`;
            document.getElementById('result-time').innerText = data.data.waktu;
            document.getElementById('result-msg').innerText = data.message;
            
            // Warna Card Berdasarkan Terlambat/Tidak
            const card = document.getElementById('result-card');
            const icon = document.getElementById('status-icon');
            
            if(data.data.is_late) {
                // Style Merah/Kuning jika terlambat
                card.className = "bg-gradient-to-br from-orange-50 to-white border border-orange-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm relative overflow-hidden";
                icon.className = "w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0 text-orange-600";
                icon.innerHTML = `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            } else {
                // Style Normal Hijau
                card.className = "bg-gradient-to-br from-emerald-50 to-white border border-emerald-100 rounded-2xl p-5 flex items-center gap-4 shadow-sm relative overflow-hidden";
                icon.className = "w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600";
                icon.innerHTML = `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
            }

            document.getElementById('result-container').classList.remove('hidden');
        }

        function showError(message) {
            // Play Sound
            audioError.currentTime = 0;
            audioError.play();

            document.getElementById('error-msg').innerText = message;
            document.getElementById('error-container').classList.remove('hidden');
        }

        function resetUI() {
            document.getElementById('result-container').classList.add('hidden');
            document.getElementById('error-container').classList.add('hidden');
            document.getElementById('loading').classList.add('hidden');
        }

    </script>
</x-app-layout>