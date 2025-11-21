<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                    Jadwal Ujian & Kegiatan
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola kalender akademik, ujian (UTS/UAS), dan input nilai.</p>
            </div>
            
            @if($tahunAjaranAktif)
                <div class="hidden md:flex items-center gap-3 text-sm text-gray-600 bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Tahun Ajaran: <span class="font-bold text-gray-900">{{ $tahunAjaranAktif->nama_tahun_ajaran }}</span></span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ACTION BAR --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                <div class="relative w-full sm:w-96 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-amber-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <form method="GET">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm shadow-sm transition" 
                               placeholder="Cari kegiatan (UTS, UAS)...">
                    </form>
                </div>

                @if($tahunAjaranAktif)
                <button onclick="openModal('create')" 
                   class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 cursor-pointer">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Kegiatan
                </button>
                @else
                <span class="text-sm text-red-500 bg-red-50 px-3 py-1 rounded-lg border border-red-100">
                    Aktifkan Tahun Ajaran terlebih dahulu.
                </span>
                @endif
            </div>

            {{-- TABLE CONTENT --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @if($kegiatans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/80">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Kegiatan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode & Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($kegiatans as $kegiatan)
                                    @php
                                        $today = now()->startOfDay();
                                        $tglMulai = \Carbon\Carbon::parse($kegiatan->tanggal_mulai);
                                        $tglSelesai = \Carbon\Carbon::parse($kegiatan->tanggal_selesai);
                                        
                                        // Logika Status
                                        if ($today->lt($tglMulai)) {
                                            $statusLabel = 'Akan Datang';
                                            $statusClass = 'bg-blue-100 text-blue-700 border-blue-200';
                                            $dotClass = 'bg-blue-500';
                                        } elseif ($today->gt($tglSelesai)) {
                                            $statusLabel = 'Selesai';
                                            $statusClass = 'bg-gray-100 text-gray-600 border-gray-200';
                                            $dotClass = 'bg-gray-500';
                                        } else {
                                            $statusLabel = 'Sedang Berlangsung';
                                            $statusClass = 'bg-green-100 text-green-700 border-green-200 animate-pulse';
                                            $dotClass = 'bg-green-500';
                                        }

                                        // Warna Tipe
                                        $tipeColors = [
                                            'UTS' => 'text-indigo-600 bg-indigo-50 border-indigo-100',
                                            'UAS' => 'text-rose-600 bg-rose-50 border-rose-100',
                                            'Harian' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                            'Lainnya' => 'text-gray-600 bg-gray-50 border-gray-100'
                                        ];
                                        $tipeClass = $tipeColors[$kegiatan->tipe] ?? $tipeColors['Lainnya'];
                                    @endphp

                                    <tr class="group hover:bg-gray-50 transition-colors duration-200">
                                        {{-- Kolom 1: Nama & Tipe --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 mt-1">
                                                     <span class="px-2 py-1 text-[10px] font-bold uppercase rounded-md border {{ $tipeClass }}">
                                                        {{ $kegiatan->tipe }}
                                                     </span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900">{{ $kegiatan->nama_kegiatan }}</div>
                                                    @if($kegiatan->keterangan)
                                                        <div class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $kegiatan->keterangan }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Kolom 2: Tanggal & Status --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col gap-1.5">
                                                <div class="flex items-center text-sm text-gray-600 font-medium">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    {{ $tglMulai->format('d M') }} - {{ $tglSelesai->format('d M Y') }}
                                                </div>
                                                <div class="flex">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClass }}">
                                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $dotClass }}"></span>
                                                        {{ $statusLabel }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Kolom 3: Aksi --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-3">
                                                {{-- Tombol Kelola Nilai (Primary Action) --}}
                                                <a href="{{ route('sekolah.admin.kegiatan-akademik.kelola.kelas', $kegiatan->id) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-white border border-indigo-200 rounded-lg text-indigo-600 text-xs font-bold hover:bg-indigo-50 hover:border-indigo-300 shadow-sm transition">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                    Kelola Nilai
                                                </a>

                                                <div class="h-4 w-px bg-gray-300"></div>

                                                {{-- Edit --}}
                                                <button onclick="openModal('edit', {{ $kegiatan }})" class="text-gray-400 hover:text-amber-500 transition" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>

                                                {{-- Delete --}}
                                                <form action="{{ route('sekolah.admin.kegiatan-akademik.destroy', $kegiatan->id) }}" method="POST" class="delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn-delete text-gray-400 hover:text-red-500 transition" title="Hapus">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                        {{ $kegiatans->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                        <div class="bg-gray-50 rounded-full p-6 mb-4">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Belum ada Kegiatan Akademik</h3>
                        <p class="text-sm text-gray-500 mt-1 max-w-sm">Tambahkan jadwal ujian seperti UTS, UAS, atau kegiatan lainnya untuk mulai mengelola nilai.</p>
                        <button onclick="openModal('create')" class="mt-4 inline-flex items-center px-5 py-2.5 bg-gray-900 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 transition">
                            + Buat Kegiatan Baru
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL COMPONENT --}}
    <div id="kegiatanModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95" id="modalPanel">
                <div class="bg-gray-50/50 px-4 py-4 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold leading-6 text-gray-900" id="modalTitle">Tambah Kegiatan</h3>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form id="kegiatanForm" method="POST">
                    @csrf
                    <div id="methodSpoof"></div>
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        
                        <div>
                            <label for="nama_kegiatan" class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" id="nama_kegiatan" required placeholder="Cth: Penilaian Tengah Semester Ganjil"
                                   class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm py-2.5"
                                   value="{{ old('nama_kegiatan') }}">
                        </div>

                        <div>
                            <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe Kegiatan</label>
                            <select name="tipe" id="tipe" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm py-2.5">
                                <option value="UTS">UTS (Ujian Tengah Semester)</option>
                                <option value="UAS">UAS (Ujian Akhir Semester)</option>
                                <option value="Harian">Harian / Quiz</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" required
                                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm py-2.5"
                                       value="{{ old('tanggal_mulai') }}">
                            </div>
                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" required
                                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm py-2.5"
                                       value="{{ old('tanggal_selesai') }}">
                            </div>
                        </div>

                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                            <textarea name="keterangan" id="keterangan" rows="2" 
                                      class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                                      placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                        </div>

                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100 gap-2">
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 sm:ml-3 sm:w-auto transition-all">
                            Simpan
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const modal = document.getElementById('kegiatanModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');
            const form = document.getElementById('kegiatanForm');
            const title = document.getElementById('modalTitle');
            const methodSpoof = document.getElementById('methodSpoof');
            
            const baseUpdateUrl = "{{ route('sekolah.admin.kegiatan-akademik.index') }}";
            const storeUrl = "{{ route('sekolah.admin.kegiatan-akademik.store') }}";

            function openModal(mode, data = null) {
                form.reset();
                methodSpoof.innerHTML = '';

                if (mode === 'create') {
                    title.innerText = 'Tambah Kegiatan Baru';
                    form.action = storeUrl;
                } else if (mode === 'edit' && data) {
                    title.innerText = 'Edit Kegiatan';
                    form.action = `${baseUpdateUrl}/${data.id}`;
                    methodSpoof.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                    
                    document.getElementById('nama_kegiatan').value = data.nama_kegiatan;
                    document.getElementById('tipe').value = data.tipe;
                    document.getElementById('tanggal_mulai').value = data.tanggal_mulai;
                    document.getElementById('tanggal_selesai').value = data.tanggal_selesai;
                    document.getElementById('keterangan').value = data.keterangan || '';
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

            @if($errors->any())
                document.addEventListener('DOMContentLoaded', function() {
                    openModal('create');
                    document.getElementById('nama_kegiatan').value = "{{ old('nama_kegiatan') }}";
                    document.getElementById('tipe').value = "{{ old('tipe') }}";
                    document.getElementById('tanggal_mulai').value = "{{ old('tanggal_mulai') }}";
                    document.getElementById('tanggal_selesai').value = "{{ old('tanggal_selesai') }}";
                    document.getElementById('keterangan').value = "{{ old('keterangan') }}";
                });
            @endif

            document.addEventListener('DOMContentLoaded', function () {
                const deleteButtons = document.querySelectorAll('.btn-delete');
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const form = this.closest('.delete-form');
                        Swal.fire({
                            title: 'Hapus Kegiatan?',
                            text: "Data nilai terkait mungkin akan ikut terhapus!",
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