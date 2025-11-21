@extends('layouts.kios')

@section('content')
{{-- Latar Belakang Animasi --}}
<div class="relative min-h-screen w-full overflow-hidden bg-slate-900 flex flex-col items-center justify-between py-10 px-4 font-sans selection:bg-indigo-500 selection:text-white">
    
    {{-- 1. BACKGROUND FX --}}
    <div class="absolute inset-0 z-0">
        {{-- Gradien Bergerak --}}
        <div class="absolute top-0 -left-4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-blob animation-delay-4000"></div>
        
        {{-- Grid Pattern Halus --}}
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
    </div>

    {{-- 2. HEADER: Info Sekolah & Jam --}}
    <div class="relative z-10 w-full max-w-7xl flex justify-between items-start">
        <div class="flex items-center gap-4">
            {{-- Logo Placeholder (Bisa diganti img) --}}
            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h1 class="text-white font-black text-2xl tracking-tight">KIOS ABSENSI</h1>
                <p class="text-indigo-300 font-medium text-sm tracking-widest uppercase">Mode Layar Penuh</p>
            </div>
        </div>

        <div class="text-right">
            <div id="clock-time" class="text-5xl font-black text-white font-mono tracking-tighter leading-none drop-shadow-lg">00:00</div>
            <div id="clock-date" class="text-indigo-200 font-bold text-sm uppercase tracking-widest mt-1">SENIN, 1 JANUARI 2025</div>
        </div>
    </div>

    {{-- 3. MAIN CONTENT: KODE --}}
    <div class="relative z-10 flex-1 flex flex-col items-center justify-center w-full max-w-5xl">
        
        {{-- Instruksi --}}
        <div class="mb-8 px-6 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm text-indigo-200 text-sm font-bold uppercase tracking-widest shadow-lg">
            Scan atau Masukkan Kode di Aplikasi
        </div>

        {{-- KOTAK KODE --}}
        <div class="relative group cursor-default">
            {{-- Glow Effect di Belakang Kode --}}
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-cyan-500 rounded-3xl blur opacity-30 group-hover:opacity-60 transition duration-1000"></div>
            
            <div class="relative bg-slate-900/80 backdrop-blur-xl border border-white/10 rounded-3xl p-12 md:p-20 text-center shadow-2xl">
                
                {{-- KODE DISPLAY --}}
                <div id="kode-display" 
                     class="font-mono font-black text-transparent bg-clip-text bg-gradient-to-b from-white to-slate-400 tracking-[0.15em] select-all transition-all duration-300"
                     style="font-size: clamp(5rem, 18vw, 12rem); line-height: 0.9; text-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    LOADING
                </div>

            </div>
        </div>

        {{-- Status Koneksi (Optional) --}}
        <div id="status-indicator" class="mt-8 flex items-center gap-2 opacity-0 transition-opacity duration-500">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
            </span>
            <span class="text-red-400 text-sm font-bold">Koneksi Terputus</span>
        </div>

    </div>

    {{-- 4. FOOTER: TIMER BAR --}}
    <div class="relative z-10 w-full max-w-3xl mb-10">
        <div class="flex justify-between items-end mb-3 px-1">
            <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">
                Kode Berubah Dalam
            </span>
            <span id="countdown-text" class="text-4xl font-black text-white font-mono tabular-nums">
                60
            </span>
        </div>
        
        {{-- Progress Bar Container --}}
        <div class="w-full bg-slate-800/50 rounded-full h-3 p-0.5 backdrop-blur-sm border border-white/5 shadow-inner">
            {{-- Bar Fill --}}
            <div id="countdown-bar" 
                 class="h-full rounded-full bg-gradient-to-r from-indigo-500 via-purple-500 to-cyan-500 shadow-[0_0_15px_rgba(99,102,241,0.6)] transition-all duration-1000 ease-linear relative overflow-hidden" 
                 style="width: 100%">
                 {{-- Shine Effect on Bar --}}
                 <div class="absolute top-0 left-0 bottom-0 right-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.3)_50%,transparent_75%)] bg-[length:250%_250%,100%_100%] animate-shine"></div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    /* Font Digital untuk Jam (Optional: gunakan font Google seperti 'JetBrains Mono' atau 'Orbitron' di layout utama) */
    
    /* Animasi Background Blob */
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }

    /* Animasi Shine pada Bar */
    @keyframes shine {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    .animate-shine {
        animation: shine 3s infinite linear;
    }
</style>
@endpush

@push('scripts')
<script>
    /* --- 1. LOGIKA JAM & TANGGAL --- */
    function updateClock() {
        const now = new Date();
        // Jam
        const timeString = now.toLocaleTimeString('id-ID', { 
            hour12: false, hour: '2-digit', minute: '2-digit' 
        }); // Detik opsional, dihapus agar lebih bersih atau tambah jika perlu
        document.getElementById('clock-time').innerText = timeString;
        
        // Tanggal
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateString = now.toLocaleDateString('id-ID', dateOptions);
        document.getElementById('clock-date').innerText = dateString.toUpperCase();
    }
    setInterval(updateClock, 1000);
    updateClock();


    /* --- 2. LOGIKA KODE ABSEN --- */
    const kodeElement = document.getElementById('kode-display');
    const barElement = document.getElementById('countdown-bar');
    const textElement = document.getElementById('countdown-text');
    const statusIndicator = document.getElementById('status-indicator');
    const fetchUrl = "{{ route('sekolah.admin.konfigurasi.kios.new_kode') }}"; // Pastikan route ini mengembalikan JSON {kode: '123456'}

    let timer = 60; 
    const maxTime = 60; // Durasi refresh (detik)

    async function fetchNewCode() {
        // Efek visual saat loading (Blur sedikit)
        kodeElement.style.opacity = '0.5';
        kodeElement.style.transform = 'scale(0.95)';
        
        try {
            const response = await fetch(fetchUrl);
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            
            // Update Kode dengan Transisi
            setTimeout(() => {
                kodeElement.textContent = data.kode; 
                kodeElement.style.opacity = '1';
                kodeElement.style.transform = 'scale(1)';
                
                // Reset Status
                statusIndicator.style.opacity = '0';
                kodeElement.classList.remove('text-red-500');
                kodeElement.classList.add('text-transparent', 'bg-clip-text', 'bg-gradient-to-b', 'from-white', 'to-slate-400');
            }, 200);
            
            // Reset Timer
            timer = maxTime; 
            
        } catch (error) {
            console.error('Fetch error:', error);
            kodeElement.textContent = "OFFLINE";
            
            // Tampilkan Indikator Error
            statusIndicator.style.opacity = '1';
            
            // Ubah warna teks jadi merah
            kodeElement.classList.remove('text-transparent', 'bg-clip-text', 'bg-gradient-to-b', 'from-white', 'to-slate-400');
            kodeElement.classList.add('text-red-500');

            // Coba lagi dalam 5 detik
            setTimeout(fetchNewCode, 5000); 
        }
    }

    function updateCountdown() {
        timer--;
        
        // Update Lebar Bar
        const percentage = (timer / maxTime) * 100;
        barElement.style.width = percentage + '%';
        
        // Update Teks Angka
        textElement.textContent = timer;

        // Logika Warna Bar (Merah jika < 10 detik)
        if (timer < 10) {
            barElement.classList.remove('from-indigo-500', 'via-purple-500', 'to-cyan-500');
            barElement.classList.add('from-red-500', 'via-orange-500', 'to-yellow-500');
            
            textElement.classList.add('text-red-500');
            textElement.classList.remove('text-white');
        } else {
            barElement.classList.add('from-indigo-500', 'via-purple-500', 'to-cyan-500');
            barElement.classList.remove('from-red-500', 'via-orange-500', 'to-yellow-500');
            
            textElement.classList.remove('text-red-500');
            textElement.classList.add('text-white');
        }

        // Refresh jika waktu habis
        if (timer <= 0) {
            fetchNewCode();
        }
    }

    // Inisialisasi
    fetchNewCode();
    setInterval(updateCountdown, 1000);
</script>
@endpush