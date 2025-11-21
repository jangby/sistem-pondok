<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['hide-nav' => true]); ?>
     <?php $__env->slot('header', null, []); ?>  <?php $__env->endSlot(); ?>

    
    <div class="min-h-screen bg-gray-100 pb-32 relative">

        
        <div class="h-44 bg-red-600 rounded-b-[40px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            
            
            <div class="flex justify-between items-center p-6 text-white relative z-40"> 
                <a href="<?php echo e(route('pengurus.uks.index')); ?>" class="bg-white/20 p-2 rounded-xl backdrop-blur-md hover:bg-white/30 transition text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="font-bold text-lg">Detail Kesehatan</h1>
                <div class="w-10"></div> 
            </div>
        </div>

        
        <div class="px-6 -mt-20 relative z-30"> 
            <div class="bg-white rounded-3xl shadow-xl shadow-gray-900/5 p-6 border border-gray-100">
                
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-red-50 rounded-full mx-auto mb-3 flex items-center justify-center text-2xl font-bold text-red-600 ring-4 ring-white shadow-md">
                        <?php echo e(substr($uks->santri->full_name, 0, 1)); ?>

                    </div>
                    
                    <h2 class="text-xl font-bold text-gray-800"><?php echo e($uks->santri->full_name); ?></h2>
                    <p class="text-gray-500 text-sm mb-4"><?php echo e($uks->santri->kelas->nama_kelas ?? '-'); ?> • <?php echo e($uks->santri->nis); ?></p>

                    <div class="inline-block px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                        <?php echo e($uks->status == 'sembuh' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                        <?php echo e(str_replace('_', ' ', $uks->status)); ?>

                    </div>
                </div>

                
                <hr class="my-6 border-gray-100">

                
                <div class="space-y-6">
                    
                    
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-500 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">Tanggal Sakit</p>
                            <p class="font-bold text-gray-800"><?php echo e($uks->created_at->isoFormat('dddd, D MMMM Y')); ?></p>
                            <p class="text-sm text-gray-500"><?php echo e($uks->created_at->format('H:i')); ?> WIB</p>
                        </div>
                    </div>

                    
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 shrink-0">
                            
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-red-400 uppercase font-bold">Keluhan Utama</p>
                            <p class="text-gray-800 font-medium leading-relaxed"><?php echo e($uks->keluhan); ?></p>
                        </div>
                    </div>

                    
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shrink-0">
                             
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v3m0 0v3m0-3h3m-3 0H3.75M6.75 9v3m0 0v3m0-3h3m-3 0H3.75m6-6v3m0 0v3m0-3h3m-3 0H9.75m6 6v3m0 0v3m0-3h3m-3 0h-3m-6-6v3m0 0v3m0-3h3m-3 0H3.75m6 6v3m0 0v3m0-3h3m-3 0H9.75m6-6v3m0 0v3m0-3h3m-3 0h-3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-blue-400 uppercase font-bold">Tindakan / Obat</p>
                            <p class="text-gray-800 font-medium leading-relaxed"><?php echo e($uks->tindakan ?? 'Belum ada tindakan tercatat.'); ?></p>
                        </div>
                    </div>

                    
                    <?php if($uks->tanggal_sembuh): ?>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-green-600 font-bold">Telah Sembuh Pada:</p>
                                <p class="text-gray-800 font-bold"><?php echo e($uks->tanggal_sembuh->isoFormat('D MMMM Y')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>

    
    <div class="fixed bottom-0 left-0 right-0 p-5 pb-8 z-30 bg-white border-t border-gray-200">
        <a href="<?php echo e(route('pengurus.uks.edit', $uks->id)); ?>" 
           class="block w-full bg-red-600 text-white font-bold text-center py-4 rounded-2xl shadow-lg shadow-red-500/50 active:scale-95 transition">
            Edit Data / Update Status
        </a>
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
<?php endif; ?><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/uks/show.blade.php ENDPATH**/ ?>