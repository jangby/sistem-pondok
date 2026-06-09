<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cetak Rapor Santri') }}
            </h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('pendidikan.admin.rapor.export_biodata') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-md shadow-sm transition duration-150 ease-in-out">
                    <i class="fa fa-file-excel"></i> 
                    <span>Download Biodata Rapor</span>
                </a>
                <a href="{{ route('pendidikan.admin.rapor.export_nilai', ['jenis_ujian' => 'uas', 'semester' => 'genap']) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md shadow-sm transition duration-150 ease-in-out">
                    <i class="fa fa-file-excel"></i> 
                    <span>Download Nilai (UAS Genap)</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Filter Cetak Rapor</h3>
                    <a href="{{ route('pendidikan.admin.rapor-template.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                        <span>&rarr; Kelola Desain Template</span>
                    </a>
                </div>

                <form action="{{ route('pendidikan.admin.rapor.generate') }}" method="POST" target="_blank">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Semester</label>
                            <select name="semester" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="1">Semester 1 (Ganjil)</option>
                                <option value="2">Semester 2 (Genap - Kenaikan)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">*Smt 2 menentukan naik/tinggal kelas.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Desain Rapor</label>
                            <select name="template_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Pilih Desain --</option>
                                @foreach($templates as $t)
                                    <option value="{{ $t->id }}">{{ $t->nama_template }} ({{ $t->ukuran_kertas }})</option>
                                @endforeach
                            </select>
                            
                            @if($templates->isEmpty())
                                <p class="text-xs text-red-500 mt-1">
                                    Belum ada desain. 
                                    <a href="{{ route('pendidikan.admin.rapor-template.create') }}" class="underline font-bold text-red-600 hover:text-red-700">Buat dulu di sini.</a>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-md border border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-600 mb-2">Opsi Cetak</h4>
                        <div class="flex flex-wrap items-center gap-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="download" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Langsung Download PDF</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="show_cover" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Sertakan Halaman Cover</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md shadow-md flex items-center gap-2 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Generate & Cetak Rapor</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>