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
            <?php echo e(__('Cetak Kartu Ujian')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Filter Data</h3>
                </div>

                <form action="<?php echo e(route('pendidikan.admin.kartu.generate')); ?>" method="POST" target="_blank">
                    <?php echo csrf_field(); ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas (Mustawa)</label>
                            
                            <select name="mustawa_id" id="mustawa_select" required onchange="loadSantri(this.value)" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">-- Pilih Kelas --</option>
                                <?php $__currentLoopData = $mustawas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($m->id); ?>"><?php echo e($m->nama); ?> (Tingkat <?php echo e($m->tingkat); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ujian (Judul Kartu)</label>
                            <input type="text" name="nama_ujian" placeholder="Contoh: Ujian Akhir Semester" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                                   value="UJIAN AKHIR PERIODE <?php echo e(date('Y')); ?>">
                        </div>
                    </div>

                    
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih Santri (Opsional - Untuk Cetak Ulang/Hilang)
                        </label>
                        <p class="text-xs text-gray-500 mb-2">Biarkan kosong untuk mencetak <b>semua santri</b> di kelas tersebut. Tekan CTRL (Windows) atau Command (Mac) untuk memilih lebih dari satu nama.</p>
                        
                        <select name="santri_ids[]" id="santri_select" multiple class="w-full h-40 border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50" disabled>
                            <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                        </select>
                        <p id="loading_text" class="text-xs text-emerald-600 mt-1 hidden">Sedang memuat data santri...</p>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-6 rounded shadow-lg flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            <span>Cetak Kartu (PDF)</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    
    <?php $__env->startPush('scripts'); ?>
    <script>
    function loadSantri(mustawaId) {
        const santriSelect = document.getElementById('santri_select');
        const loadingText = document.getElementById('loading_text');
        
        // Reset dropdown
        santriSelect.innerHTML = '';
        santriSelect.disabled = true;

        if (!mustawaId) {
            santriSelect.innerHTML = '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
            return;
        }

        // Tampilkan loading
        loadingText.classList.remove('hidden');

        // [PERBAIKAN] Menggunakan route() agar URL dinamis dan benar
        // URL ini akan otomatis menjadi: /pendidikan-admin/kartu-ujian/get-santri/1
        const url = "<?php echo e(route('pendidikan.admin.kartu.get-santri', ':id')); ?>".replace(':id', mustawaId);

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                loadingText.classList.add('hidden');
                santriSelect.disabled = false;

                if (data.length === 0) {
                    const option = document.createElement('option');
                    option.text = 'Tidak ada santri aktif di kelas ini';
                    santriSelect.add(option);
                } else {
                    data.forEach(santri => {
                        const option = document.createElement('option');
                        option.value = santri.id;
                        // Menampilkan Nama + NIS
                        option.text = `${santri.full_name} (${santri.nis ?? '-'})`;
                        santriSelect.add(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loadingText.classList.add('hidden');
                santriSelect.innerHTML = '<option value="">Gagal memuat data (Cek Console)</option>';
            });
    }
</script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/kartu/index.blade.php ENDPATH**/ ?>