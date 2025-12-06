<x-app-layout hide-nav>
    <div class="min-h-screen bg-gray-50 pb-24 font-sans relative">

        {{-- 1. STICKY HEADER & SEARCH --}}
        <div class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm transition-all duration-300">
            <div class="px-5 py-4">
                <div class="flex items-center justify-between mb-3">
                    <h1 class="text-lg font-extrabold text-gray-800 tracking-tight">Daftar Komputer</h1>
                    <div class="text-xs font-bold px-3 py-1 bg-blue-100 text-blue-700 rounded-full border border-blue-200">
                        Total: {{ $computers->count() }}
                    </div>
                </div>

                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input type="text" id="searchPc" onkeyup="filterComputers()" 
                        class="block w-full pl-10 pr-4 py-3 bg-gray-100 border-transparent text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all shadow-sm" 
                        placeholder="Cari nomor komputer...">
                </div>
            </div>
        </div>

        {{-- 2. LIST KOMPUTER (GRID LAYOUT) --}}
        <div class="px-4 mt-4 grid grid-cols-1 gap-3" id="pcListContainer">
            @forelse($computers as $pc)
                @php
                    $isOnline = $pc->last_seen >= now()->subMinutes(2);
                @endphp

                <div onclick="openModalDetail('{{ $pc->id }}', '{{ $pc->pc_name }}', '{{ $pc->password }}', '{{ $isOnline }}', '{{ $pc->pending_command }}')" 
                     class="pc-item relative bg-white p-4 rounded-2xl shadow-sm border border-gray-100 active:scale-95 transition-transform duration-100 cursor-pointer overflow-hidden group">
                    
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isOnline ? 'bg-green-500' : 'bg-gray-300' }}"></div>

                    <div class="flex justify-between items-center pl-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full {{ $isOnline ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-lg">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <div>
                                <h3 class="pc-name font-bold text-gray-800 text-sm">{{ $pc->pc_name }}</h3>
                                <p class="text-[10px] {{ $isOnline ? 'text-green-600 font-semibold' : 'text-gray-400' }}">
                                    {{ $isOnline ? '• Online' : '• Offline' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col items-end">
                            <span class="text-[9px] text-gray-400 uppercase tracking-wider mb-0.5">Password</span>
                            <div class="bg-gray-50 border border-gray-200 text-gray-800 font-mono text-lg font-black px-3 py-1 rounded-lg tracking-widest group-hover:border-blue-300 group-hover:bg-blue-50 group-hover:text-blue-700 transition-colors">
                                {{ $pc->password ?? '---' }}
                            </div>
                        </div>
                    </div>

                    @if($pc->pending_command)
                    <div class="absolute inset-0 bg-white/60 flex items-center justify-center backdrop-blur-[1px]">
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm animate-pulse flex items-center gap-2">
                            <i class="fas fa-cog fa-spin"></i> Proses {{ ucfirst($pc->pending_command) }}...
                        </span>
                    </div>
                    @endif
                </div>

            @empty
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                        <i class="fas fa-wifi text-2xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm">Belum ada komputer terhubung.</p>
                </div>
            @endforelse
            
            <div id="noResult" class="hidden text-center py-8 text-gray-400 text-sm">
                Komputer tidak ditemukan.
            </div>
        </div>
    </div>

    {{-- 3. MODAL DETAIL & ACTION (HIDDEN BY DEFAULT) --}}
    <div id="pcModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalDetail()"></div>

        <div class="fixed inset-x-0 bottom-0 z-10 w-full overflow-hidden bg-white rounded-t-[30px] shadow-2xl transform transition-all sm:max-w-lg sm:mx-auto p-6 pb-safe">
            
            <div class="w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-6"></div>

            <div class="text-center mb-6">
                <div id="modalIcon" class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4 animate-bounce-short">
                    <i class="fas fa-desktop text-2xl text-blue-600"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight" id="modalPcName">PC-00</h3>
                <p class="text-sm text-gray-500 mt-1" id="modalStatus">Status: Memuat...</p>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200 text-center mb-6 relative overflow-hidden">
                <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-2">Password Login</p>
                <h2 class="text-4xl font-black text-gray-800 tracking-widest font-mono" id="modalPassword">------</h2>
                
                <button type="button" onclick="printTicket()" class="mt-4 w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-indigo-200 active:scale-95 transition-all">
                    <i class="fas fa-print"></i> Cetak Password (Thermal)
                </button>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <form id="formLogout" method="POST" action="">
                    @csrf
                    <input type="hidden" name="command" value="logout">
                    <button type="submit" class="w-full py-3.5 px-4 bg-yellow-50 text-yellow-700 font-bold rounded-xl border border-yellow-200 shadow-sm active:bg-yellow-100 active:scale-95 transition-all flex flex-col items-center justify-center gap-1">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                        <span class="text-xs">Logout Paksa</span>
                    </button>
                </form>

                <form id="formShutdown" method="POST" action="" onsubmit="return confirm('Matikan PC ini sekarang?')">
                    @csrf
                    <input type="hidden" name="command" value="shutdown">
                    <button type="submit" class="w-full py-3.5 px-4 bg-red-50 text-red-600 font-bold rounded-xl border border-red-200 shadow-sm active:bg-red-100 active:scale-95 transition-all flex flex-col items-center justify-center gap-1">
                        <i class="fas fa-power-off text-lg"></i>
                        <span class="text-xs">Shutdown</span>
                    </button>
                </form>
            </div>

            <button type="button" class="mt-4 w-full py-3 bg-white text-gray-500 font-bold rounded-xl border border-gray-200 active:bg-gray-50" onclick="closeModalDetail()">
                Tutup
            </button>
        </div>
    </div>

    @include('layouts.petugas-lab-nav')

    @push('scripts')
    <script>
        // --- 1. SEARCH FUNCTION ---
        function filterComputers() {
            let input = document.getElementById('searchPc').value.toUpperCase();
            let items = document.getElementsByClassName('pc-item');
            let found = 0;

            for (let i = 0; i < items.length; i++) {
                let name = items[i].getElementsByClassName("pc-name")[0].innerText;
                if (name.toUpperCase().indexOf(input) > -1) {
                    items[i].style.display = "";
                    found++;
                } else {
                    items[i].style.display = "none";
                }
            }
            document.getElementById("noResult").style.display = (found === 0) ? "block" : "none";
        }

        // --- 2. MODAL LOGIC ---
        function openModalDetail(id, name, password, isOnline, pendingCommand) {
            const modal = document.getElementById('pcModal');
            const icon = document.getElementById('modalIcon');
            
            // Set Data
            document.getElementById('modalPcName').innerText = name;
            document.getElementById('modalPassword').innerText = password;
            
            // Set Status UI
            let statusText = document.getElementById('modalStatus');
            if (isOnline == '1') {
                statusText.innerHTML = '<span class="text-green-600 font-bold">● Online</span> • Siap menerima perintah';
                icon.className = "mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4";
                icon.innerHTML = '<i class="fas fa-desktop text-2xl text-green-600"></i>';
            } else {
                statusText.innerHTML = '<span class="text-gray-400 font-bold">● Offline</span> • Terakhir terlihat baru saja';
                icon.className = "mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4";
                icon.innerHTML = '<i class="fas fa-desktop text-2xl text-gray-400"></i>';
            }

            // Set Form Actions URL
            // URL Pattern: /petugas-lab/komputer/{id}/command
            let baseUrl = "{{ route('petugas-lab.komputer.command', ':id') }}";
            let actionUrl = baseUrl.replace(':id', id);

            document.getElementById('formLogout').action = actionUrl;
            document.getElementById('formShutdown').action = actionUrl;

            // Show Modal
            modal.classList.remove('hidden');
        }

        function closeModalDetail() {
            document.getElementById('pcModal').classList.add('hidden');
        }

        // --- 3. PRINTER BRIDGE (WEBVIEW INTERFACE) ---
        function printTicket() {
            let pcName = document.getElementById('modalPcName').innerText;
            let password = document.getElementById('modalPassword').innerText;
            
            // Format Data untuk dikirim ke Android
            let printData = {
                pc: pcName,
                pass: password
            };

            // Cek apakah berjalan di dalam WebView Android yang sudah di-inject
            if (window.AndroidInterface && window.AndroidInterface.printThermal) {
                // Panggil fungsi Java/Kotlin di Android
                window.AndroidInterface.printThermal(pcName, password);
            } else {
                // Fallback untuk testing di Browser Desktop
                console.log("Mencetak ke Bluetooth: ", printData);
                alert("SIMULASI PRINT BLUETOOTH\n\nPC: " + pcName + "\nPassword: " + password + "\n\n(Fitur ini akan berjalan real-time di Aplikasi Android)");
            }
        }
    </script>
    @endpush

</x-app-layout>