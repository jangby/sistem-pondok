<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24 font-sans">
        <div class="bg-emerald-600 pt-8 pb-16 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden">
            <div class="absolute -top-10 -right-16 w-48 h-48 bg-emerald-500 opacity-30 rounded-full"></div>
            <div class="relative z-10 flex justify-between items-center mt-2">
                <div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">Manajemen Perpulangan</h1>
                    <p class="text-emerald-100 text-xs mt-1">Atur jadwal liburan dan kartu santri</p>
                </div>
                <a href="{{ route('pengurus.dashboard') }}" class="bg-white/20 backdrop-blur-md p-2 rounded-xl text-white hover:bg-white/30 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
            </div>
        </div>

        <div class="px-5 -mt-10 relative z-20">
            
            {{-- Update grid menjadi 3 kolom agar muat 3 tombol, atau biarkan 2 tapi responsif --}}
            <div class="grid grid-cols-3 gap-3 mb-6">
                
                <a href="{{ route('pengurus.perpulangan.scan') }}" class="bg-white rounded-2xl p-3 shadow-lg shadow-emerald-900/10 border border-emerald-100 flex flex-col items-center justify-center gap-2 group active:scale-95 transition relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-6 h-6 bg-emerald-50 rounded-bl-full -mr-2 -mt-2"></div>
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition duration-300 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <span class="font-bold text-gray-800 text-[10px] text-center leading-tight">Buka Scanner</span>
                </a>

                <a href="{{ route('pengurus.perpulangan.create') }}" class="bg-white rounded-2xl p-3 shadow-lg shadow-emerald-900/10 border border-emerald-100 flex flex-col items-center justify-center gap-2 group active:scale-95 transition">
                     <div class="w-10 h-10 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition duration-300 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <span class="font-bold text-gray-800 text-[10px] text-center leading-tight">Jadwal Baru</span>
                </a>

                {{-- TOMBOL BARU: KELOLA PETUGAS --}}
                <a href="{{ route('pengurus.perpulangan.petugas.index') }}" class="bg-white rounded-2xl p-3 shadow-lg shadow-emerald-900/10 border border-emerald-100 flex flex-col items-center justify-center gap-2 group active:scale-95 transition">
                    <div class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition duration-300 shadow-sm">
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                   </div>
                   <span class="font-bold text-gray-800 text-[10px] text-center leading-tight">Kelola Petugas</span>
               </a>

            </div>

            <div class="flex items-center justify-between mb-3 px-1">
                <h3 class="font-bold text-gray-800 text-base">Daftar Agenda</h3>
                <span class="text-[10px] bg-white border border-gray-200 px-2 py-1 rounded-full text-gray-500">Terbaru</span>
            </div>
            
            <div class="space-y-4 pb-10">
                @forelse($events as $event)
<a href="{{ route('pengurus.perpulangan.show', $event->id) }}" class="block">
    <div class="bg-white rounded-2xl shadow-sm border {{ $event->is_active ? 'border-emerald-200 ring-1 ring-emerald-100' : 'border-gray-100' }} overflow-hidden relative group hover:shadow-md transition">
        
        <object class="absolute top-4 right-4 z-20">
            <form action="{{ route('pengurus.perpulangan.toggle-status', $event->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" onchange="this.form.submit()" class="sr-only peer" {{ $event->is_active ? 'checked' : '' }}>
                    
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                    
                    <span class="ml-2 text-[10px] font-bold uppercase tracking-wider {{ $event->is_active ? 'text-emerald-600' : 'text-gray-400' }}">
                        {{ $event->is_active ? 'ON' : 'OFF' }}
                    </span>
                </label>
            </form>
        </object>

        <div class="p-5">
            @if($event->is_active)
                <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
            @endif

            <h4 class="font-bold text-gray-800 text-lg leading-tight mb-1 group-hover:text-emerald-600 transition pr-16">
                {{ $event->judul }}
            </h4>
            
            <div class="flex items-center text-xs text-gray-500 mb-4 gap-2">
                <svg class="w-4 h-4 {{ $event->is_active ? 'text-emerald-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>{{ $event->tanggal_mulai->format('d M Y') }} s/d {{ $event->tanggal_akhir->format('d M Y') }}</span>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-4 border-t border-gray-100 pt-4 relative z-10">
                <object class="w-full">
                    <a href="{{ route('pengurus.perpulangan.pilih-santri', $event->id) }}" class="flex items-center justify-center gap-2 py-2.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold hover:bg-emerald-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .667.333 1 1 1v1m0 0v2m0-2h3m0 0v-2"></path></svg>
                        Cetak Kartu
                    </a>
                </object>
                
                <object class="w-full">
                    <form action="{{ route('pengurus.perpulangan.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Hapus agenda ini? Data scan santri juga akan terhapus!');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-red-50 text-red-600 text-xs font-bold hover:bg-red-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus
                        </button>
                    </form>
                </object>
            </div>
        </div>
    </div>
</a>
@empty
    <div class="text-center py-10 text-gray-400 bg-white rounded-2xl border border-dashed border-gray-300">
        <p class="text-sm">Belum ada jadwal perpulangan.</p>
    </div>
@endforelse
            </div>
        </div>
    </div>
</x-app-layout>