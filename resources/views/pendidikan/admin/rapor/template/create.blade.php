<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Desain Rapor Baru') }}
        </h2>
    </x-slot>

    <script src="https://cdn.tiny.cloud/1/jrp8h6om1rgg556481kizv917d8n3a2ww0uuekm1nfxr1vhk/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('pendidikan.admin.rapor-template.store') }}" method="POST">
                @csrf
                
                <div class="flex flex-col lg:flex-row gap-6">
                    
                    <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Desain</label>
                                <input type="text" name="nama_template" required class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Rapor UTS 2025">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Kertas</label>
                                <select name="ukuran_kertas" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="A4">A4</option>
                                    <option value="F4">F4 (Folio)</option>
                                    <option value="A5">A5</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Orientasi</label>
                                <select name="orientasi" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="portrait">Portrait (Tegak)</option>
                                    <option value="landscape">Landscape (Mendatar)</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-4 mb-4 text-sm">
                            <div class="flex items-center gap-2">
                                <span>Atas:</span> <input type="number" name="margin_top" value="10" class="w-16 border-gray-300 rounded-md h-8"> mm
                            </div>
                            <div class="flex items-center gap-2">
                                <span>Bawah:</span> <input type="number" name="margin_bottom" value="10" class="w-16 border-gray-300 rounded-md h-8"> mm
                            </div>
                            <div class="flex items-center gap-2">
                                <span>Kiri:</span> <input type="number" name="margin_left" value="15" class="w-16 border-gray-300 rounded-md h-8"> mm
                            </div>
                            <div class="flex items-center gap-2">
                                <span>Kanan:</span> <input type="number" name="margin_right" value="10" class="w-16 border-gray-300 rounded-md h-8"> mm
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Desain Dokumen</label>
                            <textarea id="my-editor" name="konten_html" rows="30" class="w-full"></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Simpan Desain</button>
                        </div>
                    </div>

                    <div class="w-full lg:w-80 bg-gray-50 p-4 rounded-lg border border-gray-200 h-fit sticky top-4">
                        <h3 class="font-bold text-lg mb-4 text-gray-800">Panel Variabel</h3>
                        <p class="text-xs text-gray-500 mb-4">Klik tombol di bawah untuk menyisipkan data otomatis ke dalam rapor.</p>

                        <div class="mb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2 uppercase">Identitas Santri</h4>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="insertVar('@{{nama_santri}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Nama Lengkap</button>
                                <button type="button" onclick="insertVar('@{{nis}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">NIS</button>
                                <button type="button" onclick="insertVar('@{{kelas}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Kelas/Mustawa</button>
                                <button type="button" onclick="insertVar('@{{tahun_ajaran}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Thn. Ajaran</button>
                                <button type="button" onclick="insertVar('@{{semester}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Semester</button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2 uppercase">Data Pondok</h4>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="insertVar('@{{nama_pondok}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Nama Pondok</button>
                                <button type="button" onclick="insertVar('@{{alamat_pondok}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Alamat</button>
                                <button type="button" onclick="insertVar('@{{logo_pondok}}')" class="text-xs bg-blue-50 border border-blue-200 text-blue-700 px-2 py-1 rounded hover:bg-blue-100">Logo (Gambar)</button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2 uppercase">Nilai & Tabel</h4>
                            <div class="flex flex-col gap-2">
                                <button type="button" onclick="insertVar('@{{tabel_nilai}}')" class="text-sm bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 text-left">
                                    + Tabel Nilai Otomatis
                                </button>
                                <p class="text-[10px] text-gray-400 mb-2">*Otomatis membuat tabel berisi No, Mapel, KKM, Nilai, Predikat</p>

                                <div class="flex flex-wrap gap-2">
                                    <button type="button" onclick="insertVar('@{{total_nilai}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Total Nilai</button>
                                    <button type="button" onclick="insertVar('@{{rata_rata}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Rata-rata</button>
                                    <button type="button" onclick="insertVar('@{{ranking}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Ranking</button>
                                    <button type="button" onclick="insertVar('@{{keputusan}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Keputusan</button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2 uppercase">Kehadiran</h4>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="insertVar('@{{sakit}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Jml Sakit</button>
                                <button type="button" onclick="insertVar('@{{izin}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Jml Izin</button>
                                <button type="button" onclick="insertVar('@{{alpha}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Jml Alpha</button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2 uppercase">Area Tanda Tangan</h4>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="insertVar('@{{titimangsa}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Tanggal Cetak</button>
                                <button type="button" onclick="insertVar('@{{wali_kelas}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Nama Wali Kelas</button>
                                <button type="button" onclick="insertVar('@{{kepala_pondok}}')" class="text-xs bg-white border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">Nama Kepala Pondok</button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

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