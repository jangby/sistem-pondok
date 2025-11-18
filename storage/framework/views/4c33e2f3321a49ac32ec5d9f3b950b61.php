
<div class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-gray-200 pb-safe pt-2 px-6 shadow-[0_-5px_20px_-5px_rgba(0,0,0,0.1)] z-50">
    <div class="flex justify-around items-end pb-3">
        
        
        <a href="<?php echo e(route('pengurus.dashboard')); ?>" class="flex flex-col items-center gap-1 group <?php echo e(request()->routeIs('pengurus.dashboard') ? 'text-emerald-600' : 'text-gray-400'); ?>">
            <div class="p-1.5 rounded-xl <?php echo e(request()->routeIs('pengurus.dashboard') ? 'bg-emerald-50' : 'group-hover:bg-gray-50'); ?> transition">
                <svg class="w-6 h-6 <?php echo e(request()->routeIs('pengurus.dashboard') ? 'text-emerald-600' : 'text-gray-400'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
            <span class="text-[10px] font-bold <?php echo e(request()->routeIs('pengurus.dashboard') ? 'text-emerald-700' : 'text-gray-500'); ?>">Beranda</span>
        </a>

        
        <a href="#" class="-mt-10 group">
            <div class="bg-emerald-600 text-white p-4 rounded-2xl shadow-emerald-300/50 shadow-lg border-[6px] border-gray-50 flex items-center justify-center transform group-active:scale-95 transition hover:bg-emerald-700">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M19 19h-5m-9-6h5a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zM6 21v-2a1 1 0 011-1h10V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16z"></path></svg>
            </div>
        </a>

        
        <a href="<?php echo e(route('profile.edit')); ?>" class="flex flex-col items-center gap-1 group <?php echo e(request()->routeIs('profile.edit') ? 'text-emerald-600' : 'text-gray-400'); ?>">
            <div class="p-1.5 rounded-xl <?php echo e(request()->routeIs('profile.edit') ? 'bg-emerald-50' : 'group-hover:bg-gray-50'); ?> transition">
                <svg class="w-6 h-6 <?php echo e(request()->routeIs('profile.edit') ? 'text-emerald-600' : 'text-gray-400'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <span class="text-[10px] font-bold <?php echo e(request()->routeIs('profile.edit') ? 'text-emerald-700' : 'text-gray-500'); ?>">Profil</span>
        </a>

    </div>
</div><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/layouts/pengurus-nav.blade.php ENDPATH**/ ?>