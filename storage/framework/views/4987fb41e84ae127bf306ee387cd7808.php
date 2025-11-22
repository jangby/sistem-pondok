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
            <?php echo e(__('Buat Template Kartu Ujian')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <script src="https://cdn.tiny.cloud/1/jrp8h6om1rgg556481kizv917d8n3a2ww0uuekm1nfxr1vhk/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <form action="<?php echo e(route('pendidikan.admin.kartu-template.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="flex flex-col lg:flex-row gap-6">
                    <div class="flex-1 bg-white shadow-sm sm:rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Template</label>
                                <input type="text" name="nama_template" required class="w-full border-gray-300 rounded-md" placeholder="Cth: Kartu UAS 2025">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran Kertas</label>
                                <select name="ukuran_kertas" class="w-full border-gray-300 rounded-md">
                                    <option value="A4">A4</option>
                                    <option value="F4">F4</option>
                                    <option value="A5" selected>A5 (Setengah A4)</option>
                                    <option value="A6">A6 (Kecil)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Orientasi</label>
                                <select name="orientasi" class="w-full border-gray-300 rounded-md">
                                    <option value="portrait">Portrait (Tegak)</option>
                                    <option value="landscape">Landscape (Mendatar)</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-4 mb-4 text-sm">
                            <div class="flex items-center gap-2">Atas: <input type="number" name="margin_top" value="5" class="w-16 h-8 border-gray-300 rounded"> mm</div>
                            <div class="flex items-center gap-2">Bawah: <input type="number" name="margin_bottom" value="5" class="w-16 h-8 border-gray-300 rounded"> mm</div>
                            <div class="flex items-center gap-2">Kiri: <input type="number" name="margin_left" value="5" class="w-16 h-8 border-gray-300 rounded"> mm</div>
                            <div class="flex items-center gap-2">Kanan: <input type="number" name="margin_right" value="5" class="w-16 h-8 border-gray-300 rounded"> mm</div>
                        </div>

                        <div class="mb-4">
                            <textarea id="my-editor" name="konten_html" rows="25" class="w-full"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan Desain</button>
                        </div>
                    </div>

                    <div class="w-full lg:w-80 bg-gray-50 p-4 rounded-lg border border-gray-200 h-fit sticky top-4 overflow-y-auto max-h-screen">
                        <h3 class="font-bold text-lg mb-4">Variabel Kartu</h3>
                        
                        <div class="mb-4 border-b pb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2">Identitas</h4>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="insertVar('{{nama_santri}}')" class="btn-var">Nama</button>
                                <button type="button" onclick="insertVar('{{nis}}')" class="btn-var">NIS</button>
                                <button type="button" onclick="insertVar('{{kelas}}')" class="btn-var">Kelas</button>
                            </div>
                        </div>

                        <div class="mb-4 border-b pb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2">Jadwal</h4>
                            <button type="button" onclick="insertVar('{{tabel_jadwal_ujian}}')" class="text-xs bg-green-50 border border-green-200 text-green-700 px-2 py-2 rounded w-full text-left">
                                + Tabel Jadwal Ujian Otomatis
                            </button>
                            <p class="text-[10px] text-gray-400 mt-1">*Tabel berisi Hari, Jam, Mapel</p>
                        </div>

                        <div class="mb-4">
                            <h4 class="font-semibold text-sm text-gray-600 mb-2">Pondok</h4>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="insertVar('{{nama_pondok}}')" class="btn-var">Nama Pondok</button>
                                <button type="button" onclick="insertVar('{{logo_pondok}}')" class="btn-var-blue">Logo</button>
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/kartu/template/create.blade.php ENDPATH**/ ?>