<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Edit Desain Rapor')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <script src="https://cdn.tiny.cloud/1/jrp8h6om1rgg556481kizv917d8n3a2ww0uuekm1nfxr1vhk/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <form action="<?php echo e(route('pendidikan.admin.rapor-template.update', $template->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="flex flex-col lg:flex-row gap-6">
                    
                    <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Desain</label>
                                <input type="text" name="nama_template" value="<?php echo e($template->nama_template); ?>" required class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Rapor UTS 2025">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Kertas</label>
                                <select name="ukuran_kertas" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="A4" <?php echo e($template->ukuran_kertas == 'A4' ? 'selected' : ''); ?>>A4</option>
                                    <option value="F4" <?php echo e($template->ukuran_kertas == 'F4' ? 'selected' : ''); ?>>F4 (Folio)</option>
                                    <option value="A5" <?php echo e($template->ukuran_kertas == 'A5' ? 'selected' : ''); ?>>A5</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Orientasi</label>
                                <select name="orientasi" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="portrait" <?php echo e($template->orientasi == 'portrait' ? 'selected' : ''); ?>>Portrait (Tegak)</option>
                                    <option value="landscape" <?php echo e($template->orientasi == 'landscape' ? 'selected' : ''); ?>>Landscape (Mendatar)</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-4 mb-4 text-sm">
                            <div class="flex items-center gap-2">
                                <span>Atas:</span> <input type="number" name="margin_top" value="<?php echo e($template->margin_top ?? 10); ?>" class="w-16 border-gray-300 rounded-md h-8"> mm
                            </div>
                            <div class="flex items-center gap-2">
                                <span>Bawah:</span> <input type="number" name="margin_bottom" value="<?php echo e($template->margin_bottom ?? 10); ?>" class="w-16 border-gray-300 rounded-md h-8"> mm
                            </div>
                            <div class="flex items-center gap-2">
                                <span>Kiri:</span> <input type="number" name="margin_left" value="<?php echo e($template->margin_left ?? 15); ?>" class="w-16 border-gray-300 rounded-md h-8"> mm
                            </div>
                            <div class="flex items-center gap-2">
                                <span>Kanan:</span> <input type="number" name="margin_right" value="<?php echo e($template->margin_right ?? 10); ?>" class="w-16 border-gray-300 rounded-md h-8"> mm
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Desain Dokumen</label>
                            <textarea id="my-editor" name="konten_html" rows="30" class="w-full"><?php echo e($template->konten_html); ?></textarea>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="<?php echo e(route('pendidikan.admin.rapor-template.index')); ?>" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">Batal</a>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">Simpan Perubahan</button>
                        </div>
                    </div>

                    <div class="w-full lg:w-80 bg-gray-50 p-4 rounded-lg border border-gray-200 h-fit sticky top-4 overflow-y-auto max-h-screen">
                        <h3 class="font-bold text-lg mb-4 text-gray-800">Panel Variabel</h3>
                        <p class="text-xs text-gray-500 mb-4">Klik tombol di bawah untuk menyisipkan data otomatis.</p>

                        <div class="mb-4 border-b pb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2 uppercase">Biodata Santri</h4>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="insertVar('{{nama_santri}}')" class="btn-var">Nama Lengkap</button>
                                <button type="button" onclick="insertVar('{{nis}}')" class="btn-var">NIS</button>
                                <button type="button" onclick="insertVar('{{nisn}}')" class="btn-var">NISN</button>
                                <button type="button" onclick="insertVar('{{nik}}')" class="btn-var">NIK</button>
                                <button type="button" onclick="insertVar('{{ttl}}')" class="btn-var">TTL (Lengkap)</button>
                                <button type="button" onclick="insertVar('{{jenis_kelamin}}')" class="btn-var">Jenis Kelamin</button>
                                <button type="button" onclick="insertVar('{{alamat}}')" class="btn-var">Alamat</button>
                                <button type="button" onclick="insertVar('{{kelas}}')" class="btn-var">Kelas</button>
                            </div>
                        </div>

                        <div class="mb-4 border-b pb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2 uppercase">Orang Tua / Wali</h4>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="insertVar('{{nama_ayah}}')" class="btn-var">Nama Ayah</button>
                                <button type="button" onclick="insertVar('{{nama_ibu}}')" class="btn-var">Nama Ibu</button>
                                <button type="button" onclick="insertVar('{{pekerjaan_ayah}}')" class="btn-var">Pekerjaan Ayah</button>
                                <button type="button" onclick="insertVar('{{no_hp_wali}}')" class="btn-var">No. HP Wali</button>
                            </div>
                        </div>

                        <div class="mb-4 border-b pb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2 uppercase">Data Pondok</h4>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="insertVar('{{nama_pondok}}')" class="btn-var">Nama Pondok</button>
                                <button type="button" onclick="insertVar('{{alamat_pondok}}')" class="btn-var">Alamat</button>
                                <button type="button" onclick="insertVar('{{logo_pondok}}')" class="btn-var-blue">Logo (Gambar)</button>
                                <button type="button" onclick="insertVar('{{tahun_ajaran}}')" class="btn-var">Thn. Ajaran</button>
                                <button type="button" onclick="insertVar('{{semester}}')" class="btn-var">Semester</button>
                            </div>
                        </div>

                        <div class="mb-4 border-b pb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2 uppercase">Tabel Nilai (Per Jenis)</h4>
                            <div class="flex flex-col gap-2">
                                <button type="button" onclick="insertVar('{{tabel_nilai_tulis}}')" class="text-xs bg-indigo-50 border border-indigo-200 text-indigo-700 px-2 py-2 rounded hover:bg-indigo-100 text-left">
                                    + Tabel Ujian Tulis
                                </button>
                                <button type="button" onclick="insertVar('{{tabel_nilai_lisan}}')" class="text-xs bg-indigo-50 border border-indigo-200 text-indigo-700 px-2 py-2 rounded hover:bg-indigo-100 text-left">
                                    + Tabel Ujian Lisan (Hafalan)
                                </button>
                                <button type="button" onclick="insertVar('{{tabel_nilai_praktek}}')" class="text-xs bg-indigo-50 border border-indigo-200 text-indigo-700 px-2 py-2 rounded hover:bg-indigo-100 text-left">
                                    + Tabel Ujian Praktek (Ibadah)
                                </button>
                                <button type="button" onclick="insertVar('{{tabel_nilai_absensi}}')" class="text-xs bg-indigo-50 border border-indigo-200 text-indigo-700 px-2 py-2 rounded hover:bg-indigo-100 text-left">
                                    + Tabel Kehadiran Mapel
                                </button>
                                
                                <hr class="my-1 border-gray-200">
                                
                                <button type="button" onclick="insertVar('{{tabel_nilai}}')" class="text-xs bg-gray-100 border border-gray-300 text-gray-700 px-2 py-2 rounded hover:bg-gray-200 text-left">
                                    + Tabel Nilai Gabungan (Akhir)
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2 uppercase">Area Tanda Tangan</h4>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="insertVar('{{titimangsa}}')" class="btn-var">Tanggal Cetak</button>
                                <button type="button" onclick="insertVar('{{wali_kelas}}')" class="btn-var">Nama Wali Kelas</button>
                                <button type="button" onclick="insertVar('{{kepala_pondok}}')" class="btn-var">Nama Kepala Pondok</button>
                                <button type="button" onclick="insertVar('{{nama_wali}}')" class="btn-var">Nama Wali Santri</button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .btn-var {
            font-size: 0.75rem; /* text-xs */
            background-color: white;
            border: 1px solid #d1d5db; /* gray-300 */
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn-var:hover {
            background-color: #f3f4f6; /* gray-100 */
        }
        .btn-var-blue {
            font-size: 0.75rem;
            background-color: #eff6ff; /* blue-50 */
            border: 1px solid #bfdbfe; /* blue-200 */
            color: #1d4ed8; /* blue-700 */
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .btn-var-blue:hover {
            background-color: #dbeafe; /* blue-100 */
        }
    </style>

    <script>
        // Konfigurasi Ukuran Kertas (dalam mm)
        const paperSizes = {
            'A4': { width: 210, height: 297 },
            'F4': { width: 215, height: 330 }, // Ukuran F4/Folio Indonesia umumnya 21.5 x 33 cm
            'A5': { width: 148, height: 210 }
        };

        function getPaperStyle() {
            // Ambil nilai dari form input
            let sizeKey = document.querySelector('select[name="ukuran_kertas"]').value || 'A4';
            let orientation = document.querySelector('select[name="orientasi"]').value || 'portrait';
            
            // Ambil margin
            let mt = document.querySelector('input[name="margin_top"]').value || 10;
            let mb = document.querySelector('input[name="margin_bottom"]').value || 10;
            let ml = document.querySelector('input[name="margin_left"]').value || 15;
            let mr = document.querySelector('input[name="margin_right"]').value || 10;

            let width = paperSizes[sizeKey].width;
            let height = paperSizes[sizeKey].height;

            // Jika landscape, tukar width & height
            if (orientation === 'landscape') {
                let temp = width;
                width = height;
                height = temp;
            }

            // CSS untuk mensimulasikan kertas MS Word
            return `
                body { 
                    font-family: 'Times New Roman', Helvetica, Arial, sans-serif; 
                    font-size: 12pt; 
                    background-color: #fff;
                    width: ${width}mm; 
                    min-height: ${height}mm;
                    padding: ${mt}mm ${mr}mm ${mb}mm ${ml}mm !important; /* Margin visual */
                    margin: 20px auto; 
                    box-shadow: 0 0 10px rgba(0,0,0,0.2); /* Efek bayangan kertas */
                    box-sizing: border-box;
                }
                html {
                    background-color: #555; /* Latar belakang gelap seperti Word */
                    padding: 20px 0;
                    display: flex;
                    justify-content: center;
                }
                /* Tampilan Page Break */
                .mce-pagebreak {
                    border: 0;
                    border-top: 2px dashed #888;
                    height: 20px;
                    background: #ddd;
                    margin: 20px -${mr}mm 20px -${ml}mm; /* Melebar ke pinggir */
                    text-align: center;
                    content: "--- BATAS HALAMAN (PAGE BREAK) ---";
                    color: #555;
                    font-size: 10px;
                    display: block;
                    page-break-before: always;
                }
                table { width: 100%; border-collapse: collapse; } 
                table td, table th { border: 1px solid black; padding: 4px; }
            `;
        }

        // Inisialisasi TinyMCE
        tinymce.init({
            selector: '#my-editor',
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
            menubar: 'file edit view insert format tools table help',
            // Menambahkan tombol 'pagebreak' ke toolbar
            toolbar: 'undo redo | fontfamily fontsize | bold italic underline | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | table pagebreak | fullscreen preview save',
            toolbar_sticky: true,
            height: 800,
            
            // PENTING: Memuat CSS dinamis saat editor start
            content_style: getPaperStyle(),
            
            // Agar tombol pagebreak berfungsi memasukkan elemen pemisah halaman
            pagebreak_separator: "", 
            pagebreak_split_block: true,

            setup: function(editor) {
                // Listener: Jika user mengubah ukuran kertas/margin di form, update tampilan editor
                const inputs = document.querySelectorAll('select[name="ukuran_kertas"], select[name="orientasi"], input[name^="margin_"]');
                inputs.forEach(input => {
                    input.addEventListener('change', function() {
                        // Update CSS editor secara realtime
                        let newStyle = getPaperStyle();
                        editor.dom.doc.querySelector('style').innerHTML = newStyle;
                    });
                });
            }
        });

        // Fungsi Insert Variabel (Tetap Sama)
        function insertVar(variableName) {
            tinymce.get('my-editor').execCommand('mceInsertContent', false, variableName);
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/rapor/template/edit.blade.php ENDPATH**/ ?>