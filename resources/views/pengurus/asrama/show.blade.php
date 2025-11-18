<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-28" x-data="{ showAddMember: false }">
        
        {{-- HEADER DETAIL --}}
        <div class="bg-emerald-600 pt-8 pb-24 px-6 rounded-b-[35px] shadow-lg relative z-10">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-xl font-bold text-white">{{ $asrama->nama_asrama }}</h1>
                    <p class="text-emerald-100 text-xs">{{ $asrama->komplek }} • {{ $asrama->penghuni->count() }}/{{ $asrama->kapasitas }} Anggota</p>
                </div>
                
                {{-- GROUP TOMBOL HEADER --}}
                <div class="flex gap-2">
                    
                    {{-- 1. TOMBOL SETTING (GEAR) --}}
                    <a href="{{ route('pengurus.asrama.settings', $asrama->id) }}" class="bg-white/20 p-2 rounded-xl text-white hover:bg-white/30 backdrop-blur-md transition active:scale-95 shadow-sm border border-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </a>
                    
                    {{-- 2. TOMBOL BACK (PANAH KIRI) --}}
                    <a href="{{ route('pengurus.asrama.list', $asrama->jenis_kelamin == 'Laki-laki' ? 'Putra' : 'Putri') }}" class="bg-white/20 p-2 rounded-xl text-white hover:bg-white/30 backdrop-blur-md transition active:scale-95 shadow-sm border border-white/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>

                </div>
            </div>
        </div>

        {{-- KETUA ASRAMA CARD --}}
        <div class="px-5 -mt-12 relative z-20 mb-6">
            <div class="bg-white p-4 rounded-2xl shadow-md border border-gray-100 flex items-center gap-4 backdrop-blur-xl">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-xl shrink-0 ring-4 ring-white">
                    {{ substr($asrama->ketua_asrama, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Ketua Asrama</p>
                    <p class="font-bold text-gray-800 text-lg leading-none mt-1">{{ $asrama->ketua_asrama }}</p>
                </div>
            </div>
        </div>

        {{-- LIST ANGGOTA --}}
        <div class="px-5">
            <h3 class="font-bold text-gray-800 mb-4 flex justify-between items-center">
                <span>Daftar Penghuni <span class="text-xs text-gray-400 font-normal">({{ $asrama->penghuni->count() }})</span></span>
                
                <button @click="showAddMember = true" class="text-xs bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg font-bold hover:bg-emerald-200 transition flex items-center gap-1 shadow-sm active:scale-95" 
                    {{ $asrama->sisa_kapasitas <= 0 ? 'disabled style=opacity:0.5' : '' }}>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah
                </button>
            </h3>

            <div class="space-y-3">
                @forelse($asrama->penghuni as $s)
                    <div class="bg-white p-3 rounded-xl border border-gray-100 flex justify-between items-center shadow-sm hover:border-emerald-200 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center font-bold text-gray-600 text-xs">
                                {{ substr($s->full_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $s->full_name }}</p>
                                <p class="text-[10px] text-gray-500">{{ $s->nis }} • {{ $s->kelas->nama_kelas ?? '-' }}</p>
                            </div>
                        </div>
                        <form action="{{ route('pengurus.asrama.member.remove', $s->id) }}" method="POST" onsubmit="return confirm('Keluarkan santri ini dari asrama?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-50 p-2 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-100 transition active:scale-90">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400 text-sm bg-white rounded-xl border border-dashed border-gray-200">
                        Asrama ini masih kosong.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- MODAL ADD MEMBER (Slide Up) --}}
        <div x-show="showAddMember" class="fixed inset-0 z-[60] flex items-end justify-center sm:items-center" style="display: none;">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showAddMember = false"></div>
            
            <div class="bg-white w-full max-w-md rounded-t-[30px] relative z-10 p-6 shadow-2xl h-[80vh] flex flex-col transform transition-transform"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
                
                <div class="w-full flex justify-center pt-0 pb-4 cursor-pointer" @click="showAddMember = false">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>

                <h3 class="font-bold text-lg mb-4 text-gray-800">Pilih Santri (Tanpa Asrama)</h3>
                
                <div class="overflow-y-auto flex-1 p-5 space-y-2 custom-scrollbar bg-gray-50 rounded-xl border border-gray-100">
                    @forelse($calonAnggota as $calon)
                        <form action="{{ route('pengurus.asrama.member.add', $asrama->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="santri_id" value="{{ $calon->id }}">
                            <button type="submit" class="w-full flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 hover:bg-emerald-50 hover:border-emerald-200 transition text-left group shadow-sm">
                                <div>
                                    <p class="font-bold text-gray-800 text-sm group-hover:text-emerald-700">{{ $calon->full_name }}</p>
                                    <p class="text-[10px] text-gray-500 font-mono bg-gray-100 inline-block px-1.5 py-0.5 rounded mt-0.5">{{ $calon->nis }}</p>
                                </div>
                                <div class="bg-gray-100 text-gray-400 p-1.5 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                            </button>
                        </form>
                    @empty
                        <div class="text-center mt-10 opacity-50">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            <p class="text-gray-500 text-xs">Tidak ada santri tersedia.</p>
                            <p class="text-gray-400 text-[10px] mt-1">Semua santri {{ $asrama->jenis_kelamin }} sudah memiliki asrama.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
    @include('layouts.pengurus-nav')
</x-app-layout>