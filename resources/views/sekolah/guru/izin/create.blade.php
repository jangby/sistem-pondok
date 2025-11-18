<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('sekolah.guru.izin.index') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Formulir Pengajuan</h1>
            </div>
        </div>

        {{-- 2. FORM CARD --}}
        <div class="px-5 -mt-16 relative z-20">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                
                <form method="POST" action="{{ route('sekolah.guru.izin.store') }}">
                    @csrf
                    
                    {{-- Tipe Izin --}}
                    <div class="mb-5">
                        <label for="tipe_izin" class="block text-xs font-bold text-gray-500 uppercase mb-2">Tipe Pengajuan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="tipe_izin" value="sakit" class="peer sr-only" required>
                                <div class="rounded-xl border-2 border-gray-200 p-3 text-center transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:bg-gray-50">
                                    <div class="text-2xl mb-1">🤒</div>
                                    <span class="text-sm font-bold text-gray-600 peer-checked:text-emerald-700">Sakit</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="tipe_izin" value="izin" class="peer sr-only" required>
                                <div class="rounded-xl border-2 border-gray-200 p-3 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50">
                                    <div class="text-2xl mb-1">📅</div>
                                    <span class="text-sm font-bold text-gray-600 peer-checked:text-blue-700">Izin</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label for="tanggal_mulai" class="block text-xs font-bold text-gray-500 uppercase mb-2">Mulai</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                                   class="block w-full border-gray-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50 font-medium py-3" 
                                   value="{{ old('tanggal_mulai', now()->format('Y-m-d')) }}" required>
                            <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-1" />
                        </div>
                        <div>
                            <label for="tanggal_selesai" class="block text-xs font-bold text-gray-500 uppercase mb-2">Selesai</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                                   class="block w-full border-gray-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50 font-medium py-3" 
                                   value="{{ old('tanggal_selesai', now()->format('Y-m-d')) }}" required>
                            <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-1" />
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-6">
                        <label for="keterangan_guru" class="block text-xs font-bold text-gray-500 uppercase mb-2">Alasan / Keterangan</label>
                        <textarea id="keterangan_guru" name="keterangan_guru" rows="4" 
                                  class="block w-full border-gray-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500 bg-gray-50 py-3 placeholder-gray-400" 
                                  placeholder="Jelaskan alasan pengajuan izin..." required>{{ old('keterangan_guru') }}</textarea>
                        <x-input-error :messages="$errors->get('keterangan_guru')" class="mt-1" />
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit" class="w-full py-4 bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <span>Kirim Pengajuan</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    
                    <div class="text-center mt-4">
                         <a href="{{ route('sekolah.guru.izin.index') }}" class="text-xs font-bold text-gray-400 hover:text-gray-600">Batal</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>