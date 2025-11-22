<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cetak Kartu Ujian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Filter Cetak Kartu</h3>
                    <a href="{{ route('pendidikan.admin.kartu-template.index') }}" class="text-sm text-blue-600 hover:underline">
                        &rarr; Kelola Desain Kartu
                    </a>
                </div>

                <form action="{{ route('pendidikan.admin.kartu.generate') }}" method="POST" target="_blank">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas (Mustawa)</label>
                            <select name="mustawa_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($mustawas as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama }} (Tingkat {{ $m->tingkat }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Desain Kartu</label>
                            <select name="template_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Desain --</option>
                                @foreach($templates as $t)
                                    <option value="{{ $t->id }}">{{ $t->nama_template }} ({{ $t->ukuran_kertas }})</option>
                                @endforeach
                            </select>
                            
                            @if($templates->isEmpty())
                                <p class="text-xs text-red-500 mt-1">
                                    Belum ada desain. 
                                    <a href="{{ route('pendidikan.admin.kartu-template.create') }}" class="underline font-bold">Buat dulu di sini.</a>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-md border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-600 mb-2">Opsi Cetak</h4>
                        <div class="flex items-center gap-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="download" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Langsung Download PDF</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow-lg flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Generate Kartu</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>