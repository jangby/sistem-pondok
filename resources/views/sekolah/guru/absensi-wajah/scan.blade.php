<x-app-layout hide-nav>
    {{-- CSS Kustom untuk Efek & Animasi --}}
    <style>
        /* Paksa video webcam agar responsive mengikuti container */
        #my_camera video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 1rem;
        }
        
        /* Animasi Garis Scanner */
        .scan-line {
            position: absolute;
            width: 100%;
            height: 4px;
            background: rgba(99, 102, 241, 0.8);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.8);
            animation: scanning 2.5s infinite linear;
            z-index: 5;
            top: 0;
        }

        @keyframes scanning {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }

        /* Hide Scrollbar tapi tetap bisa scroll */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <div class="min-h-screen bg-slate-50 font-sans text-slate-800 pb-20">
        
        {{-- 1. MODERN HEADER (Sticky & Glass Effect) --}}
        <div class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm transition-all duration-300">
            <div class="max-w-3xl mx-auto px-4 py-3 flex justify-between items-center">
                <div>
                    <h1 class="text-lg font-bold text-slate-800 tracking-tight leading-none">Absensi Wajah</h1>
                    <p class="text-xs text-slate-500 font-medium mt-1 truncate max-w-[200px]">
                        {{ $jadwal->mataPelajaran->nama_mapel ?? 'Mapel' }} &bull; {{ $jadwal->kelas->nama_kelas ?? 'Kelas' }}
                    </p>
                </div>
                {{-- Indikator Realtime --}}
                <div class="flex flex-col items-end">
                    <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-0.5">Hadir</span>
                    <span id="counter-hadir" class="bg-indigo-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-indigo-200 shadow-lg">
                        0
                    </span>
                </div>
            </div>
        </div>

        {{-- WRAPPER UTAMA --}}
        <div class="pt-20 px-4 max-w-lg mx-auto md:max-w-2xl lg:max-w-4xl space-y-6">

            {{-- 2. CAMERA SECTION (Hero Element) --}}
            <div class="relative w-full mx-auto">
                
                {{-- Container Kamera dengan Aspect Ratio 4:3 atau Square di Mobile --}}
                <div class="relative w-full aspect-[4/3] md:aspect-video bg-slate-900 rounded-2xl overflow-hidden shadow-2xl ring-4 ring-white">
                    
                    {{-- Webcam Output --}}
                    <div id="my_camera" class="w-full h-full transform scale-x-[-1]"></div>

                    {{-- Overlay: Efek Scanning (Hanya muncul saat aktif) --}}
                    <div id="scan-overlay" class="absolute inset-0 pointer-events-none hidden">
                        <div class="scan-line"></div>
                        {{-- Corner Brackets (Viewfinder) --}}
                        <div class="absolute top-4 left-4 w-8 h-8 border-t-4 border-l-4 border-white/50 rounded-tl-lg"></div>
                        <div class="absolute top-4 right-4 w-8 h-8 border-t-4 border-r-4 border-white/50 rounded-tr-lg"></div>
                        <div class="absolute bottom-4 left-4 w-8 h-8 border-b-4 border-l-4 border-white/50 rounded-bl-lg"></div>
                        <div class="absolute bottom-4 right-4 w-8 h-8 border-b-4 border-r-4 border-white/50 rounded-br-lg"></div>
                    </div>

                    {{-- Overlay: Flash Effect --}}
                    <div id="flash-effect" class="absolute inset-0 bg-white opacity-0 transition-opacity duration-200 pointer-events-none z-20"></div>

                    {{-- Overlay: Status Pill (Floating di dalam kamera) --}}
                    <div class="absolute top-4 left-0 right-0 flex justify-center z-10">
                        <div id="scan-status" class="px-4 py-1.5 rounded-full bg-black/60 backdrop-blur-md text-white text-xs font-semibold shadow-lg flex items-center gap-2 transition-all duration-300 border border-white/10">
                            <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                            <span>Menunggu kamera...</span>
                        </div>
                    </div>

                    {{-- Overlay: Start/Stop Control (Floating di bawah kamera) --}}
                    <div class="absolute bottom-4 left-0 right-0 flex justify-center z-20 px-4">
                        <button onclick="toggleScan()" id="btn-toggle" class="w-full md:w-auto md:px-8 py-3 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white rounded-xl shadow-lg shadow-rose-600/30 font-bold text-sm transition-all flex items-center justify-center gap-2 backdrop-blur-sm bg-opacity-90">
                            <i class="fas fa-stop"></i>
                            <span>Stop Scan</span>
                        </button>
                    </div>
                </div>

                <p class="text-center text-xs text-slate-400 mt-3 flex items-center justify-center gap-1">
                    <i class="fas fa-bolt text-yellow-500"></i> Auto-scan setiap 4 detik
                </p>
            </div>

            {{-- 3. LOG ACTIVITY (Scrollable List) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-700 text-sm">Riwayat Sesi Ini</h3>
                    <i class="fas fa-history text-slate-300"></i>
                </div>
                
                <div id="log-area" class="max-h-[300px] overflow-y-auto p-4 space-y-3 no-scrollbar scroll-smooth">
                    {{-- Empty State --}}
                    <div id="empty-log" class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 mb-3 text-slate-300">
                            <i class="fas fa-user-clock text-xl"></i>
                        </div>
                        <p class="text-sm text-slate-400">Belum ada siswa terdeteksi</p>
                    </div>
                    
                    {{-- Item log akan masuk di sini via JS --}}
                </div>
            </div>

        </div>
    </div>

    {{-- Script Libraries --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        const ROUTE_PROSES = "{{ route('sekolah.guru.absensi_wajah.proses') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        const JADWAL_ID = "{{ $jadwal->id }}";
        
        let intervalScan = null;
        let isProcessing = false;
        let isActive = true;
        let countHadir = 0;

        // --- 1. SETUP WEBCAM (Optimasi Mobile) ---
        Webcam.set({
            width: 640,
            height: 480,
            image_format: 'jpeg',
            jpeg_quality: 80, // Sedikit turunkan agar upload lebih cepat
            flip_horiz: true,
            constraints: {
                width: { ideal: 640 },
                height: { ideal: 480 },
                facingMode: "user", // Kamera depan
                aspectRatio: 4/3
            }
        });

        Webcam.attach('#my_camera');

        Webcam.on('live', function() {
            setStatus('ready');
            setTimeout(mulaiInterval, 500);
        });

        Webcam.on('error', function(err) {
            setStatus('error', 'Akses kamera ditolak/error');
        });

        // --- 2. LOGIKA UTAMA ---

        function mulaiInterval() {
            if(intervalScan) clearInterval(intervalScan);
            // Scan interval 4 detik
            intervalScan = setInterval(prosesScanWajah, 4000);
            setStatus('scanning');
        }

        function prosesScanWajah() {
            if (!isActive || isProcessing) return;
            
            setStatus('processing');
            isProcessing = true;

            // Flash Effect Visual
            $('#flash-effect').removeClass('opacity-0').addClass('opacity-40');
            setTimeout(() => $('#flash-effect').removeClass('opacity-40').addClass('opacity-0'), 150);

            Webcam.snap(function(data_uri) {
                $.ajax({
                    url: ROUTE_PROSES,
                    type: "POST",
                    data: {
                        _token: CSRF_TOKEN,
                        jadwal_id: JADWAL_ID,
                        image: data_uri
                    },
                    timeout: 8000, // Timeout dipercepat agar UI responsif
                    success: function(resp) {
                        isProcessing = false;
                        if(!isActive) return;

                        if(resp.status == 'success') {
                            playAudio('success');
                            tambahLog(resp.nama, 'Hadir', 'success');
                            setStatus('success', resp.nama);
                            // Kembali scanning setelah 1.5 detik
                            setTimeout(() => { if(isActive) setStatus('scanning'); }, 1500);
                        } else if(resp.status == 'info') {
                            tambahLog(resp.nama, 'Sudah Absen', 'info');
                            setStatus('info', 'Sudah Absen: ' + resp.nama);
                            setTimeout(() => { if(isActive) setStatus('scanning'); }, 1500);
                        } else {
                            // Gagal/Tidak kenal
                            setStatus('scanning'); // Langsung scan lagi tanpa delay lama
                        }
                    },
                    error: function(err) {
                        isProcessing = false;
                        console.error(err);
                        if(isActive) setStatus('scanning');
                    }
                });
            });
        }

        // --- 3. UI HELPERS ---

        function toggleScan() {
            isActive = !isActive;
            let btn = $('#btn-toggle');
            
            if(isActive) {
                mulaiInterval();
                $('#scan-overlay').removeClass('hidden');
                btn.html('<i class="fas fa-stop"></i><span>Stop Scan</span>')
                   .removeClass('bg-indigo-600 shadow-indigo-600/30').addClass('bg-rose-600 shadow-rose-600/30');
            } else {
                clearInterval(intervalScan);
                setStatus('paused');
                $('#scan-overlay').addClass('hidden');
                btn.html('<i class="fas fa-play"></i><span>Lanjut Scan</span>')
                   .removeClass('bg-rose-600 shadow-rose-600/30').addClass('bg-indigo-600 shadow-indigo-600/30');
            }
        }

        function setStatus(type, msg = '') {
            let el = $('#scan-status');
            let text = '';
            let iconColor = '';
            let dotClass = ''; // Untuk indikator warna bulat

            // Reset classes
            el.removeClass('bg-black/60 bg-green-500/90 bg-blue-500/90 bg-red-500/90 text-white');

            switch(type) {
                case 'scanning':
                    text = 'Mencari wajah...';
                    el.addClass('bg-black/60 text-white');
                    dotClass = 'bg-green-400 animate-pulse';
                    $('#scan-overlay').removeClass('hidden');
                    break;
                case 'processing':
                    text = 'Memverifikasi...';
                    el.addClass('bg-indigo-600/90 text-white');
                    dotClass = 'bg-white animate-ping';
                    break;
                case 'success':
                    text = '<i class="fas fa-check-circle"></i> ' + msg;
                    el.addClass('bg-green-500/90 text-white');
                    dotClass = 'hidden';
                    break;
                case 'info':
                    text = '<i class="fas fa-info-circle"></i> ' + msg;
                    el.addClass('bg-blue-500/90 text-white');
                    dotClass = 'hidden';
                    break;
                case 'paused':
                    text = 'Scan Dihentikan';
                    el.addClass('bg-slate-700/90 text-white');
                    dotClass = 'bg-gray-400';
                    break;
                case 'error':
                    text = msg;
                    el.addClass('bg-red-500/90 text-white');
                    dotClass = 'bg-white';
                    break;
                default:
                    text = 'Menyiapkan...';
                    el.addClass('bg-black/60 text-white');
                    dotClass = 'bg-yellow-400';
            }

            // Rebuild HTML
            let html = '';
            if(dotClass !== 'hidden') {
                html += `<div class="w-2 h-2 rounded-full ${dotClass}"></div>`;
            }
            html += `<span>${text}</span>`;
            el.html(html);
        }

        function tambahLog(nama, status, type) {
            $('#empty-log').hide();
            
            if(type === 'success') {
                countHadir++;
                $('#counter-hadir').text(countHadir);
                // Efek scale pada counter
                $('#counter-hadir').addClass('scale-125');
                setTimeout(() => $('#counter-hadir').removeClass('scale-125'), 200);
            }

            let time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            
            // Konfigurasi style berdasarkan tipe
            let styles = {
                'success': { 
                    border: 'border-l-4 border-green-500', 
                    bg: 'bg-white', 
                    text: 'text-green-600',
                    badge: 'bg-green-100 text-green-700'
                },
                'info': { 
                    border: 'border-l-4 border-blue-500', 
                    bg: 'bg-slate-50', 
                    text: 'text-blue-600',
                    badge: 'bg-blue-100 text-blue-700'
                }
            };
            
            let s = styles[type] || styles['success'];

            let itemHtml = `
                <div class="flex justify-between items-center p-3 rounded-lg shadow-sm border border-slate-100 ${s.border} ${s.bg} animate-in slide-in-from-top-2 fade-in duration-300">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0 text-slate-500 text-xs font-bold">
                            ${nama.substring(0,2).toUpperCase()}
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="font-bold text-slate-800 text-sm truncate">${nama}</span>
                            <span class="text-[10px] text-slate-400 flex items-center gap-1">
                                <i class="far fa-clock"></i> ${time}
                            </span>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-md ${s.badge}">
                        ${status}
                    </span>
                </div>
            `;
            
            $('#log-area').prepend(itemHtml);
        }

        function playAudio(type) {
            // Simple Beep using AudioContext (Tanpa file eksternal biar ringan)
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            
            const ctx = new AudioContext();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            if(type === 'success') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(1760, ctx.currentTime + 0.1);
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                osc.start();
                osc.stop(ctx.currentTime + 0.3);
            }
        }
        
        // CSS Animation Helper
        $('head').append('<style>.animate-in { animation: slideIn 0.3s ease-out forwards; } @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }</style>');
    </script>
</x-app-layout>