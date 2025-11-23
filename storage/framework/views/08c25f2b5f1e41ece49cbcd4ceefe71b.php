<!DOCTYPE html>
<html>
<head>
    <title>Label Aset - <?php echo e($lokasi->name); ?></title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 20px;
        }
        
        /* Header Laporan (Judul Halaman) */
        .header-page {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }
        .header-page h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header-page p {
            margin: 5px 0 0;
            font-size: 10pt;
            color: #666;
        }

        /* Container Stiker */
        .container {
            width: 100%;
        }

        /* Kotak Label Individual */
        .label-box {
            width: 32%; /* 3 Kolom per baris */
            float: left;
            margin-right: 1%;
            margin-bottom: 15px;
            border: 1px dashed #999; /* Garis potong putus-putus */
            padding: 8px;
            text-align: center;
            height: 130px; /* Tinggi tetap agar seragam */
            box-sizing: border-box;
            overflow: hidden;
            position: relative;
        }

        /* Agar kolom ketiga tidak punya margin kanan (opsional, tapi margin 1% cukup aman) */
        .label-box:nth-child(3n) {
            margin-right: 0;
        }

        .pondok-name {
            font-size: 7pt;
            color: #666;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .barang-name {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #000;
        }

        .barcode-img {
            display: block;
            margin: 5px auto;
            height: 45px; /* Tinggi barcode */
            width: 90%;   /* Lebar menyesuaikan kotak */
            max-width: 200px;
        }

        .kode-text {
            font-size: 9pt;
            letter-spacing: 2px;
            font-family: 'Courier New', Courier, monospace; /* Font monospaced agar angka sejajar */
            font-weight: bold;
            margin-top: 2px;
        }

        /* Helper untuk baris baru */
        .clear {
            clear: both;
        }

        /* Page Break untuk cetak banyak halaman */
        .page-break {
            page-break-after: always;
            clear: both;
        }
    </style>
</head>
<body>

    
    <div class="header-page">
        <h2>LABEL ASET: <?php echo e($lokasi->name); ?></h2>
        <p><?php echo e(Auth::user()->pondokStaff->pondok->name ?? 'PONDOK PESANTREN'); ?></p>
    </div>

    <div class="container">
        <?php $count = 0; ?>

        <?php $__currentLoopData = $barangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
            <?php if(!empty($item->code)): ?>
                <div class="label-box">
                    <div class="pondok-name"><?php echo e(Str::limit(Auth::user()->pondokStaff->pondok->name ?? 'MILIK PONDOK', 30)); ?></div>
                    
                    <div class="barang-name"><?php echo e(Str::limit($item->name, 22)); ?></div>
                    
                    
                    <img class="barcode-img" 
                         src="data:image/png;base64,<?php echo e(base64_encode($generator->getBarcode($item->code, $generator::TYPE_CODE_128))); ?>">
                    
                    <div class="kode-text"><?php echo e($item->code); ?></div>
                </div>

                <?php $count++; ?>

                
                <?php if($count % 3 == 0): ?>
                    <div class="clear"></div>
                <?php endif; ?>

                
                <?php if($count % 24 == 0): ?> 
                    <div class="page-break"></div>
                    
                    
                    <div class="header-page" style="margin-top: 20px;">
                        <h2>LABEL ASET: <?php echo e($lokasi->name); ?> (Lanjutan)</h2>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/inventaris/barang/pdf_labels.blade.php ENDPATH**/ ?>