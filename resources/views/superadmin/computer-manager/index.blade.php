<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Komputer Lab') }}
            </h2>
            <button onclick="window.location.reload();" class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh Data
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Nama PC</th>
                                    <th scope="col" class="px-6 py-3">Password Saat Ini</th>
                                    <th scope="col" class="px-6 py-3">Terakhir Update</th>
                                    <th scope="col" class="px-6 py-3 text-center">Aksi Kontrol</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($computers as $pc)
                                    @php
                                        // Anggap Online jika update terakhir kurang dari 2 menit yang lalu
                                        $isOnline = $pc->last_seen >= now()->subMinutes(2);
                                    @endphp

                                    <tr class="bg-white border-b hover:bg-gray-50 transition duration-150">
                                        
                                        <td class="px-6 py-4">
                                            @if($pc->pending_command)
                                                <span class="inline-flex items-center bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                    <span class="w-2 h-2 mr-1 bg-yellow-500 rounded-full animate-pulse"></span>
                                                    Menunggu {{ ucfirst($pc->pending_command) }}
                                                </span>
                                            @elseif($isOnline)
                                                <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                    <span class="w-2 h-2 mr-1 bg-green-500 rounded-full"></span>
                                                    Online
                                                </span>
                                            @else
                                                <span class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                    <span class="w-2 h-2 mr-1 bg-red-500 rounded-full"></span>
                                                    Offline
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            <div class="text-base">{{ $pc->pc_name }}</div>
                                            <div class="text-xs text-gray-400">{{ $pc->ip_address ?? 'IP Tidak Terdeteksi' }}</div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <code class="text-lg font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded border border-blue-200 tracking-wider font-mono select-all">
                                                    {{ $pc->password }}
                                                </code>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-gray-900">{{ $pc->last_seen->diffForHumans() }}</div>
                                            <div class="text-xs text-gray-400">{{ $pc->last_seen->format('d M Y H:i:s') }}</div>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center space-x-2">
                                                
                                                <form action="{{ route('superadmin.computer.command', $pc->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="command" value="logout">
                                                    <button type="submit" 
                                                            onclick="return confirm('Apakah Anda yakin ingin MELOGOUT user di {{ $pc->pc_name }}? Data yang belum disimpan user mungkin hilang.')"
                                                            class="inline-flex items-center px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition ease-in-out duration-150"
                                                            title="Logout User">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                                        Logout
                                                    </button>
                                                </form>

                                                <form action="{{ route('superadmin.computer.command', $pc->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="command" value="shutdown">
                                                    <button type="submit" 
                                                            onclick="return confirm('⚠️ PERINGATAN KERAS:\nApakah Anda yakin ingin MEMATIKAN {{ $pc->pc_name }}?\nKomputer akan mati total dalam beberapa detik.')"
                                                            class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150"
                                                            title="Matikan Komputer">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                        Matikan
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            <p class="mt-2 text-sm font-medium">Belum ada komputer yang terhubung.</p>
                                            <p class="text-xs">Pastikan script Python sudah dijalankan di komputer lab.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>