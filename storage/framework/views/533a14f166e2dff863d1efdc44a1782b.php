<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Perpustakaan & Lab</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;800&display=swap');
        
        body {
            font-family: 'Open Sans', sans-serif;
            background: #e5e5e5; 
            margin: 0;
            padding: 20px;
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        /* === DESAIN KARTU UTAMA === */
        .id-card {
            width: 85.6mm;
            height: 54mm;
            background-color: #fff;
            background-image: radial-gradient(#f3f4f6 1px, transparent 1px);
            background-size: 10px 10px;
            border: 1px solid #bbb;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
        }

        /* Watermark Logo */
        .watermark {
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120px;
            opacity: 0.08;
            z-index: 0;
            pointer-events: none;
        }

        /* === HEADER === */
        .header {
            background: #004d40; 
            color: white;
            padding: 6px 8px;
            display: flex;
            align-items: center;
            border-bottom: 3px solid #fbbf24; 
            position: relative;
            z-index: 1;
            height: 42px;
        }

        .logo-box {
            width: 38px;
            height: 38px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .logo-box img {
            width: 32px;
            height: auto;
        }

        .header-text {
            flex: 1;
            text-align: center;
        }

        .header-title {
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1px;
            opacity: 0.9;
        }

        .school-name {
            font-size: 9pt;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 1px;
            line-height: 1;
            color: #fff;
        }

        .address {
            font-size: 4.5pt;
            line-height: 1.1;
            opacity: 0.8;
            font-weight: 400;
        }

        /* === BODY === */
        .card-body {
            padding: 4px 15px;
            flex: 1;
            position: relative; 
            z-index: 5; /* Layer body di atas background */
        }

        .name-section {
            margin-top: 2px;
            margin-bottom: 6px;
            text-align: center;
            border-bottom: 1px dashed #eee;
            padding-bottom: 4px;
        }

        .student-name {
            font-size: 13pt;
            font-weight: 800;
            color: #111;
            text-transform: uppercase;
            line-height: 1.1;
            letter-spacing: -0.3px;
        }

        /* Info Data (Kiri) */
        .info-left {
            width: 65%;
            float: left;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 1px 0;
            font-size: 9.5pt; 
            font-weight: 600;
            color: #333;
            text-align: left;
        }
        
        .info-table td:first-child {
            width: 55px;
            color: #555;
            font-weight: 400;
        }
        
        .info-table td:nth-child(2) {
            width: 10px;
            text-align: center;
        }

        /* === FOOTER (BARCODE) === */
        .footer {
            background: white;
            padding: 2px 0 6px 0;
            text-align: center;
            position: relative;
            z-index: 2; /* Layer di bawah QR Code tapi di atas watermark */
            border-top: 1px solid #f0f0f0;
            margin-top: auto;
        }

        .barcode-img {
            height: 38px; 
            max-width: 96%;
            display: block;
            margin: 4px auto 0 auto;
        }

        /* === QR CODE ABSOLUT (Layer Paling Atas) === */
        /* Posisi diubah menjadi absolut terhadap .id-card, bukan .card-body */
        .qr-absolute {
            position: absolute;
            right: 30px;    /* Sesuai permintaan (30px dari kanan) */
            bottom: 28px;   /* Jarak dari bawah kartu (overlap dengan area footer) */
            width: 74px;    
            height: 74px;
            background: #fff;
            border: 1px solid #ccc;
            padding: 2px;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2); /* Shadow sedikit dipertebal agar terlihat mengambang */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999; /* LAYER TERTINGGI: Pasti di atas footer & elemen lain */
        }
        
        .qr-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* === PRINT SETTINGS === */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            .card-container {
                gap: 0;
                display: block;
            }
            .id-card {
                float: left;
                margin: 2mm;
                border: 1px dashed #999;
                box-shadow: none;
            }
            @page {
                margin: 5mm;
                size: A4;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="card-container">
        <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="id-card">
            
            
            <img src="<?php echo e(asset('uploads/logos/logo-pondok.jpg')); ?>" 
                 onerror="this.src='<?php echo e(asset('image_5a2b20.png')); ?>'"
                 class="watermark" alt="Watermark">

            
            <div class="header">
                <div class="logo-box">
                    <img src="<?php echo e(asset('uploads/logos/logo-pondok.jpg')); ?>" 
                         onerror="this.style.display='none'" alt="Logo">
                </div>
                <div class="header-text">
                    <div class="header-title">Kartu Perpustakaan & Lab Komputer</div>
                    <div class="school-name">SMP & SMA Assa'adah Limbangan</div>
                    <div class="address">
                        Jl Raya Limbangan Tengah No. 104 Telp. (0262)431010<br>
                        Bl. Limbangan Garut 44186
                    </div>
                </div>
            </div>

            
            <div class="card-body">
                
                <div class="name-section">
                    <div class="student-name"><?php echo e(Str::limit($santri->full_name, 22)); ?></div>
                </div>

                
                <div class="info-left">
                    <table class="info-table">
                        <tr>
                            <td>NIS</td>
                            <td>:</td>
                            <td><?php echo e($santri->nis); ?></td>
                        </tr>
                        <tr>
                            <td>Jenjang</td>
                            <td>:</td>
                            <td><?php echo e($santri->kelas ? strtoupper($santri->kelas->tingkat) : '-'); ?></td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td>:</td>
                            <td><?php echo e($santri->kelas ? $santri->kelas->nama_kelas : '-'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            
            <div class="footer">
                <img class="barcode-img" 
                     src="https://bwipjs-api.metafloor.com/?bcid=code128&text=<?php echo e($santri->nis); ?>&scale=4&height=16&includetext&textxalign=center&textsize=12" 
                     alt="Barcode <?php echo e($santri->nis); ?>">
            </div>

            
            <div class="qr-absolute">
                <img class="qr-img" 
                     src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text=<?php echo e($santri->nis); ?>&scale=5" 
                     alt="QR <?php echo e($santri->nis); ?>">
            </div>

        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/superadmin/perpus/anggota/print-card.blade.php ENDPATH**/ ?>