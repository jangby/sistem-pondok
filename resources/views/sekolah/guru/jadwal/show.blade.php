<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-gray-50 pb-24">
        
        {{-- 1. HEADER (Gaya Mobile Dashboard Guru) --}}
        <div class="bg-emerald-600 px-5 pt-8 pb-24 shadow-md rounded-b-[30px] relative overflow-hidden">
            {{-- Dekorasi Background --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-emerald-400 opacity-10 rounded-full -ml-5 -mb-5 blur-xl"></div>

            <div class="relative z-10 flex items-center gap-3">
                <a href="{{ route('sekolah.guru.dashboard') }}" class="text-white hover:bg-emerald-700/50 p-2 rounded-full transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-lg font-bold text-white">Absensi Mengajar</h1>
            </div>
        </div>

        {{-- 2. KONTEN UTAMA (Floating Card) --}}
        <div class="px-5 -mt-16 relative z-20">
            
            {{-- Notifikasi Error --}}
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 text-red-600 border border-red-100 rounded-2xl text-xs font-bold flex items-start gap-2 shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl text-xs font-bold flex items-start gap-2 shadow-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden relative">
                {{-- Header Info Mapel --}}
                <div class="bg-gray-50 p-5 border-b border-gray-100">
                    <span class="inline-block px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-[10px] font-bold uppercase tracking-wider mb-2">
                        {{ $jadwalPelajaran->kelas->nama_kelas }}
                    </span>
                    <h3 class="text-xl font-bold text-gray-800 leading-tight mb-1">
                        {{ $jadwalPelajaran->mataPelajaran->nama_mapel }}
                    </h3>
                    <p class="text-xs text-gray-500 font-medium flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $jadwalPelajaran->hari }}, {{ $jadwalPelajaran->jam_mulai }} - {{ $jadwalPelajaran->jam_selesai }}
                    </p>
                </div>

                <div class="p-6">
                    {{-- LOGIKA UTAMA --}}
                    @if($absensiPelajaran)
                        
                        {{-- STATUS: SUDAH ABSEN --}}
                        <div class="bg-green-50 border border-green-100 rounded-xl p-4 flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-green-800">Terkonfirmasi Hadir</h4>
                                <p class="text-[10px] text-green-600">
                                    Masuk pukul {{ \Carbon\Carbon::parse($absensiPelajaran->jam_guru_masuk_kelas)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        {{-- FORM MATERI --}}
                        <form method="POST" action="{{ route('sekolah.guru.jadwal.absen.materi', $absensiPelajaran->id) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="materi_pembahasan" class="block text-xs font-bold text-gray-500 uppercase mb-2">
                                    Materi Pembahasan (Jurnal)
                                </label>
                                <textarea id="materi_pembahasan" name="materi_pembahasan" rows="3" 
                                          class="block w-full border-gray-200 rounded-xl text-sm focus:border-emerald-500 focus:ring-emerald-500 placeholder-gray-300 bg-gray-50" 
                                          placeholder="Tulis ringkasan materi hari ini...">{{ old('materi_pembahasan', $absensiPelajaran->materi_pembahasan) }}</textarea>
                                <x-input-error :messages="$errors->get('materi_pembahasan')" class="mt-1" />
                            </div>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-gray-700 transition shadow-sm">
                                    Simpan Jurnal
                                </button>
                            </div>
                        </form>

                        <hr class="my-6 border-gray-100 border-dashed">

                        {{-- TOMBOL LANJUT --}}
                        <a href="{{ route('sekolah.guru.absensi.siswa.index', $absensiPelajaran->id) }}" 
                           class="block w-full py-4 bg-emerald-600 text-white font-bold rounded-xl text-center shadow-lg shadow-emerald-200 hover:bg-emerald-700 active:scale-[0.98] transition-transform flex items-center justify-center gap-2">
                            <span>Lanjut Absensi Siswa</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>

                    @else
                        {{-- STATUS: BELUM ABSEN --}}
                        
                        <div class="text-center py-4">
                            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <p class="text-gray-500 text-sm px-4 mb-6">
                                Pastikan Anda sudah berada di dalam kelas. Sistem akan memverifikasi lokasi GPS Anda.
                            </p>

                            <form method="POST" action="{{ route('sekolah.guru.jadwal.absen') }}" id="form-absen-mengajar" class="hidden">
                                @csrf
                                <input type="hidden" name="jadwal_pelajaran_id" value="{{ $jadwalPelajaran->id }}">
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                            </form>

                            <button id="btn-absen-mengajar"
                                    class="w-full py-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Mulai Mengajar</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @if(!$absensiPelajaran)
    <script>
        const formAbsensi = document.getElementById('form-absen-mengajar');
        const inputLat = document.getElementById('latitude');
        const inputLon = document.getElementById('longitude');
        const btnAbsen = document.getElementById('btn-absen-mengajar');

        const handleAbsenMengajar = (event) => {
            // Visual Loading Awal
            Swal.fire({
                title: 'Mencari Lokasi...',
                text: 'Mohon izinkan akses GPS.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            if (!navigator.geolocation) {
                Swal.fire('Gagal', 'Browser tidak mendukung Geolocation.', 'error');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                // Sukses
                (position) => {
                    inputLat.value = position.coords.latitude;
                    inputLon.value = position.coords.longitude;
                    
                    // Update Loading -> Memproses
                    Swal.fire({
                        title: 'Memproses Absensi...',
                        text: 'Sedang memverifikasi data.',
                        icon: 'info',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit Form
                    formAbsensi.submit();
                },
                // Gagal
                (error) => {
                    let msg = 'Terjadi kesalahan lokasi.';
                    if (error.code === error.PERMISSION_DENIED) msg = 'Izin lokasi ditolak. Aktifkan GPS.';
                    else if (error.code === error.POSITION_UNAVAILABLE) msg = 'Sinyal GPS tidak ditemukan.';
                    else if (error.code === error.TIMEOUT) msg = 'Waktu habis mencari lokasi.';
                    
                    Swal.fire('Gagal Lokasi', msg, 'error');
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        };

        if (btnAbsen) {
            btnAbsen.addEventListener('click', handleAbsenMengajar);
        }
    </script>
    @endif
    @endpush
</x-app-layout>