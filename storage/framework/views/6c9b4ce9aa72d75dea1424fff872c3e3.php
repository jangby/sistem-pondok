

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-900 text-white flex flex-col items-center justify-center p-6 relative overflow-hidden">
    
    
    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-purple-500"></div>
    <div class="absolute bottom-0 right-0 opacity-10">
        <svg width="400" height="400" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
    </div>

    
    <div class="text-center mb-10 z-10">
        <h1 class="text-5xl font-extrabold tracking-tight mb-2">Perpustakaan Digital</h1>
        <p class="text-xl text-gray-400">Silakan Scan Kartu Santri Anda untuk Masuk</p>
    </div>

    
    <div class="w-full max-w-2xl bg-gray-800 p-8 rounded-2xl shadow-2xl border border-gray-700 z-10 relative">
        
        
        <form action="<?php echo e(route('sekolah.superadmin.perpustakaan.kunjungan.store')); ?>" method="POST" class="mb-8">
            <?php echo csrf_field(); ?>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
                <input type="text" 
                       name="kode_santri" 
                       id="scanInput" 
                       class="block w-full pl-14 pr-4 py-4 bg-gray-900 border-2 border-blue-500 rounded-xl text-2xl text-white placeholder-gray-500 focus:ring-4 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-inner" 
                       placeholder="Tempel Kartu / Scan QR di sini..." 
                       autocomplete="off" 
                       autofocus>
            </div>
            
            
            <div class="mt-4 flex gap-4 justify-center">
                <label class="inline-flex items-center">
                    <input type="radio" name="keperluan" value="Membaca" checked class="form-radio text-blue-600 h-5 w-5">
                    <span class="ml-2 text-lg">Membaca</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="keperluan" value="Pinjam Buku" class="form-radio text-green-600 h-5 w-5">
                    <span class="ml-2 text-lg">Pinjam Buku</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="keperluan" value="Internet" class="form-radio text-purple-600 h-5 w-5">
                    <span class="ml-2 text-lg">Komputer/Internet</span>
                </label>
            </div>
        </form>

        
        <?php if(session('success')): ?>
            <div class="bg-green-600 text-white p-4 rounded-lg text-center text-xl font-bold animate-pulse mb-6">
                ✅ <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        
        <?php if(session('error')): ?>
            <div class="bg-red-600 text-white p-4 rounded-lg text-center text-xl font-bold mb-6">
                ❌ <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        
        <div class="border-t border-gray-700 pt-6">
            <h3 class="text-gray-400 text-sm uppercase tracking-wider mb-4 font-bold">Kunjungan Terakhir Hari Ini</h3>
            <div class="space-y-3">
                <?php $__currentLoopData = $todayVisits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between bg-gray-700/50 p-3 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center font-bold text-lg">
                            <?php echo e(substr($visit->santri->name, 0, 1)); ?>

                        </div>
                        <div>
                            <p class="font-bold text-white"><?php echo e($visit->santri->name); ?></p>
                            <p class="text-xs text-gray-400"><?php echo e($visit->santri->kelas->nama_kelas ?? '-'); ?> • <?php echo e($visit->waktu_berkunjung->format('H:i')); ?></p>
                        </div>
                    </div>
                    <span class="text-xs bg-gray-600 px-2 py-1 rounded text-gray-300"><?php echo e($visit->keperluan); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <div class="mt-8 text-gray-500 text-sm">
        Sistem Perpustakaan Pondok v1.0 &bull; Tekan F11 untuk Full Screen
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('scanInput');
        
        // Fokus otomatis saat halaman dimuat
        input.focus();

        // Paksa fokus kembali jika user klik di luar (agar scan tetap jalan)
        document.addEventListener('click', function() {
            input.focus();
        });

        // Mencegah kehilangan fokus
        input.addEventListener('blur', function() {
            setTimeout(() => input.focus(), 10);
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kios', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/superadmin/perpus/kunjungan/kiosk.blade.php ENDPATH**/ ?>