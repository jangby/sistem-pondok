<x-app-layout>
    {{-- STYLE KHUSUS (Hanya untuk hover card) --}}
    @push('styles')
    <style>
        .config-card { transition: transform 0.2s, box-shadow 0.2s; }
        .config-card:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
    </style>
    @endpush

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                    <span class="text-3xl">⚙️</span> Konfigurasi Absensi
                </h2>
                <p class="text-sm text-gray-500 mt-1">Atur jam kerja, lokasi, jaringan, dan kalender libur sekolah.</p>
            </div>
            
            {{-- KIOS BANNER MINI --}}
            <a href="{{ route('sekolah.admin.konfigurasi.kios.show') }}" target="_blank" class="group flex items-center gap-3 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-200 transition-all">
                <div class="p-1.5 bg-white/20 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div class="text-left">
                    <div class="text-[10px] font-medium text-indigo-200 uppercase tracking-wider">Mode Perangkat</div>
                    <div class="text-sm font-bold">Buka Kios Absensi &rarr;</div>
                </div>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. PENGATURAN UTAMA (JAM & HARI KERJA) --}}
            <form method="POST" action="{{ route('sekolah.admin.konfigurasi.settings.store') }}">
                @csrf
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                            Waktu Operasional
                        </h3>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                    
                    <div class="p-6 md:p-8 grid grid-cols-1 lg:grid-cols-2 gap-10">
                        {{-- Jam Kerja --}}
                        <div class="space-y-6">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Aturan Jam (Format 24 Jam)</h4>
                            
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Jam Masuk</label>
                                    <div class="relative">
                                        <input type="time" name="jam_masuk" value="{{ old('jam_masuk', $absensiSettings->jam_masuk ?? '07:00') }}" 
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 font-mono font-bold text-gray-800">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Waktu mulai check-in.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Batas Terlambat</label>
                                    <div class="relative">
                                        <input type="time" name="batas_telat" value="{{ old('batas_telat', $absensiSettings->batas_telat ?? '07:15') }}" 
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-xl focus:ring-amber-500 focus:border-amber-500 font-mono font-bold text-gray-800">
                                        <svg class="w-5 h-5 text-amber-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Lewat ini dihitung telat.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Pulang Awal</label>
                                    <div class="relative">
                                        <input type="time" name="jam_pulang_awal" value="{{ old('jam_pulang_awal', $absensiSettings->jam_pulang_awal ?? '14:00') }}" 
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 font-mono font-bold text-gray-800">
                                        <svg class="w-5 h-5 text-emerald-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Batas Akhir Scan</label>
                                    <div class="relative">
                                        <input type="time" name="jam_pulang_akhir" value="{{ old('jam_pulang_akhir', $absensiSettings->jam_pulang_akhir ?? '17:00') }}" 
                                            class="block w-full pl-10 py-2.5 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 font-mono font-bold text-gray-800">
                                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hari Kerja (PERBAIKAN: Desain Chips/Tombol Lebih Jelas) --}}
