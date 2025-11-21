<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                    Jadwal Pelajaran
                </h2>
                <p class="text-sm text-gray-500 mt-1">Atur jadwal KBM (Kegiatan Belajar Mengajar) per kelas.</p>
            </div>
            
            <div class="hidden md:flex items-center gap-3 text-sm text-gray-600 bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>Total Jadwal: <span class="font-bold text-gray-900">{{ $jadwals->total() }}</span> Sesi</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ACTION BAR --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                {{-- Search / Filter --}}
                <div class="relative w-full sm:w-96 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-purple-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <form method="GET">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm shadow-sm transition" 
                               placeholder="Cari guru, mapel, atau kelas...">
                    </form>
                </div>

                {{-- TRIGGER MODAL TAMBAH --}}
                <button onclick="openModal('create')" 
                   class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 cursor-pointer">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Jadwal
                </button>
            </div>

            {{-- TABLE CONTENT --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @if($jadwals->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Hari & Jam</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Guru Pengajar</th>
                                    <th class="relative px-6 py-4"><span class="sr-only">Aksi</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($jadwals as $jadwal)
                                    @php
                                        // Warna Label Hari
                                        $dayColors = [
                                            'Senin' => 'bg-red-100 text-red-700',
                                            'Selasa' => 'bg-orange-100 text-orange-700',
                                            'Rabu' => 'bg-yellow-100 text-yellow-700',
                                            'Kamis' => 'bg-green-100 text-green-700',
                                            'Jumat' => 'bg-teal-100 text-teal-700',
                                            'Sabtu' => 'bg-blue-100 text-blue-700',
                                            'Minggu' => 'bg-gray-100 text-gray-700',
                                        ];
                                        $badgeColor = $dayColors[$jadwal->hari] ?? 'bg-gray-100 text-gray-700';
                                    @endphp

                                    <tr class="group hover:bg-gray-50 transition-colors duration-200">
                                        {{-- Kolom 1: Hari & Jam --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col items-start gap-1">
                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-bold rounded-md {{ $badgeColor }}">
                                                    {{ $jadwal->hari }}
                                                </span>
                                                <div class="flex items-center text-sm text-gray-500 font-mono">
                                                    <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Kolom 2: Kelas --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $jadwal->kelas->nama_kelas ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">Tingkat {{ $jadwal->kelas->tingkat ?? '' }}</div>
                                        </td>

                                        {{-- Kolom 3: Mapel --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-purple-700">{{ $jadwal->mataPelajaran->nama_mapel ?? '-' }}</div>
                                            @if($jadwal->mataPelajaran && $jadwal->mataPelajaran->kode_mapel)
                                                <div class="text-xs text-gray-400 font-mono mt-0.5">{{ $jadwal->mataPelajaran->kode_mapel }}</div>
                                            @endif
                                        </td>

                                        {{-- Kolom 4: Guru --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                                    {{ substr($jadwal->guru->name ?? '?', 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $jadwal->guru->name ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Kolom 5: Aksi --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                {{-- BUTTON EDIT --}}
                                                <button onclick="openModal('edit', {{ $jadwal }})" 
                                                   class="p-2 bg-white border border-gray-200 rounded-lg text-indigo-600 hover:bg-indigo-50 hover:border-indigo-300 shadow-sm transition" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                
                                                {{-- BUTTON DELETE (SWEETALERT) --}}
                                                <form action="{{ route('sekolah.admin.jadwal-pelajaran.destroy', $jadwal->id) }}" method="POST" class="delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn-delete p-2 bg-white border border-gray-200 rounded-lg text-red-600 hover:bg-red-50 hover:border-red-300 shadow-sm transition" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                        {{ $jadwals->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                        <div class="bg-gray-50 rounded-full p-6 mb-4">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Jadwal Kosong</h3>
                        <button onclick="openModal('create')" class="mt-4 inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition">
                            + Buat Jadwal Baru
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL COMPONENT --}}
    <div id="jadwalModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
        
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl opacity-0 scale-95" id="modalPanel">
                
                {{-- Header Modal --}}
                <div class="bg-gray-50/50 px-4 py-4 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold leading-6 text-gray-900" id="modalTitle">Tambah Jadwal Pelajaran</h3>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Form Body --}}
                <form id="jadwalForm" method="POST">
                    @csrf
                    <div id="methodSpoof"></div>

                    <div class="px-4 py-5 sm:p-6">
                        
                        {{-- INFO TAHUN AJARAN (Optional Visual) --}}
                        <div class="mb-5 p-3 bg-blue-50 border border-blue-100 rounded-xl flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="text-xs text-blue-800">
                                <span class="font-bold">Info:</span> Jadwal ini akan berlaku untuk Tahun Ajaran Aktif saat ini.
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            {{-- Pilih Kelas --}}
                            <div class="col-span-1">
                                <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                                <select name="kelas_id" id="kelas_id" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm py-2.5" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Pilih Guru --}}
                            <div class="col-span-1">
                                <label for="guru_user_id" class="block text-sm font-medium text-gray-700 mb-1">Guru Pengajar</label>
                                <select name="guru_user_id" id="guru_user_id" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm py-2.5" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach($guruList as $guru)
                                        <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Pilih Mapel --}}
                            <div class="col-span-2">
                                <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm py-2.5" required>
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach($mapelList as $mapel)
                                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }} {{ $mapel->kode_mapel ? '('.$mapel->kode_mapel.')' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Pilih Hari --}}
                            <div class="col-span-2 md:col-span-1">
                                <label for="hari" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                                <select name="hari" id="hari" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm py-2.5" required>
                                    <option value="">-- Pilih Hari --</option>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                                        <option value="{{ $h }}">{{ $h }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Waktu (Jam Mulai - Selesai) --}}
                            <div class="col-span-2 md:col-span-1 flex gap-2">
                                <div class="w-1/2">
                                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-1">Mulai</label>
                                    <input type="time" name="jam_mulai" id="jam_mulai" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm py-2.5" required>
                                </div>
                                <div class="w-1/2">
                                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-1">Selesai</label>
                                    <input type="time" name="jam_selesai" id="jam_selesai" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm py-2.5" required>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100 gap-2">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 sm:ml-3 sm:w-auto transition-all">
                            Simpan Jadwal
                        </button>
                        <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- SweetAlert2 CDN --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            /* --- MODAL LOGIC --- */
            const modal = document.getElementById('jadwalModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');
            const form = document.getElementById('jadwalForm');
            const title = document.getElementById('modalTitle');
            const methodSpoof = document.getElementById('methodSpoof');
            
            const baseUpdateUrl = "{{ route('sekolah.admin.jadwal-pelajaran.index') }}";
            const storeUrl = "{{ route('sekolah.admin.jadwal-pelajaran.store') }}";

            function openModal(mode, data = null) {
                form.reset();
                methodSpoof.innerHTML = '';

                if (mode === 'create') {
                    title.innerText = 'Tambah Jadwal Pelajaran';
                    form.action = storeUrl;
                } else if (mode === 'edit' && data) {
                    title.innerText = 'Edit Jadwal Pelajaran';
                    form.action = `${baseUpdateUrl}/${data.id}`;
                    methodSpoof.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                    
                    // Populate Data
                    document.getElementById('kelas_id').value = data.kelas_id;
                    document.getElementById('guru_user_id').value = data.guru_user_id;
                    document.getElementById('mata_pelajaran_id').value = data.mata_pelajaran_id;
                    document.getElementById('hari').value = data.hari;
                    // Format Time: Database usually H:i:s, Input time needs H:i
                    document.getElementById('jam_mulai').value = data.jam_mulai.substring(0, 5);
                    document.getElementById('jam_selesai').value = data.jam_selesai.substring(0, 5);
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
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            // Handle Validation Errors (Re-open modal if errors exist)
            @if($errors->any())
                document.addEventListener('DOMContentLoaded', function() {
                    openModal('create'); // Default to create mode on error for simplicity
                    // Manually repopulate old values if needed
                    document.getElementById('kelas_id').value = "{{ old('kelas_id') }}";
                    document.getElementById('guru_user_id').value = "{{ old('guru_user_id') }}";
                    document.getElementById('mata_pelajaran_id').value = "{{ old('mata_pelajaran_id') }}";
                    document.getElementById('hari').value = "{{ old('hari') }}";
                    document.getElementById('jam_mulai').value = "{{ old('jam_mulai') }}";
                    document.getElementById('jam_selesai').value = "{{ old('jam_selesai') }}";
                });
            @endif

            /* --- SWEETALERT DELETE --- */
            document.addEventListener('DOMContentLoaded', function () {
                const deleteButtons = document.querySelectorAll('.btn-delete');
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const form = this.closest('.delete-form');
                        Swal.fire({
                            title: 'Hapus Jadwal?',
                            text: "Jadwal ini akan dihapus permanen.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal',
                            background: '#ffffff',
                            customClass: { popup: 'rounded-2xl shadow-xl', confirmButton: 'rounded-lg px-4 py-2', cancelButton: 'rounded-lg px-4 py-2' }
                        }).then((result) => {
                            if (result.isConfirmed) form.submit();
                        });
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>