<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24 font-sans">
        {{-- Header --}}
        <div class="bg-emerald-600 pt-8 pb-16 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden">
            <div class="absolute -top-10 -right-16 w-48 h-48 bg-emerald-500 opacity-30 rounded-full"></div>
            <div class="relative z-10 flex justify-between items-center mt-2">
                <div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">Data Petugas</h1>
                    <p class="text-emerald-100 text-xs mt-1">Kelola akun akses scan perpulangan</p>
                </div>
                <a href="{{ route('pengurus.perpulangan.index') }}" class="bg-white/20 backdrop-blur-md p-2 rounded-xl text-white hover:bg-white/30 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            </div>
        </div>

        <div class="px-5 -mt-10 relative z-20">
            {{-- Tombol Tambah --}}
            <a href="{{ route('pengurus.perpulangan.petugas.create') }}" class="bg-white rounded-2xl p-4 shadow-lg shadow-emerald-900/10 border border-emerald-100 flex items-center gap-4 mb-6 hover:shadow-xl transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Tambah Petugas</h3>
                    <p class="text-xs text-gray-500">Buat akun akses baru</p>
                </div>
            </a>

            <div class="space-y-3">
                @forelse($petugas as $p)
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold">
                            {{ substr($p->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">{{ $p->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $p->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('pengurus.perpulangan.petugas.edit', $p->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                        <form action="{{ route('pengurus.perpulangan.petugas.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus petugas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-gray-400">
                    <p class="text-sm">Belum ada data petugas.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>