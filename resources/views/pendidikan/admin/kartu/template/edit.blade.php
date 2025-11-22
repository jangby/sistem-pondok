<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Template Kartu') }}
        </h2>
    </x-slot>

    <script src="https://cdn.tiny.cloud/1/jrp8h6om1rgg556481kizv917d8n3a2ww0uuekm1nfxr1vhk/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('pendidikan.admin.kartu-template.update', $template->id) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="flex flex-col lg:flex-row gap-6">
                    <div class="flex-1 bg-white shadow-sm sm:rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Template</label>
                                <input type="text" name="nama_template" value="{{ $template->nama_template }}" required class="w-full border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Kertas</label>
                                <select name="ukuran_kertas" class="w-full border-gray-300 rounded-md">
                                    <option value="A4" {{ $template->ukuran_kertas == 'A4' ? 'selected' : '' }}>A4</option>
                                    <option value="F4" {{ $template->ukuran_kertas == 'F4' ? 'selected' : '' }}>F4</option>
                                    <option value="A5" {{ $template->ukuran_kertas == 'A5' ? 'selected' : '' }}>A5</option>
                                    <option value="A6" {{ $template->ukuran_kertas == 'A6' ? 'selected' : '' }}>A6</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Orientasi</label>
                                <select name="orientasi" class="w-full border-gray-300 rounded-md">
                                    <option value="portrait" {{ $template->orientasi == 'portrait' ? 'selected' : '' }}>Portrait</option>
                                    <option value="landscape" {{ $template->orientasi == 'landscape' ? 'selected' : '' }}>Landscape</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-4 mb-4 text-sm">
                            <div class="flex items-center gap-2">Atas: <input type="number" name="margin_top" value="{{ $template->margin_top }}" class="w-16 h-8 border-gray-300 rounded"></div>
                            <div class="flex items-center gap-2">Bawah: <input type="number" name="margin_bottom" value="{{ $template->margin_bottom }}" class="w-16 h-8 border-gray-300 rounded"></div>
                            <div class="flex items-center gap-2">Kiri: <input type="number" name="margin_left" value="{{ $template->margin_left }}" class="w-16 h-8 border-gray-300 rounded"></div>
                            <div class="flex items-center gap-2">Kanan: <input type="number" name="margin_right" value="{{ $template->margin_right }}" class="w-16 h-8 border-gray-300 rounded"></div>
                        </div>

                        <div class="mb-4">
                            <textarea id="my-editor" name="konten_html" rows="25" class="w-full">{{ $template->konten_html }}</textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('pendidikan.admin.kartu-template.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
                        </div>
                    </div>

                    <div class="w-full lg:w-80 bg-gray-50 p-4 rounded-lg border border-gray-200 h-fit sticky top-4">
                        <h3 class="font-bold text-lg mb-4">Variabel Kartu</h3>
                        <div class="mb-4 border-b pb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2">Identitas</h4>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="insertVar('@{{nama_santri}}')" class="btn-var">Nama</button>
                                <button type="button" onclick="insertVar('@{{nis}}')" class="btn-var">NIS</button>
                                <button type="button" onclick="insertVar('@{{kelas}}')" class="btn-var">Kelas</button>
                            </div>
                        </div>
                        <div class="mb-4 border-b pb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2">Jadwal</h4>
                            <button type="button" onclick="insertVar('@{{tabel_jadwal_ujian}}')" class="text-xs bg-green-50 border border-green-200 text-green-700 px-2 py-2 rounded w-full text-left">+ Tabel Jadwal</button>
                        </div>
                        <div class="mb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2">Pondok</h4>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="insertVar('@{{nama_pondok}}')" class="btn-var">Nama</button>
                                <button type="button" onclick="insertVar('@{{logo_pondok}}')" class="btn-var-blue">Logo</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .btn-var { font-size: 0.75rem; background: white; border: 1px solid #d1d5db; padding: 2px 6px; border-radius: 4px; cursor: pointer; }
        .btn-var-blue { font-size: 0.75rem; background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; padding: 2px 6px; border-radius: 4px; cursor: pointer; }
    </style>

    <script>
        tinymce.init({
            selector: '#my-editor',
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            toolbar_sticky: true,
            height: 800,
            content_style: 'body { font-family:Times New Roman,Helvetica,Arial,sans-serif; font-size:12pt; padding: 10px; } table { width: 100%; border-collapse: collapse; } table td, table th { border: 1px solid black; padding: 5px; }'
        });

        // Fungsi untuk memasukkan variabel ke editor
        function insertVar(variableName) {
            tinymce.get('my-editor').execCommand('mceInsertContent', false, variableName);
        }
    </script>
</x-app-layout>