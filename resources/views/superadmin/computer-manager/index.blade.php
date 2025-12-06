<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Password Komputer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">
                            Daftar komputer yang terhubung dan password Windows dinamis saat ini.
                        </p>
                    </div>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nama PC</th>
                                    <th scope="col" class="px-6 py-3">Password Aktif</th>
                                    <th scope="col" class="px-6 py-3">IP Address</th>
                                    <th scope="col" class="px-6 py-3">Terakhir Update</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($computers as $pc)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $pc->pc_name }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-blue-600 font-bold text-lg">
                                        {{ $pc->password }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $pc->ip_address }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $pc->last_seen->diffForHumans() }}
                                        <br>
                                        <span class="text-xs text-gray-400">({{ $pc->last_seen->format('d M Y H:i') }})</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($pc->last_seen->diffInHours(now()) < 25)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">Online</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">Offline</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">Belum ada data komputer masuk.</td>
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