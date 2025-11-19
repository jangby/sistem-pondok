<div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 pb-safe z-50">
    <div class="flex justify-around items-center h-16">
        
        <a href="<?php echo e(route('pos.dashboard')); ?>" class="flex flex-col items-center justify-center w-full h-full <?php echo e(request()->routeIs('pos.dashboard') ? 'text-emerald-600' : 'text-gray-400 hover:text-emerald-500'); ?>">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span class="text-[10px] font-medium">Beranda</span>
        </a>

        <a href="<?php echo e(route('pos.index')); ?>" class="flex flex-col items-center justify-center w-full h-full -mt-6">
            <div class="w-14 h-14 bg-emerald-600 rounded-full shadow-lg flex items-center justify-center text-white border-4 border-gray-50 <?php echo e(request()->routeIs('pos.index') ? 'bg-emerald-700' : ''); ?>">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            </div>
            <span class="text-[10px] font-medium text-emerald-600 mt-1">Kasir</span>
        </a>

        <a href="<?php echo e(route('pos.history')); ?>" class="flex flex-col items-center justify-center w-full h-full <?php echo e(request()->routeIs('pos.history') ? 'text-emerald-600' : 'text-gray-400 hover:text-emerald-500'); ?>">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <span class="text-[10px] font-medium">Riwayat</span>
        </a>

        <a href="<?php echo e(route('pos.payout')); ?>" class="flex flex-col items-center justify-center w-full h-full <?php echo e(request()->routeIs('pos.payout') ? 'text-emerald-600' : 'text-gray-400 hover:text-emerald-500'); ?>">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span class="text-[10px] font-medium">Tarik Dana</span>
        </a>

    </div>
</div><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/layouts/pos-nav.blade.php ENDPATH**/ ?>