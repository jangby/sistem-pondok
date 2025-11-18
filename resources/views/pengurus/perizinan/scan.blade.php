<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-900 flex flex-col items-center justify-center p-6 relative overflow-hidden">
        
        {{-- Dekorasi --}}
        <div class="absolute top-0 left-0 w-full h-1/2 bg-gradient-to-b from-gray-800 to-transparent opacity-50"></div>

        {{-- Tombol Close --}}
        <a href="{{ route('pengurus.uks.index') }}" class="absolute top-6 right-6 text-gray-400 hover:text-white z-50">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </a>

        <div class="w-full max-w-md relative z-10 text-center">
            
            {{-- Animasi Scanner --}}
            <div class="mb-8 relative inline-block">
                <div class="w-40 h-40 rounded-3xl border-4 border-red-500 flex items-center justify-center bg-gray-800 shadow-[0_0_50px_rgba(239,68,68,0.5)] relative overflow-hidden">
                    <svg class="w-20 h-20 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M19 19h-5m-9-6h5a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zM6 21v-2a1 1 0 011-1h10V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16z"></path></svg>
                    
                    {{-- Garis Scan Bergerak --}}
                    <div class="absolute top-0 left-0 w-full h-1 bg-red-400 shadow-[0_0_20px_rgba(248,113,113,1)] animate-scan"></div>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-white mb-2">Scan Kartu Santri</h2>
            <p class="text-gray-400 mb-8 text-sm">Tempelkan kartu RFID atau Scan QR Code untuk mencatat izin.</p>

            {{-- FORM INPUT SCAN (Hidden/Auto-Focus) --}}
            <form action="{{ route('pengurus.perizinan.process') }}" method="POST" class="w-full">
                @csrf
                <div class="relative">
                    <input type="text" name="kode_kartu" id="kode_kartu" 
                        class="w-full bg-gray-800 border-2 border-gray-700 text-white text-center rounded-xl py-4 focus:ring-2 focus:ring-red-500 focus:border-red-500 placeholder-gray-600 font-mono tracking-widest" 
                        placeholder="Klik disini lalu tap kartu..." autofocus autocomplete="off">
                    
                    <div class="absolute right-4 top-4 text-gray-500">
                        <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                </div>
                
                <button type="submit" class="mt-6 w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-red-600/30 transition active:scale-95">
                    Proses Data
                </button>
            </form>

        </div>
    </div>

    {{-- Auto Focus Script --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('kode_kartu');
            input.focus();
            
            // Opsional: Klik dimanapun akan fokus ke input
            document.addEventListener('click', function() {
                input.focus();
            });
        });
    </script>
    @endpush

    <style>
        @keyframes scan {
            0%, 100% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        .animate-scan {
            animation: scan 2s linear infinite;
        }
    </style>
</x-app-layout>