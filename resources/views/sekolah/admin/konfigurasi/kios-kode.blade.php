@extends('layouts.kios')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen w-full overflow-hidden px-4">
    
    {{-- HEADER: Kecil saja agar fokus ke kode --}}
    <div class="mb-2 text-center z-10">
        <h1 class="text-2xl md:text-4xl font-bold text-gray-600 tracking-[0.2em] uppercase">
            KODE ABSENSI
        </h1>
    </div>
    
    {{-- AREA KODE: SANGAT BESAR --}}
    <div class="relative z-20 flex-1 flex items-center justify-center w-full">
        {{-- Efek Glow/Cahaya Latar Belakang --}}
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-[50vw] h-[50vw] bg-blue-600/20 rounded-full blur-[100px] opacity-40 pointer-events-none animate-pulse"></div>
        
        {{-- 
            DISPLAY KODE
            Menggunakan font-size dinamis (clamp):
            - Minimal: 6rem (di HP)
            - Ideal: 25vw (25% dari lebar layar)
            - Maksimal: 22rem (sangat besar di monitor)
        --}}
        <div id="kode-display" 
             class="font-mono font-black text-white tracking-widest text-center select-all drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]"
             style="font-size: clamp(6rem, 25vw, 22rem); line-height: 1;">
             ...
        </div>
    </div>

    {{-- PETUNJUK --}}
    <p class="text-gray-400 text-lg md:text-2xl font-medium mb-10 z-10">
        Masukkan kode di atas pada HP Anda
    </p>

    {{-- PROGRESS BAR: Lebar Penuh di Bawah --}}
    <div class="w-full max-w-5xl mb-8 z-10">
        <div class="flex justify-between items-end mb-2 px-2">
            <span class="text-sm md:text-base text-gray-500 font-bold font-mono uppercase tracking-wider">
                Refresh Otomatis
            </span>
            <span id="countdown-text" class="text-3xl md:text-4xl font-black text-blue-500 font-mono">
                60
            </span>
        </div>
        
        {{-- Bar Background --}}
        <div class="w-full bg-gray-800 rounded-full h-4 md:h-6 overflow-hidden shadow-inner border border-gray-700">
            {{-- Bar Fill --}}
            <div id="countdown-bar" 
                 class="bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-400 h-full rounded-full transition-all duration-1000 ease-linear shadow-[0_0_20px_rgba(59,130,246,0.6)]" 
                 style="width: 100%">
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const kodeElement = document.getElementById('kode-display');
    const barElement = document.getElementById('countdown-bar');
    const textElement = document.getElementById('countdown-text');
    const fetchUrl = "{{ route('sekolah.admin.konfigurasi.kios.new_kode') }}";

    let timer = 60; 

    async function fetchNewCode() {
        // Efek visual saat loading
        kodeElement.style.opacity = '0.5';
        kodeElement.style.filter = 'blur(4px)';
        
        try {
            const response = await fetch(fetchUrl);
            if (!response.ok) throw new Error('Gagal mengambil kode');
            const data = await response.json();
            
            // Update Kode
            kodeElement.textContent = data.kode; 
            
            // Reset Timer
            timer = 60; 
            
            // Kembalikan visual normal
            kodeElement.style.opacity = '1';
            kodeElement.style.filter = 'none';
            kodeElement.classList.remove('text-red-500');
            
        } catch (error) {
            kodeElement.textContent = "OFFLINE";
            kodeElement.classList.add('text-red-500'); // Warna merah jika error
            console.error(error);
            setTimeout(fetchNewCode, 5000); 
        }
    }

    function updateCountdown() {
        timer--;
        
        // Update Lebar Bar
        const percentage = (timer / 60) * 100;
        barElement.style.width = percentage + '%';
        
        // Update Teks Angka
        textElement.textContent = timer;

        // Logika Warna Bar (Merah jika waktu < 10 detik)
        if (timer < 10) {
            barElement.classList.remove('from-blue-600', 'via-blue-500', 'to-cyan-400');
            barElement.classList.add('from-red-600', 'via-red-500', 'to-orange-500');
            textElement.classList.add('text-red-500');
            textElement.classList.remove('text-blue-500');
        } else {
            barElement.classList.add('from-blue-600', 'via-blue-500', 'to-cyan-400');
            barElement.classList.remove('from-red-600', 'via-red-500', 'to-orange-500');
            textElement.classList.remove('text-red-500');
            textElement.classList.add('text-blue-500');
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