<div class="space-y-6">
    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Hari Sekolah Aktif</h4>
    
    @php
        // Logic untuk memastikan data hari kerja ter-handle dengan benar
        $defaultDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $savedDays = $absensiSettings->hari_kerja ?? $defaultDays;
        
        // Jika tersimpan sebagai JSON string di database, decode dulu
        if (is_string($savedDays)) {
            $savedDays = json_decode($savedDays, true) ?? [];
        }
        
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
    @endphp
    
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        @foreach($days as $hari)
            <label class="relative cursor-pointer group">
                {{-- INPUT CHECKBOX (Hidden) --}}
                <input type="checkbox" 
                       name="hari_kerja[]" 
                       value="{{ $hari }}" 
                       class="peer sr-only" 
                       @checked(in_array($hari, $savedDays))>
                
                {{-- TAMPILAN KARTU (Sibling dari Input) --}}
                <div class="flex items-center justify-between w-full px-4 py-3 rounded-xl border-2 transition-all duration-200 ease-in-out
                            bg-gray-50 border-gray-200 text-gray-500 
                            group-hover:border-blue-300 group-hover:bg-white group-hover:shadow-sm group-hover:-translate-y-0.5
                            peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white peer-checked:shadow-lg peer-checked:-translate-y-1">
                    
                    <span class="font-bold text-sm">{{ $hari }}</span>
                    
                    {{-- Lingkaran Status --}}
                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 bg-white/50 flex items-center justify-center
                                peer-checked:border-white peer-checked:bg-white/20">
                        {{-- Ikon Check hanya muncul di state checked --}}
                    </div>
                </div>

                {{-- IKON CHECKLIST (Sibling dari Input - Absolute Position) --}}
                {{-- Trik: SVG ditaruh sejajar dengan input agar bisa terkena efek peer-checked --}}
                <svg class="absolute top-4 right-4 w-3 h-3 text-white hidden peer-checked:block pointer-events-none font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                </svg>
            </label>
        @endforeach
    </div>

    <p class="text-xs text-gray-500 leading-relaxed mt-2">
        <span class="text-blue-600 font-bold">*</span> Hari yang <span class="font-bold text-gray-700">tidak dipilih</span> (abu-abu) akan dianggap libur, dan siswa/guru tidak dapat melakukan absensi.
    </p>
    <x-input-error :messages="$errors->get('hari_kerja')" class="mt-2" />
