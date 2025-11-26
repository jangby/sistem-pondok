<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>
    <div class="min-h-screen bg-gray-50 pb-20 font-sans">
        
        {{-- HEADER UNGU --}}
        <div class="bg-purple-600 px-6 pt-8 pb-10 rounded-b-[30px] shadow-lg">
            <div class="flex items-center gap-4">
                <a href="{{ route('sekolah.petugas.dashboard') }}" class="text-white hover:bg-white/20 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-xl font-bold text-white">Buku Tamu</h1>
            </div>
        </div>

        {{-- FORM INPUT CEPAT --}}
        <div class="px-6 -mt-6">
            <div class="bg-white p-5 rounded-2xl shadow-lg border border-gray-100">
                <form action="{{ route('sekolah.petugas.kunjungan.store') }}" method="POST" class="flex gap-2">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="nama" class="w-full text-sm rounded-xl border-gray-200 bg-gray-50" placeholder="Nama / ID Pengunjung">
                    </div>
                    <button type="submit" class="bg-purple-600 text-white px-4 rounded-xl font-bold shadow-md hover:bg-purple-700">
                        Masuk
                    </button>
                </form>
            </div>
        </div>

        {{-- LIST HARI INI --}}
        <div class="px-6 mt-6">
            <h3 class="font-bold text-gray-700 mb-3">Hari Ini ({{ count($kunjungan) }})</h3>
            <div class="space-y-3">
                @foreach($kunjungan as $tamu)
                    <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                        <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-xs">
                            {{ substr($tamu->nama ?? 'G', 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">{{ $tamu->nama ?? 'Guest' }}</h4>
                            <p class="text-[10px] text-gray-500">{{ $tamu->created_at->format('H:i') }} WIB • {{ $tamu->keperluan ?? 'Baca Buku' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>