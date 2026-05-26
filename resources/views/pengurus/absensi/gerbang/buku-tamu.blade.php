<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        
        {{-- HEADER MOBILE --}}
        <div class="bg-emerald-600 pt-6 pb-24 px-6 rounded-b-[35px] shadow-lg relative overflow-hidden z-10">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('pengurus.absensi.index') }}" class="bg-white/20 p-2.5 rounded-xl text-white backdrop-blur-md hover:bg-white/30 transition active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-tight">Buku Tamu</h1>
                        <p class="text-emerald-100 text-xs font-medium">Data Kunjungan Hari Ini</p>
                    </div>
                </div>
                
                {{-- Tombol Buka Modal Tambah Tamu --}}
                <button onclick="bukaModalTamu()" class="bg-white text-emerald-700 text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm hover:bg-emerald-50 hover:scale-105 active:scale-95 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tamu Baru</span>
                </button>
            </div>
        </div>

        {{-- CONTENT CONTAINER --}}
        <div class="px-5 -mt-16 relative z-20 space-y-6 max-w-5xl mx-auto">
            
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl shadow-sm text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- FILTER & CETAK LAPORAN BULANAN --}}
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-5 flex flex-col sm:flex-row justify-between gap-4 items-center">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Unduh Laporan Bulanan</h3>
                        <p class="text-xs text-gray-500">Pilih bulan untuk mencetak riwayat buku tamu.</p>
                    </div>
                </div>

                <form action="{{ route('pengurus.buku-tamu.pdf') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <select name="bulan" class="bg-gray-50 border border-gray-200 text-sm rounded-xl focus:ring-emerald-500 w-full sm:w-32 p-2.5">
                        @for($i=1; $i<=12; $i++)
                            <option value="{{ sprintf('%02d', $i) }}" {{ date('m') == sprintf('%02d', $i) ? 'selected' : '' }}>Bulan {{ $i }}</option>
                        @endfor
                    </select>
                    <select name="tahun" class="bg-gray-50 border border-gray-200 text-sm rounded-xl focus:ring-emerald-500 w-full sm:w-28 p-2.5">
                        @for($t=date('Y'); $t>=date('Y')-2; $t--)
                            <option value="{{ $t }}" {{ date('Y') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition flex items-center justify-center gap-2 whitespace-nowrap">
                        Cetak PDF
                    </button>
                </form>
            </div>

            {{-- LIST TAMU HARI INI --}}
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 pb-2 border-b border-gray-50">
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    Daftar Tamu ({{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }})
                </h3>

                <div class="space-y-4">
                    @forelse($tamus as $tamu)
                        <div class="border border-gray-100 rounded-2xl p-4 bg-gray-50 relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1.5 h-full {{ $tamu->jam_keluar ? 'bg-gray-400' : 'bg-green-500' }}"></div>
                            
                            <div class="flex justify-between items-start pl-2">
                                <div>
                                    <h4 class="font-bold text-gray-800 text-base">{{ $tamu->nama_tamu }}</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">Dari: <span class="font-semibold">{{ $tamu->instansi_asal ?? '-' }}</span></p>
                                    
                                    <div class="mt-2 text-xs text-gray-600 bg-white p-2 rounded-lg border border-gray-100 inline-block shadow-sm">
                                        <span class="block mb-1">🎯 <b>Bertemu:</b> {{ $tamu->bertemu_dengan }}</span>
                                        <span class="block">📝 <b>Keperluan:</b> {{ $tamu->keperluan }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</p>
                                    @if($tamu->jam_keluar)
                                        <span class="bg-gray-200 text-gray-600 text-[10px] font-bold px-2 py-1 rounded-lg">Telah Keluar</span>
                                        <p class="text-xs text-gray-500 mt-2">{{ \Carbon\Carbon::parse($tamu->jam_masuk)->format('H:i') }} - {{ \Carbon\Carbon::parse($tamu->jam_keluar)->format('H:i') }}</p>
                                    @else
                                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-lg animate-pulse">Masih di Dalam</span>
                                        <p class="text-xs text-gray-500 mt-2">Masuk: {{ \Carbon\Carbon::parse($tamu->jam_masuk)->format('H:i') }}</p>
                                        
                                        <form action="{{ route('pengurus.buku-tamu.checkout', $tamu->id) }}" method="POST" class="mt-2">
                                            @csrf @method('PUT')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg active:scale-95 transition" onclick="return confirm('Tandai tamu ini sudah keluar pondok?');">
                                                Checkout
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 text-sm italic bg-white rounded-2xl border border-gray-100 border-dashed">
                            Belum ada kunjungan tamu hari ini.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL TAMBAH TAMU --}}
    <div id="modal_tamu" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-3xl w-full max-w-md p-6 relative shadow-2xl transform scale-95 transition-transform duration-300" id="modal_content_tamu">
            
            <button onclick="tutupModalTamu()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 bg-gray-100 p-2 rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Catat Tamu Baru</h3>

            <form action="{{ route('pengurus.buku-tamu.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Nama Lengkap *</label>
                    <input type="text" name="nama_tamu" required class="bg-gray-50 border border-gray-200 text-sm rounded-xl focus:ring-emerald-500 w-full p-2.5">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">No. HP (Opsional)</label>
                        <input type="text" name="no_hp" class="bg-gray-50 border border-gray-200 text-sm rounded-xl focus:ring-emerald-500 w-full p-2.5">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Asal/Instansi</label>
                        <input type="text" name="instansi_asal" class="bg-gray-50 border border-gray-200 text-sm rounded-xl focus:ring-emerald-500 w-full p-2.5">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Bertemu Dengan *</label>
                    <input type="text" name="bertemu_dengan" required placeholder="Nama santri / ustadz / pengurus" class="bg-gray-50 border border-gray-200 text-sm rounded-xl focus:ring-emerald-500 w-full p-2.5">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Keperluan *</label>
                    <textarea name="keperluan" required rows="2" class="bg-gray-50 border border-gray-200 text-sm rounded-xl focus:ring-emerald-500 w-full p-2.5"></textarea>
                </div>

                <button type="submit" class="w-full bg-emerald-600 text-white py-3 rounded-xl font-bold text-sm shadow-md active:scale-95 transition hover:bg-emerald-700 mt-2">
                    Simpan Data Tamu
                </button>
            </form>
        </div>
    </div>

    <script>
        const modalTamu = document.getElementById('modal_tamu');
        const modalContentTamu = document.getElementById('modal_content_tamu');

        function bukaModalTamu() {
            modalTamu.classList.remove('hidden');
            modalTamu.classList.add('flex');
            setTimeout(() => {
                modalTamu.classList.remove('opacity-0');
                modalContentTamu.classList.remove('scale-95');
            }, 10);
        }

        function tutupModalTamu() {
            modalTamu.classList.add('opacity-0');
            modalContentTamu.classList.add('scale-95');
            setTimeout(() => {
                modalTamu.classList.add('hidden');
                modalTamu.classList.remove('flex');
            }, 300);
        }
    </script>
</x-app-layout>