</div>
                    </div>
                </div>
            </form>

            {{-- 2. GRID MODUL (3 KOLOM) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- MODUL A: HARI LIBUR --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col h-[500px] config-card">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-red-50/50 rounded-t-2xl">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></span>
                            Hari Libur
                        </h3>
                        <button onclick="openModal('libur')" class="text-xs bg-white border border-gray-200 hover:border-red-300 text-gray-600 hover:text-red-600 px-3 py-1.5 rounded-lg transition font-bold shadow-sm">
                            + Tambah
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-3">
                        @forelse($hariLiburList as $libur)
                            <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-red-50/30 transition group">
                                <div>
                                    <div class="text-xs font-bold text-red-500">{{ \Carbon\Carbon::parse($libur->tanggal)->format('d M Y') }}</div>
                                    <div class="text-sm font-bold text-gray-700">{{ $libur->keterangan }}</div>
                                </div>
                                <form action="{{ route('sekolah.admin.konfigurasi.hari-libur.destroy', $libur->id) }}" method="POST" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-delete text-gray-300 group-hover:text-red-500 transition p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-10 text-gray-400 text-sm">Belum ada hari libur.</div>
                        @endforelse
                    </div>
                </div>

                {{-- MODUL B: WIFI --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col h-[500px] config-card">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-emerald-50/50 rounded-t-2xl">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg></span>
                            Jaringan WiFi
                        </h3>
                        <button onclick="openModal('wifi')" class="text-xs bg-white border border-gray-200 hover:border-emerald-300 text-gray-600 hover:text-emerald-600 px-3 py-1.5 rounded-lg transition font-bold shadow-sm">
                            + Tambah
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-3">
                        @forelse($wifiList as $wifi)
                            <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-emerald-50/30 transition group">
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $wifi->nama_wifi_ssid }}</div>
                                    <div class="text-xs font-mono text-gray-400">{{ $wifi->bssid ?? 'Any BSSID' }}</div>
                                </div>
                                <form action="{{ route('sekolah.admin.konfigurasi.wifi.destroy', $wifi->id) }}" method="POST" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-delete text-gray-300 group-hover:text-red-500 transition p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-10 text-gray-400 text-sm">Belum ada WiFi terdaftar.</div>
                        @endforelse
                    </div>
                </div>

                {{-- MODUL C: GEOFENCE --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col h-[500px] config-card">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-purple-50/50 rounded-t-2xl">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></span>
                            Lokasi (GPS)
                        </h3>
                        <button onclick="openModal('geofence')" class="text-xs bg-white border border-gray-200 hover:border-purple-300 text-gray-600 hover:text-purple-600 px-3 py-1.5 rounded-lg transition font-bold shadow-sm">
                            + Tambah
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-3">
                        @forelse($geofenceList as $geo)
                            <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-purple-50/30 transition group">
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $geo->nama_lokasi }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Radius: {{ $geo->radius }}m
                                    </div>
                                    <div class="text-[10px] font-mono text-gray-400 mt-1">{{ $geo->latitude }}, {{ $geo->longitude }}</div>
                                </div>
                                <form action="{{ route('sekolah.admin.konfigurasi.geofence.destroy', $geo->id) }}" method="POST" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-delete text-gray-300 group-hover:text-red-500 transition p-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-10 text-gray-400 text-sm">Belum ada lokasi terdaftar.</div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- MODAL UNIFIED (Libur, WiFi, Geofence) --}}
    <div id="configModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95" id="modalPanel">
                
                <div class="bg-white px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-black text-gray-800" id="modalTitle">Tambah Data</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="px-6 py-6">
                    {{-- Form Libur --}}
                    <form id="formLibur" action="{{ route('sekolah.admin.konfigurasi.hari-libur.store') }}" method="POST" class="hidden space-y-5 modal-form">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Libur</label>
                            <input type="date" name="tanggal" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm py-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Keterangan</label>
                            <input type="text" name="keterangan" placeholder="Cth: Hari Kemerdekaan" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 text-sm py-2.5">
                        </div>
                        <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 shadow-md transition">Simpan Hari Libur</button>
                    </form>

                    {{-- Form WiFi --}}
                    <form id="formWifi" action="{{ route('sekolah.admin.konfigurasi.wifi.store') }}" method="POST" class="hidden space-y-5 modal-form">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama WiFi (SSID)</label>
                            <input type="text" name="nama_wifi_ssid" placeholder="Cth: Sekolah_Official" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2.5">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">MAC Address (BSSID) - Opsional</label>
                            <input type="text" name="bssid" placeholder="AA:BB:CC:DD:EE:FF" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm py-2.5">
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl hover:bg-emerald-700 shadow-md transition">Simpan WiFi</button>
                    </form>

                    {{-- Form Geofence --}}
                    <form id="formGeofence" action="{{ route('sekolah.admin.konfigurasi.geofence.store') }}" method="POST" class="hidden space-y-5 modal-form">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lokasi</label>
                            <input type="text" name="nama_lokasi" placeholder="Cth: Gerbang Utama" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm py-2.5">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Latitude</label>
                                <input type="text" name="latitude" placeholder="-6.12345" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm py-2.5">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Longitude</label>
                                <input type="text" name="longitude" placeholder="106.12345" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm py-2.5">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Radius (Meter)</label>
                            <input type="number" name="radius" value="50" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm py-2.5">
                        </div>
                        <button type="submit" class="w-full bg-purple-600 text-white font-bold py-3 rounded-xl hover:bg-purple-700 shadow-md transition">Simpan Lokasi</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const modal = document.getElementById('configModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        const modalTitle = document.getElementById('modalTitle');
        
        function openModal(type) {
            // Hide all forms first
            document.querySelectorAll('.modal-form').forEach(el => el.classList.add('hidden'));
            
            if (type === 'libur') {
                modalTitle.innerText = 'Tambah Hari Libur';
                document.getElementById('formLibur').classList.remove('hidden');
            } else if (type === 'wifi') {
                modalTitle.innerText = 'Tambah Jaringan WiFi';
                document.getElementById('formWifi').classList.remove('hidden');
            } else if (type === 'geofence') {
                modalTitle.innerText = 'Tambah Lokasi Geofence';
                document.getElementById('formGeofence').classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'scale-95');
                panel.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closeModal() {
            backdrop.classList.add('opacity-0');
            panel.classList.remove('opacity-100', 'scale-100');
            panel.classList.add('opacity-0', 'scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        // SweetAlert Delete
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Hapus Data?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });

        // Auto Open Modal on Error
        @if($errors->has('tanggal') || $errors->has('keterangan'))
            openModal('libur');
        @elseif($errors->has('nama_wifi_ssid') || $errors->has('bssid'))
            openModal('wifi');
        @elseif($errors->has('nama_lokasi') || $errors->has('latitude'))
            openModal('geofence');
        @endif
    </script>
    @endpush
</x-app-layout>