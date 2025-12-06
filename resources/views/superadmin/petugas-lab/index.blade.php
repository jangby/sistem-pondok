<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Petugas Lab') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-end px-4 sm:px-0">
                <a href="{{ route('superadmin.petugas-lab.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Tambah Petugas</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-0">
                @foreach($petugas as $p)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 relative group hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl">
                                {{ substr($p->name, 0, 1) }}
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $p->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $p->email }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded-md">
                                    Petugas Lab
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-between items-center border-t pt-4">
                            <a href="{{ route('superadmin.petugas-lab.edit', $p->id) }}" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                Edit Data
                            </a>
                            
                            <form action="{{ route('superadmin.petugas-lab.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin hapus petugas ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($petugas->isEmpty())
            <div class="text-center py-10">
                <p class="text-gray-500">Belum ada petugas. Silakan tambahkan.</p>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>