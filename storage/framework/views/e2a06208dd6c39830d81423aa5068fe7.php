<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Perpulangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
        
        @media print {
            @page {
                margin: 0.5cm;
                size: A4 portrait;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background-color: white;
            }
            .no-print { display: none; }
            .page-break { page-break-inside: avoid; }
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Ukuran Kartu ID-1 (KTP) Presisi */
        .id-card {
            width: 85.6mm;
            height: 53.98mm;
            position: relative;
            overflow: hidden;
            background: #fff;
            /* Border ganda untuk kesan eksklusif */
            border: 1px solid #d1d5db;
            outline: 1px solid #000;
            outline-offset: -3px; 
            border-radius: 6px;
        }

        /* Watermark Logo di tengah */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
            opacity: 0.04; /* Sangat tipis agar hemat tinta & tidak ganggu teks */
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body class="bg-gray-100 p-8">

    <div class="no-print fixed top-6 right-6 z-50">
        <button onclick="window.print()" class="bg-emerald-600 text-white px-6 py-2.5 rounded-full font-bold shadow-xl hover:bg-emerald-700 transition flex items-center gap-2 transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Kartu
        </button>
    </div>

    <div class="max-w-[210mm] mx-auto min-h-screen flex flex-wrap gap-4 content-start">
        
        <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="id-card page-break flex flex-col shadow-sm">
            
            <img src="https://raw.githubusercontent.com/Dhuyuand/aset-cerdas-cermat/main/Logo.png" class="watermark" alt="Watermark">

            <div class="relative z-10 flex items-center px-4 pt-3 pb-2 border-b-[2px] border-emerald-600 mx-1">
                 <div class="w-[11mm] h-[11mm] flex-shrink-0 flex items-center justify-center mr-3">
                    <img src="https://raw.githubusercontent.com/Dhuyuand/aset-cerdas-cermat/main/Logo.png" 
                         alt="Logo" class="w-full h-full object-contain filter drop-shadow-sm">
                 </div>

                 <div class="flex-1 leading-none">
                     <h1 class="font-extrabold text-[11px] uppercase tracking-tight text-emerald-900">Pondok Pesantren Assa'adah</h1>
                     <p class="text-[7.5px] text-gray-500 font-semibold mt-1 uppercase tracking-wide"><?php echo e($event->judul); ?></p>
                 </div>
            </div>

            <div class="relative z-10 flex-1 flex flex-row items-center px-4 py-1 gap-2">
                
                <div class="w-[68%] flex flex-col justify-center gap-1.5">
                    
                    <div>
                        <span class="block text-[6px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Nama Lengkap</span>
                        <h2 class="font-black text-[12.5px] leading-none text-gray-800 uppercase tracking-tight">
                            <?php echo e(Str::limit($record->santri->full_name, 22)); ?>

                        </h2>
                    </div>

                    <div class="grid grid-cols-2 gap-y-1 gap-x-2">
                        <div>
                            <span class="block text-[6px] text-gray-400 font-bold uppercase tracking-wider">Kelas / Mustawa</span>
                            <p class="font-bold text-[9px] text-gray-700"><?php echo e($record->santri->mustawa->nama ?? '-'); ?></p>
                        </div>
                        <div>
                            <span class="block text-[6px] text-gray-400 font-bold uppercase tracking-wider">Kamar / Asrama</span>
                            <p class="font-bold text-[9px] text-gray-700"><?php echo e($record->santri->asrama->nama_asrama ?? '-'); ?></p>
                        </div>
                        <div class="col-span-2">
                            <span class="block text-[6px] text-gray-400 font-bold uppercase tracking-wider">Alamat</span>
                            <p class="font-bold text-[9px] text-gray-700 leading-tight"><?php echo e(Str::limit($record->santri->desa, 35) ?? '-'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="w-[32%] flex flex-col items-end justify-center">
                    <div class="bg-white p-1 border border-gray-200 rounded shadow-sm">
                        <?php echo QrCode::size(72)->margin(0)->color(20, 20, 20)->backgroundColor(255, 255, 255)->generate($record->qr_token); ?>

                    </div>
                    <span class="text-[6px] font-mono text-gray-400 mt-1 mr-1">ID: <?php echo e($record->santri->nis); ?></span>
                </div>
            </div>

            <div class="relative z-10 bg-emerald-50 h-[4mm] border-t border-emerald-100 flex items-center justify-between px-4 mx-[1px] mb-[1px] rounded-b-[4px]">
                <span class="text-[6px] text-emerald-700 font-bold uppercase tracking-widest">Kartu Pulang</span>
                <span class="text-[6px] text-emerald-600 italic">Harap dibawa saat kembali</span>
            </div>

        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/perpulangan/print.blade.php ENDPATH**/ ?>