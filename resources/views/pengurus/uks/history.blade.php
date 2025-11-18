<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    {{-- PERUBAHAN 1: Ganti bg-gray-50 menjadi bg-gray-100 agar konsisten dengan dashboard baru --}}
    <div class="min-h-screen bg-gray-100 pb-24">
        
        {{-- Header --}}
        <div class="bg-white px-6 py-4 flex items-center gap-4 border-b border-gray-100 sticky top-0 z-30 shadow-sm">
            <a href="{{ route('pengurus.uks.index') }}" class="text-gray-500 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-lg font-bold text-gray-800">Riwayat Lengkap</h1>
        </div>

        {{-- Filter --}}
        <div class="p-4 bg-white border-b border-gray-100">
            <form method="GET" action="{{ route('pengurus.uks.history') }}" class="flex gap-2">
                <input type="date" name="date" value="{{ request('date') }}" class="w-1/3 rounded-xl border-gray-200 text-xs focus:ring-red-500 focus:border-red-500">
                <div class="relative w-2/3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama..." class="w-full pl-9 pr-3 py-2.5 rounded-xl border-gray-200 text-xs focus:ring-red-500 focus:border-red-500">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                
                {{-- PERUBAHAN 2: Tombol filter diubah warnanya agar sesuai tema UKS (Merah) --}}
                <button type="submit" class="bg-red-600 text-white p-2.5 rounded-xl transition active:scale-95 shadow-sm hover:bg-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>

        {{-- PERUBAHAN 3: List diberi padding px-5 dan py-6 agar konten widget terpisah dari tepi --}}
        <div class="px-5 py-6 space-y-3">
            @forelse($riwayat as $data)
                {{-- PERUBAHAN 4: Menyiapkan info warna dan label berdasarkan status --}}
                @php
                    $statusInfo = [
                        'sembuh' => ['color' => 'green', 'label' => 'Sembuh'],
                        'rujuk_rs' => ['color' => 'red', 'label' => 'Rujuk RS'],
                        'dirawat_di_asrama' => ['color' => 'orange', 'label' => 'Rawat Asrama'],
                        'sakit_ringan' => ['color' => 'yellow', 'label' => 'Sakit Ringan'],
                    ];
                    $info = $statusInfo[$data->status] ?? ['color' => 'gray', 'label' => ucfirst(str_replace('_', ' ', $data->status))];
                @endphp

                {{-- PERUBAHAN 5: Card diberi border kiri berwarna sesuai status --}}
                <a href="{{ route('pengurus.uks.show', $data->id) }}" 
                   class="block bg-white p-4 rounded-2xl shadow-md shadow-gray-900/5 border-l-4 border-{{ $info['color'] }}-500 border-y border-r border-gray-100/80 active:scale-[0.98] transition duration-150 group hover:border-{{ $info['color'] }}-500/30">
                    
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-gray-800 text-sm group-hover:text-red-600 transition">{{ $data->santri->full_name }}</h3>
                        
                        {{-- Badge Status menggunakan info warna --}}
                        <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase bg-{{ $info['color'] }}-100 text-{{ $info['color'] }}-700 border border-{{ $info['color'] }}-200/50">
                            {{ $info['label'] }}
                        </span>
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-1 truncate">{{ $data->keluhan }}</p>
                    
                    <div class="mt-2 pt-2 border-t border-gray-50 flex justify-between text-[10px] text-gray-400">
                        <span>{{ $data->created_at->format('d M Y') }}</span>
                        <span>{{ $data->created_at->format('H:i') }} WIB</span>
                    </div>
                </a>
            @empty
                {{-- PERUBAHAN 6: Empty state dibungkus dalam card 'widget' --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
                    <div class="inline-flex bg-gray-50 p-3 rounded-full mb-3 border border-gray-100">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Data Tidak Ditemukan</p>
                    <p class="text-xs text-gray-400 mt-1">Coba ubah filter pencarian Anda.</p>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $riwayat->links('pagination::tailwind') }}
            </div>
        </div>
    </div>

    @include('layouts.pengurus-nav')
</x-app-layout>