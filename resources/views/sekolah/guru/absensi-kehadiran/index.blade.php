<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-20">
        
        {{-- 1. HEADER (Mirip Payment/Tagihan) --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
                <div class="w-32 h-32 bg-white opacity-10 rounded-full absolute -top-10 -left-10 blur-2xl"></div>
                <div class="w-40 h-40 bg-emerald-400 opacity-10 rounded-full absolute bottom-0 right-0 blur-xl"></div>
            </div>
            
            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('sekolah.guru.dashboard') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Absensi Kehadiran</h1>
            </div>
        </div>

        {{-- 2. KONTEN CARD --}}
        <div class="px-5 -mt-16 relative z-20">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden p-6">
                
                {{-- Notifikasi Error --}}
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 text-red-600 border border-red-100 rounded-xl text-xs font-bold flex items-start gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('error') }}
                    </div>
                @endif
                
                {{-- Notifikasi Sukses --}}
                @if(session('success'))
                    <div class="mb-4 p-4 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-xs font-bold flex items-start gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(!$settings)
                    <div class="text-center py-6">
                        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-3 text-red-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <p class="text-gray-600 text-sm font-medium">Admin Sekolah belum mengatur jam absensi.</p>
                    </div>
                @else
                    {{-- Tanggal & Jam Digital --}}
                    <div class="text-center mb-8">
                        <span class="inline-block px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-bold mb-2 tracking-wide uppercase">
                            {{ now()->locale('id_ID')->isoFormat('dddd, D MMMM Y') }}
                        </span>
                        <div class="relative py-2">
                            <h2 class="text-5xl font-black text-gray-800 tracking-tight tabular-nums" id="jam-digital">
                                {{ now()->format('H:i:s') }}
                            </h2>
                            <p class="text-xs text-gray-400 mt-1">Waktu Server Terkini</p>
                        </div>
                    </div>

                    {{-- Form Hidden (Logic Tetap) --}}
                    <form method="POST" action="{{ route('sekolah.guru.absensi.kehadiran.store') }}" id="form-absensi" class="hidden">
                        @csrf
                        <input type="hidden" name="tipe_absen" id="tipe_absen">
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        <input type="hidden" name="kode_absen" id="kode_absen">
                    </form>

                    {{-- Area Tombol Aksi --}}
                    <div class="space-y-4">
                        @if($isHariLibur)
                            <div class="p-6 text-center bg-yellow-50 text-yellow-700 rounded-2xl border border-yellow-100">
                                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <p class="font-bold text-sm">Hari Libur</p>
                                <p class="text-xs mt-1">{{ $isHariLibur->keterangan ?? '' }}</p>
                            </div>
                        @elseif(!$isHariKerja)
                            <div class="p-6 text-center bg-gray-50 text-gray-600 rounded-2xl border border-gray-100">
                                <p class="font-bold text-sm">Bukan Hari Kerja</p>
                            </div>
                        @else
                            
                            {{-- Tombol Absen Masuk --}}
                            @if(!$absensiHariIni?->jam_masuk)
                                <button id="btn-absen-masuk" data-tipe="masuk"
                                        class="w-full group relative flex items-center justify-center gap-3 px-6 py-4 bg-emerald-600 rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition-all duration-200 overflow-hidden">
                                    <div class="absolute inset-0 bg-white/10 group-hover:bg-white/0 transition-colors"></div>
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                    <span class="text-white font-bold text-lg tracking-wide uppercase">Absen Masuk</span>
                                </button>
                                <p class="text-center text-[10px] text-gray-400 mt-2">Pastikan GPS aktif</p>
                            @else
                                <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 flex items-center gap-3">
                                    <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-500 uppercase font-bold">Sudah Absen Masuk</p>
                                        <p class="text-emerald-700 font-bold text-lg">{{ \Carbon\Carbon::parse($absensiHariIni->jam_masuk)->format('H:i') }}</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Tombol Absen Pulang --}}
                            @if($absensiHariIni?->jam_masuk && !$absensiHariIni?->jam_pulang)
                                <div class="border-t border-dashed border-gray-200 my-6"></div>
                                <button id="btn-absen-pulang" data-tipe="pulang"
                                        class="w-full group relative flex items-center justify-center gap-3 px-6 py-4 bg-red-500 rounded-xl shadow-lg shadow-red-200 hover:bg-red-600 active:scale-[0.98] transition-all duration-200">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    <span class="text-white font-bold text-lg tracking-wide uppercase">Absen Pulang</span>
                                </button>
                            @elseif($absensiHariIni?->jam_pulang)
                                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-center gap-3 mt-3">
                                    <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-500 uppercase font-bold">Sudah Absen Pulang</p>
                                        <p class="text-blue-700 font-bold text-lg">{{ \Carbon\Carbon::parse($absensiHariIni->jam_pulang)->format('H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
<script>
    // --- SCRIPT JAM DIGITAL (Tidak berubah) ---
    const jamDigitalElement = document.getElementById('jam-digital');
    if (jamDigitalElement) {
        setInterval(() => {
            const now = new Date();
            jamDigitalElement.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
        }, 1000);
    }

    // --- SCRIPT ABSENSI GPS ROBUST ---
    const formAbsensi = document.getElementById('form-absensi');
    const inputTipe = document.getElementById('tipe_absen');
    const inputLat = document.getElementById('latitude');
    const inputLon = document.getElementById('longitude');
    const inputKode = document.getElementById('kode_absen');
    const btnMasuk = document.getElementById('btn-absen-masuk');
    const btnPulang = document.getElementById('btn-absen-pulang');

    // Fungsi utama meminta lokasi
    const requestLocation = (onSuccess, onError) => {
        if (!navigator.geolocation) {
            onError('Browser tidak mendukung GPS.');
            return;
        }

        // Opsi GPS: Timeout diperlama (20 detik) untuk HP yang GPS-nya 'cold start'
        const options = { 
            enableHighAccuracy: true, 
            timeout: 20000, 
            maximumAge: 0 
        };

        navigator.geolocation.getCurrentPosition(onSuccess, onError, options);
    };

    // Fungsi cek izin dulu (Permissions API)
    const checkPermissionAndExecute = async (tipe) => {
        // 1. Cek apakah browser mendukung Permissions API
        if (navigator.permissions && navigator.permissions.query) {
            try {
                const result = await navigator.permissions.query({ name: 'geolocation' });
                
                // Jika status DENIED (Ditolak Permanen)
                if (result.state === 'denied') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Izin Lokasi Ditolak',
                        text: 'Anda pernah memblokir lokasi. Silakan reset izin lokasi browser Anda: Klik Icon Gembok di URL Bar > Izin > Lokasi > Izinkan.',
                        confirmButtonText: 'Saya Mengerti'
                    });
                    return;
                }
            } catch (error) {
                console.log("Browser lama, skip permission check");
            }
        }

        // 2. Tampilkan Loading
        Swal.fire({
            title: 'Mencari Lokasi...',
            text: 'Mohon izinkan akses lokasi jika diminta browser.',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                
                // 3. Eksekusi Ambil Lokasi
                requestLocation(
                    // SUKSES DAPAT LOKASI
                    (position) => {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;
                        
                        // Tutup loading lokasi
                        Swal.close();
                        
                        // Lanjut ke Input Kode
                        askToken(tipe, lat, lon);
                    },
                    // GAGAL DAPAT LOKASI
                    (error) => {
                        let msg = 'Gagal mengambil lokasi.';
                        if (error.code === 1) msg = 'Izin lokasi ditolak. Segarkan halaman dan klik "Izinkan".';
                        if (error.code === 2) msg = 'Sinyal GPS tidak ditemukan. Pastikan GPS HP aktif.';
                        if (error.code === 3) msg = 'Waktu habis (Timeout). Cobalah berpindah ke area terbuka.';
                        
                        Swal.fire({
                            icon: 'warning',
                            title: 'Gagal Lokasi',
                            text: msg,
                            confirmButtonText: 'Coba Lagi'
                        });
                    }
                );
            }
        });
    };

    // Fungsi Input Kode & Submit
    const askToken = (tipe, lat, lon) => {
        Swal.fire({
            title: 'Masukkan Kode Absensi',
            text: 'Masukkan 6 digit kode dari layar monitor.',
            input: 'number',
            inputAttributes: {
                autocapitalize: 'off',
                maxlength: 6,
                pattern: '[0-9]*',
                inputmode: 'numeric',
                style: 'text-align: center; font-size: 1.5em; letter-spacing: 4px; font-weight: bold;'
            },
            showCancelButton: true,
            confirmButtonText: 'Kirim Absensi',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#10b981',
            preConfirm: (kode) => {
                if (!kode || kode.length < 6) {
                    Swal.showValidationMessage('Kode harus 6 digit angka');
                    return false;
                }
                return kode;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitForm(tipe, lat, lon, result.value);
            }
        });
    };

    // Fungsi Submit Form Akhir
    const submitForm = (tipe, lat, lon, kode) => {
        Swal.fire({
            title: 'Memproses Absensi...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        inputTipe.value = tipe;
        inputLat.value = lat;
        inputLon.value = lon;
        inputKode.value = kode;
        
        formAbsensi.submit();
    };

    // Event Listener
    const handleAbsenClick = (event) => {
        event.preventDefault();
        const button = event.currentTarget;
        const tipe = button.dataset.tipe;
        
        if(!tipe) return;

        // Mulai proses cek permission -> lokasi -> kode
        checkPermissionAndExecute(tipe);
    };

    if (btnMasuk) btnMasuk.addEventListener('click', handleAbsenClick);
    if (btnPulang) btnPulang.addEventListener('click', handleAbsenClick);

</script>
@endpush
</x-app-layout>