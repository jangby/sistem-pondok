<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                    <span class="text-3xl">👨‍💼</span> Manajemen Admin Sekolah
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola akun operator untuk setiap unit sekolah.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-500 shadow-sm">
                    Total Admin: <span class="text-blue-600 font-black text-sm ml-1">{{ $users->total() }}</span>
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ACTION BAR --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                {{-- Search --}}
                <div class="relative w-full sm:w-96 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <form method="GET">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm transition" 
                               placeholder="Cari nama atau email...">
                    </form>
                </div>

                {{-- Add Button --}}
                <button onclick="openModal('create')" 
                   class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-blue-200 transition-all duration-200 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Tambah Admin Baru
                </button>
            </div>

            {{-- LIST USERS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                @if($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Profil Admin</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kontak</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Penugasan Sekolah</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($users as $user)
                                    <tr class="group hover:bg-blue-50/30 transition-colors">
                                        {{-- Profil --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-bold border border-blue-200">
                                                    {{ substr($user->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-800 text-sm">{{ $user->name }}</div>
                                                    <div class="text-xs text-gray-400">Terdaftar: {{ $user->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Kontak --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col gap-1">
                                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    {{ $user->email }}
                                                </div>
                                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                    {{ $user->telepon ?? '-' }}
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Sekolah --}}
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                @forelse($user->sekolahs as $sekolah)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                        {{ $sekolah->nama_sekolah }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-gray-400 italic">Belum ditugaskan</span>
                                                @endforelse
                                            </div>
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- Data Sekolah ID perlu di-pass manual --}}
                                                @php
                                                    $userJson = $user->toArray();
                                                    $userJson['sekolah_id'] = $user->sekolahs->first()->id ?? '';
                                                @endphp
                                                
                                                <button onclick='openModal("edit", @json($userJson))' 
                                                        class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                
                                                <form action="{{ route('sekolah.superadmin.admin-sekolah.destroy', $user->id) }}" method="POST" class="delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn-delete p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
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
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Belum ada Admin Sekolah</h3>
                        <p class="text-gray-500 text-sm mt-1 mb-4">Tambahkan akun untuk mengelola unit sekolah.</p>
                        <button onclick="openModal('create')" class="px-5 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition">
                            + Tambah Admin
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL POP-UP --}}
    <div id="adminModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 scale-95" id="modalPanel">
                
                <div class="bg-white px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-black text-gray-800" id="modalTitle">Tambah Admin Baru</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Lengkapi data akun pengelola.</p>
                    </div>
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form id="adminForm" method="POST">
                    @csrf
                    <div id="methodSpoof"></div>

                    <div class="px-6 py-6 space-y-5">
                        
                        {{-- Nama --}}
                        <div>
                            <label for="name" class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" required placeholder="Nama Admin"
                                   class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                        </div>

                        {{-- Email & Telp --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label>
                                <input type="email" name="email" id="email" required placeholder="email@sekolah.com"
                                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                            </div>
                            <div>
                                <label for="telepon" class="block text-xs font-bold text-gray-500 uppercase mb-1">No. WhatsApp</label>
                                <input type="text" name="telepon" id="telepon" required placeholder="08..."
                                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                            </div>
                        </div>

                        {{-- Sekolah Assignment --}}
                        <div>
                            <label for="sekolah_id" class="block text-xs font-bold text-gray-500 uppercase mb-1">Tugaskan di Sekolah</label>
                            <select name="sekolah_id" id="sekolah_id" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                <option value="">-- Pilih Unit Sekolah --</option>
                                @foreach($sekolahs as $sekolah)
                                    <option value="{{ $sekolah->id }}">{{ $sekolah->nama_sekolah }} ({{ $sekolah->tingkat }})</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Admin ini akan memiliki akses penuh ke data sekolah yang dipilih.</p>
                        </div>

                        {{-- Password --}}
                        <div class="pt-2 border-t border-gray-100">
                            <p class="text-xs font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Keamanan Akun
                            </p>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-xs text-gray-500 mb-1">Password Baru</label>
                                    <input type="password" name="password" id="password" placeholder="Minimal 8 karakter"
                                           class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-xs text-gray-500 mb-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password"
                                           class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                                </div>
                            </div>
                            <p id="password-hint" class="text-xs text-gray-400 mt-2 hidden">Biarkan kosong jika tidak ingin mengubah password.</p>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3 border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-md hover:bg-blue-700 sm:w-auto transition-all">
                            Simpan Data
                        </button>
                        <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all">
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
        const modal = document.getElementById('adminModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');
        const form = document.getElementById('adminForm');
        const title = document.getElementById('modalTitle');
        const methodSpoof = document.getElementById('methodSpoof');
        const passwordHint = document.getElementById('password-hint');
        
        // Sesuaikan route name dengan yang ada di web.php (biasanya admin-sekolah.index / .store)
        // Jika resource name 'admin-sekolah', maka route update adalah admin-sekolah.update dengan param {admin_sekolah}
        const baseUpdateUrl = "{{ url('sekolah-superadmin/admin-sekolah') }}"; 
        const storeUrl = "{{ route('sekolah.superadmin.admin-sekolah.store') }}";

        function openModal(mode, data = null) {
            form.reset();
            methodSpoof.innerHTML = '';

            if (mode === 'create') {
                title.innerText = 'Tambah Admin Baru';
                form.action = storeUrl;
                passwordHint.classList.add('hidden');
                document.getElementById('password').required = true;
                document.getElementById('password_confirmation').required = true;
            } else if (mode === 'edit' && data) {
                title.innerText = 'Edit Data Admin';
                form.action = `${baseUpdateUrl}/${data.id}`;
                methodSpoof.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                
                document.getElementById('name').value = data.name;
                document.getElementById('email').value = data.email;
                document.getElementById('telepon').value = data.telepon;
                // Populate select school (Asumsi user punya data sekolah_id yang kita inject di blade)
                document.getElementById('sekolah_id').value = data.sekolah_id || '';

                // Password optional saat edit
                passwordHint.classList.remove('hidden');
                document.getElementById('password').required = false;
                document.getElementById('password_confirmation').required = false;
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

        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Hapus Admin?',
                        text: "Akun ini tidak akan bisa login lagi.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });

        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                openModal('create');
                // Repopulate old values
                document.getElementById('name').value = "{{ old('name') }}";
                document.getElementById('email').value = "{{ old('email') }}";
                document.getElementById('telepon').value = "{{ old('telepon') }}";
                document.getElementById('sekolah_id').value = "{{ old('sekolah_id') }}";
            });
        @endif
    </script>
    @endpush
</x-app-layout>