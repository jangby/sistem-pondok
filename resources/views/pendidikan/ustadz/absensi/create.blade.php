<x-app-layout hide-nav>
    <x-slot name="header"></x-slot>

    {{-- Script Scanner QR --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    {{-- PASS DATA SANTRI KE ALPINE JS --}}
    <div x-data="absensiSystem({{ $santris }})" class="min-h-screen bg-gray-50 flex flex-col">
        
        {{-- 1. HEADER (Sticky) --}}
        <div class="bg-white shadow-sm sticky top-0 z-40">
            <div class="bg-emerald-600 px-4 py-4 pb-8 rounded-b-[25px]">
                <div class="flex justify-between items-center text-white">
                    <a href="{{ route('ustadz.absensi.menu', $jadwal->id) }}" class="flex items-center gap-2 hover:text-emerald-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        <span class="font-bold">Absensi Kelas</span>
                    </a>
                    <div class="text-right">
                        <p class="text-xs text-emerald-100 opacity-80">Total Santri</p>
                        <p class="text-xl font-bold leading-none" x-text="students.length">0</p>
                    </div>
                </div>
            </div>

            {{-- SWITCH METODE --}}
            <div class="px-4 -mt-6 pb-2">
                <div class="bg-white rounded-xl shadow-md border border-gray-100 p-1 flex">
                    <button @click="mode = 'rfid'" :class="mode === 'rfid' ? 'bg-emerald-100 text-emerald-700 shadow-sm' : 'text-gray-500'" class="flex-1 py-2 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M9 17h.01M9 14h.01M3 21h18M4.5 4.5l15 15"></path></svg>
                        RFID Mode
                    </button>
                    <button @click="mode = 'qr'" :class="mode === 'qr' ? 'bg-blue-100 text-blue-700 shadow-sm' : 'text-gray-500'" class="flex-1 py-2 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M9 17h.01M9 14h.01M3 21h18M4.5 4.5l15 15"></path></svg>
                        QR Code
                    </button>
                </div>
            </div>
        </div>

        {{-- 2. INPUT AREA --}}
        <div class="px-4 mt-2">
            
            {{-- RFID INPUT --}}
            <div x-show="mode === 'rfid'" class="bg-white p-4 rounded-xl border border-emerald-200 shadow-sm text-center">
                <div class="mb-2 text-emerald-600">
                    <svg class="w-10 h-10 mx-auto animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-700">Tempelkan Kartu</p>
                <p class="text-xs text-gray-400 mb-3">Pastikan kursor aktif di kolom bawah</p>
                
                <input type="text" x-model="scanInput" @keydown.enter.prevent="processScan()" x-ref="rfidInput" 
                    class="w-full text-center border-gray-300 rounded-lg focus:border-emerald-500 focus:ring-emerald-500 text-sm" 
                    placeholder="Klik di sini & Scan..." autofocus>
            </div>

            {{-- QR CAMERA --}}
            <div x-show="mode === 'qr'" class="bg-black rounded-xl overflow-hidden shadow-md relative">
                <div id="reader" class="w-full h-64 bg-black"></div>
                <p class="text-[10px] text-gray-400 text-center py-2 bg-white border-t border-gray-100">
                    Arahkan kamera ke QR Code Santri
                </p>
            </div>

            {{-- NOTIFIKASI --}}
            <div x-show="errorMessage" x-transition class="mt-2 bg-red-100 text-red-700 px-3 py-2 rounded-lg text-xs font-bold text-center">
                <span x-text="errorMessage"></span>
            </div>
            <div x-show="successMessage" x-transition class="mt-2 bg-green-100 text-green-700 px-3 py-2 rounded-lg text-xs font-bold text-center">
                <span x-text="successMessage"></span>
            </div>
        </div>

        {{-- 3. LIST SANTRI --}}
        <div class="flex-grow px-4 mt-4 pb-20">
            
            <div class="flex gap-2 mb-3 overflow-x-auto no-scrollbar">
                <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-gray-800 text-white' : 'bg-white text-gray-600 border border-gray-200'" class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition">
                    Semua (<span x-text="students.length"></span>)
                </button>
                <button @click="filter = 'hadir'" :class="filter === 'hadir' ? 'bg-emerald-500 text-white' : 'bg-white text-gray-600 border border-gray-200'" class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition">
                    Hadir (<span x-text="countStatus('H')"></span>)
                </button>
                <button @click="filter = 'belum'" :class="filter === 'belum' ? 'bg-red-500 text-white' : 'bg-white text-gray-600 border border-gray-200'" class="px-4 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition">
                    Belum (<span x-text="countStatus(null)"></span>)
                </button>
            </div>

            <div class="space-y-2">
                <template x-for="student in filteredStudents" :key="student.id">
                    <div class="bg-white p-3 rounded-xl border shadow-sm flex justify-between items-center transition-all"
                         :class="student.status_temp === 'H' ? 'border-emerald-500 bg-emerald-50' : (student.status_temp === 'I' || student.status_temp === 'S' ? 'border-blue-300 bg-blue-50' : 'border-gray-100')">
                        
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold"
                                 :class="student.status_temp === 'H' ? 'bg-emerald-200 text-emerald-800' : 'bg-gray-200 text-gray-600'">
                                <span x-text="getInitials(student.full_name)"></span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800" x-text="student.full_name"></p>
                                <p class="text-[10px] text-gray-500" x-text="student.nis"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-1">
                            <template x-if="!student.status_temp">
                                <button @click="markManual(student.id, 'H')" class="px-3 py-1 bg-gray-100 hover:bg-emerald-100 text-gray-400 hover:text-emerald-600 rounded-lg text-xs font-bold transition">
                                    Absen?
                                </button>
                            </template>
                            
                            <template x-if="student.status_temp === 'H'">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Hadir
                                </span>
                            </template>

                            <div class="relative" x-data="{ openOpt: false }">
                                <button @click="openOpt = !openOpt" class="p-1 text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                </button>
                                <div x-show="openOpt" @click.away="openOpt = false" class="absolute right-0 mt-1 w-32 bg-white rounded-lg shadow-lg border border-gray-100 z-50 py-1" style="display: none;">
                                    <button @click="markManual(student.id, 'I'); openOpt=false" class="block w-full text-left px-4 py-2 text-xs text-blue-600 hover:bg-gray-50">Set Izin</button>
                                    <button @click="markManual(student.id, 'S'); openOpt=false" class="block w-full text-left px-4 py-2 text-xs text-orange-600 hover:bg-gray-50">Set Sakit</button>
                                    <button @click="markManual(student.id, null); openOpt=false" class="block w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-gray-50">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- 4. FOOTER ACTION --}}
        <div class="fixed bottom-0 w-full max-w-md bg-white border-t border-gray-200 p-4 z-50">
            <form method="POST" action="{{ route('ustadz.absensi.store', $jadwal->id) }}">
                @csrf
                <template x-for="student in students" :key="student.id">
                    <input type="hidden" :name="'attendance[' + student.id + ']'" :value="student.status_temp">
                </template>
                <input type="hidden" name="metode" :value="mode">

                <div class="flex justify-between items-center gap-4">
                    <div class="text-xs text-gray-500">
                        <span class="block font-bold text-gray-800">Ringkasan:</span>
                        Hadir: <span x-text="countStatus('H')"></span>, 
                        Alpha: <span x-text="countStatus(null)"></span>
                    </div>
                    <button type="submit" onclick="return confirm('Simpan data? Kosong = Alpha')" class="bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg hover:bg-emerald-700 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('absensiSystem', (initialStudents) => ({
                mode: 'rfid', 
                filter: 'all', 
                scanInput: '',
                students: initialStudents.map(s => ({ ...s, status_temp: null })), 
                errorMessage: '',
                successMessage: '',
                scanner: null, // Instance Scanner

                init() {
                    this.focusInput();
                    
                    // Watch Mode Change
                    this.$watch('mode', (value) => {
                        if (value === 'qr') {
                            this.$nextTick(() => this.startScanner());
                        } else {
                            this.stopScanner();
                            this.focusInput();
                        }
                    });

                    setInterval(() => { if(this.mode === 'rfid') this.focusInput(); }, 5000);
                },

                focusInput() {
                    if (this.$refs.rfidInput) this.$refs.rfidInput.focus();
                },

                // --- LOGIKA QR SCANNER ---
                startScanner() {
                    if (this.scanner) {
                        // Jika sudah ada, clear dulu biar gak double
                        this.scanner.clear();
                    }

                    this.scanner = new Html5QrcodeScanner("reader", { 
                        fps: 10, 
                        qrbox: 250,
                        rememberLastUsedCamera: true,
                        aspectRatio: 1.0
                    });

                    this.scanner.render(this.onScanSuccess.bind(this), this.onScanFailure.bind(this));
                },

                stopScanner() {
                    if (this.scanner) {
                        this.scanner.clear().catch(error => console.error("Failed to clear scanner", error));
                        this.scanner = null;
                    }
                },

                onScanSuccess(decodedText, decodedResult) {
                    // Mainkan suara Beep (Opsional)
                    // new Audio('/sounds/beep.mp3').play(); 
                    
                    // Masukkan ke input dan proses
                    this.scanInput = decodedText;
                    this.processScan();
                    
                    // Opsional: pause sebentar biar gak scan berulang-ulang cepat
                    this.scanner.pause();
                    setTimeout(() => this.scanner.resume(), 1500);
                },

                onScanFailure(error) {
                    // Biarkan kosong agar tidak spam console
                },
                // -------------------------

                get filteredStudents() {
                    if (this.filter === 'hadir') return this.students.filter(s => s.status_temp === 'H');
                    if (this.filter === 'belum') return this.students.filter(s => !s.status_temp);
                    return this.students;
                },

                countStatus(status) {
                    if (status === null) return this.students.filter(s => !s.status_temp).length;
                    return this.students.filter(s => s.status_temp === status).length;
                },

                getInitials(name) {
                    return name.match(/(\b\S)?/g).join("").match(/(^\S|\S$)?/g).join("").toUpperCase();
                },

                processScan() {
                    const code = this.scanInput.trim();
                    if (!code) return;

                    // Cari berdasarkan RFID atau Token QR (bisa NIS juga jika QR isinya NIS)
                    // Pastikan di DB santri ada kolom qrcode_token atau gunakan nis sebagai fallback
                    const studentIndex = this.students.findIndex(s => 
                        s.rfid_uid == code || s.nis == code || s.qrcode_token == code
                    );

                    if (studentIndex !== -1) {
                        const student = this.students[studentIndex];
                        if (student.status_temp === 'H') {
                            this.showError(student.full_name + ' sudah absen!');
                        } else {
                            this.students[studentIndex].status_temp = 'H';
                            this.showSuccess(student.full_name + ' Hadir');
                        }
                    } else {
                        this.showError('Data tidak ditemukan!');
                    }
                    this.scanInput = ''; 
                },

                markManual(id, status) {
                    const index = this.students.findIndex(s => s.id === id);
                    if (index !== -1) {
                        this.students[index].status_temp = status;
                    }
                },

                showError(msg) {
                    this.errorMessage = msg;
                    this.successMessage = '';
                    setTimeout(() => this.errorMessage = '', 3000);
                },

                showSuccess(msg) {
                    this.successMessage = msg;
                    this.errorMessage = '';
                    setTimeout(() => this.successMessage = '', 3000);
                }
            }));
        });
    </script>
</x-app-layout>