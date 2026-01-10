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
            <?php echo e(__('Dashboard Penulis')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-indigo-600 rounded-2xl shadow-xl overflow-hidden text-white relative">
                <div class="p-8 md:p-12 relative z-10 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Ahlan Wa Sahlan, <?php echo e(Auth::user()->name); ?>!</h1>
                        <p class="text-indigo-100 text-lg max-w-xl">
                            Mari lanjutkan karya tulis Anda. Menyusun doa dan ilmu untuk kemanfaatan umat.
                        </p>
                        <div class="mt-6">
                            <a href="<?php echo e(route('penulis.books.create')); ?>" class="bg-white text-indigo-700 px-6 py-3 rounded-lg font-bold shadow hover:bg-gray-100 transition">
                                + Mulai Tulis Buku Baru
                            </a>
                        </div>
                    </div>
                    <div class="hidden md:block text-9xl opacity-20 transform rotate-12">
                        ✍️
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="bg-blue-100 p-4 rounded-full text-blue-600 text-2xl">📚</div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Buku</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo e($totalBuku); ?> <span class="text-sm font-normal text-gray-400">Judul</span></h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="bg-green-100 p-4 rounded-full text-green-600 text-2xl">✅</div>
                    <div>
                        <p class="text-gray-500 text-sm">Buku Terbit</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo e($bukuTerbit); ?> <span class="text-sm font-normal text-gray-400">Siap Cetak</span></h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="bg-purple-100 p-4 rounded-full text-purple-600 text-2xl">🤲</div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Pasal / Doa</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo e($totalDoa); ?> <span class="text-sm font-normal text-gray-400">Item</span></h3>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-end mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Lanjutkan Menulis</h3>
                    <a href="<?php echo e(route('penulis.books.index')); ?>" class="text-indigo-600 text-sm font-semibold hover:underline">Lihat Semua Buku &rarr;</a>
                </div>

                <?php if($recentBooks->isEmpty()): ?>
                    <div class="bg-white rounded-xl p-8 text-center border border-dashed border-gray-300">
                        <p class="text-gray-500 mb-2">Belum ada riwayat penulisan.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php $__currentLoopData = $recentBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition flex flex-col h-full">
                            <div class="h-32 bg-gray-100 w-full flex items-center justify-center text-gray-300 rounded-t-xl overflow-hidden">
                                <?php if($book->cover_image): ?>
                                    <img src="<?php echo e(asset('storage/'.$book->cover_image)); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-3xl">📖</span>
                                <?php endif; ?>
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <h4 class="font-bold text-gray-800 mb-1 truncate"><?php echo e($book->title); ?></h4>
                                <p class="text-xs text-gray-500 mb-3">Diperbarui: <?php echo e($book->updated_at->diffForHumans()); ?></p>
                                
                                <div class="flex items-center gap-2 text-xs text-gray-600 mb-4 bg-gray-50 p-2 rounded">
                                    <span>📑 <?php echo e($book->chapters_count); ?> Bab</span>
                                    <span>•</span>
                                    <span>🤲 <?php echo e($book->items_count); ?> Doa</span>
                                </div>

                                <a href="<?php echo e(route('penulis.books.show', $book->id)); ?>" class="mt-auto block w-full text-center bg-indigo-50 text-indigo-700 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-100 transition">
                                    Buka Editor
                                </a>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/penulis/dashboard.blade.php ENDPATH**/ ?>