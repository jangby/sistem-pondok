<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        
        {{-- HEADER MOBILE (EMERALD) --}}
        <div class="bg-emerald-600 pt-6 pb-24 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('pengurus.absensi.index') }}" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">Jadwal Jaga</h1>
                        <p class="text-emerald-100 text-xs font-medium">Manajemen Penjaga Gerbang</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CONTENT CONTAINER --}}
        <div class="px-5 -mt-16 relative z-20 space-y-6">
            
            {{-- TOMBOL CEPAT KE KIOS ABSENSI --}}
            <a href="{{ route('pengurus.kios.index') }}" class="flex items-center justify-between bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-3xl shadow-lg p-5 hover:scale-[1.02] transition-transform active:scale-95 border border-blue-400/50">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-sm">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">Buka Kios Absensi</h2>
                        <p class="text-blue-100 text-xs">Layar absen untuk santri bertugas</p>
                    </div>
                </div>
                <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl shadow-sm text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl shadow-sm text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- 1. FORM TAMBAH JADWAL --}}
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 pb-2 border-b border-gray-50">
                    <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    Tambah Jadwal
                </h3>

                <form action="{{ route('pengurus.jadwal.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    {{-- Pencarian Santri (Live Search) --}}
                    <div class="relative w-full">
                        <label class="text-xs font-bold text-gray-400 uppercase block mb-2 tracking-wider">Cari Santri</label>
                        <input type="hidden" name="santri_id" id="input_santri_id" required>
                        <input type="text" id="input_search_santri" placeholder="Ketik nama santri..." autocomplete="off" class="bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full p-3 shadow-sm placeholder-gray-400">
                        
                        {{-- Dropdown Hasil Pencarian --}}
                        <ul id="dropdown_santris" class="absolute z-50 w-full bg-white border border-gray-200 rounded-xl shadow-xl mt-1 hidden max-h-48 overflow-y-auto">
                            @foreach($santris as $santri)
                                <li class="px-4 py-3 hover:bg-emerald-50 cursor-pointer text-sm border-b border-gray-50 last:border-0 text-gray-700" onclick="pilihSantri('{{ $santri->id }}', '{{ addslashes($santri->full_name) }}')">
                                    {{ $santri->full_name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Pilih Hari --}}
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase block mb-2 tracking-wider">Pilih Hari</label>
                        <select name="hari" required class="bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full p-3 shadow-sm">
                            <option value="">-- Pilih Hari --</option>
                            <option value="Senin">Senin</option><option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option><option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white py-3.5 rounded-2xl font-bold text-sm shadow-lg shadow-emerald-200 active:scale-95 transition hover:bg-emerald-700 mt-2">
                        Simpan Jadwal
                    </button>
                </form>
            </div>

            {{-- 2. DAFTAR JADWAL (List Style Mobile) --}}
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        Daftar Santri Bertugas
                    </h3>
                    
                    {{-- Tombol Cetak Jadwal --}}
                    <a href="{{ route('pengurus.jadwal.pdf') }}" class="bg-red-50 text-red-600 hover:bg-red-100 hover:scale-105 active:scale-95 px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm border border-red-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Jadwal
                    </a>
                </div>

                <div class="space-y-3 max-h-[500px] overflow-y-auto pr-1 custom-scrollbar">
                    @forelse($jadwals as $jadwal)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-2xl border border-gray-100">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="bg-white border border-gray-200 text-emerald-600 font-bold px-3 py-2 rounded-xl text-center w-16 shadow-sm flex-shrink-0">
                                    <span class="text-xs uppercase block tracking-wider">{{ substr($jadwal->hari, 0, 3) }}</span>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="font-bold text-gray-800 text-sm truncate">{{ $jadwal->santri->full_name }}</p>
                                    @if($jadwal->santri->pin_absen)
                                        <p class="text-[10px] text-green-600 font-bold flex items-center gap-1 mt-0.5"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> PIN Aktif</p>
                                    @else
                                        <p class="text-[10px] text-orange-500 font-bold mt-0.5">PIN Belum Diatur</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex gap-2 flex-shrink-0">
                                {{-- Tombol Buka Modal PIN --}}
                                <button type="button" onclick="bukaModalPin('{{ $jadwal->santri->id }}', '{{ addslashes($jadwal->santri->full_name) }}')" class="bg-white p-2.5 rounded-xl text-blue-500 hover:text-blue-700 border border-gray-200 shadow-sm active:scale-90 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                </button>
                                
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('pengurus.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-white p-2.5 rounded-xl text-red-400 hover:text-red-600 border border-gray-200 shadow-sm active:scale-90 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-400 text-xs italic bg-gray-50 rounded-2xl border border-gray-100 border-dashed">
                            Belum ada jadwal yang ditambahkan.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- 3. MODAL POP-UP ATUR PIN (Tersembunyi secara default) --}}
    <div id="modal_pin" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-3xl w-full max-w-sm p-6 relative shadow-2xl transform scale-95 transition-transform duration-300" id="modal_content">
            
            {{-- Tombol Tutup (X) --}}
            <button onclick="tutupModalPin()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 bg-gray-100 p-2 rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h3 class="text-lg font-bold text-gray-800 mb-1">Atur PIN Absen</h3>
            <p class="text-xs text-gray-500 mb-5">Santri: <span id="nama_santri_modal" class="font-bold text-emerald-600"></span></p>

            <form action="{{ route('pengurus.jadwal.pin') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="santri_id" id="modal_santri_id" required>
                
                <div>
                    <label class="text-xs font-bold text-gray-400 uppercase block mb-2">Buat PIN Baru (6 Angka)</label>
                    <input type="text" name="pin" maxlength="6" pattern="\d{6}" required placeholder="Contoh: 123456" class="bg-gray-50 border border-gray-200 rounded-xl text-center text-2xl tracking-[0.5em] font-bold text-gray-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 block w-full py-4">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3.5 rounded-2xl font-bold text-sm shadow-lg shadow-blue-200 active:scale-95 transition hover:bg-blue-700">
                    Simpan PIN
                </button>
            </form>
        </div>
    </div>

    {{-- JAVASCRIPT UNTUK FITUR PENCARIAN & MODAL --}}
    <script>
        // --- 1. FITUR PENCARIAN NAMA SANTRI (LIVE SEARCH) ---
        const inputSearch = document.getElementById('input_search_santri');
        const listSantris = document.getElementById('dropdown_santris');
        const inputSantriId = document.getElementById('input_santri_id');
        const items = listSantris.getElementsByTagName('li');

        inputSearch.addEventListener('input', function() {
            listSantris.classList.remove('hidden');
            let filter = inputSearch.value.toLowerCase();
            let adaHasil = false;
            
            for (let i = 0; i < items.length; i++) {
                let txtValue = items[i].textContent || items[i].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    items[i].style.display = "";
                    adaHasil = true;
                } else {
                    items[i].style.display = "none";
                }
            }
            if(!adaHasil) listSantris.classList.add('hidden');
        });

        inputSearch.addEventListener('focus', function() {
            if(items.length > 0) listSantris.classList.remove('hidden');
        });

        document.addEventListener('click', function(e) {
            if(!inputSearch.contains(e.target) && !listSantris.contains(e.target)) {
                listSantris.classList.add('hidden');
            }
        });

        function pilihSantri(id, name) {
            inputSantriId.value = id;
            inputSearch.value = name;
            listSantris.classList.add('hidden');
        }


        // --- 2. FITUR MODAL POP-UP PIN ---
        const modal = document.getElementById('modal_pin');
        const modalContent = document.getElementById('modal_content');

        function bukaModalPin(santriId, santriName) {
            document.getElementById('modal_santri_id').value = santriId;
            document.getElementById('nama_santri_modal').innerText = santriName;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
            }, 10);
        }

        function tutupModalPin() {
            modal.classList.add('opacity-0');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    </script>
</x-app-layout>