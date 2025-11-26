<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Perpustakaan - Depan & Belakang</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap');
        
        body {
            font-family: 'Open Sans', sans-serif;
            background: #e5e5e5; 
            margin: 0;
            padding: 20px;
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact;
        }

        /* Container per halaman (Depan / Belakang) */
        .page-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-bottom: 50px; /* Jarak antar "Halaman" di tampilan layar */
        }

        /* === BASIS KARTU (Ukuran ID Card Standar) === */
        .id-card {
            width: 85.6mm;
            height: 54mm;
            background-color: #fff;
            border: 1px solid #bbb; /* Border untuk panduan potong */
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
        }

        /* Background Pattern (Dipakai Depan & Belakang) */
        .bg-pattern {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(#f3f4f6 1px, transparent 1px);
            background-size: 10px 10px;
            z-index: 0;
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

        /* === BAGIAN DEPAN === */
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

        .card-body-front {
            padding: 8px 15px;
            flex: 1;
            position: relative; 
            z-index: 5;
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Isi di atas */
        }

        .name-section {
            margin-top: 5px;
            margin-bottom: 10px;
            text-align: center;
            border-bottom: 2px solid #eee; /* Garis pemisah dipertegas */
            padding-bottom: 8px;
        }

        .student-name {
            font-size: 14pt; /* Nama diperbesar karena footer hilang */
            font-weight: 800;
            color: #111;
            text-transform: uppercase;
            line-height: 1.1;
            letter-spacing: -0.3px;
        }

        /* Info Table Depan */
        .info-left {
            width: 70%; /* Ruang lebih lega untuk alamat */
            float: left;
        }

        .info-table td {
            padding: 3px 0;
            font-size: 9pt; 
            font-weight: 600;
            color: #333;
            text-align: left;
            vertical-align: top;
            line-height: 1.2;
        }
        
        .info-table td:first-child {
            width: 70px;
            color: #555;
            font-weight: 400;
        }

        /* QR Code (Tetap di depan) */
        .qr-absolute {
            position: absolute;
            right: 20px;    
            bottom: 20px;   
            width: 70px;    
            height: 70px;
            background: #fff;
            border: 1px solid #ccc;
            padding: 2px;
            border-radius: 6px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999; 
        }
        
        .qr-img { width: 100%; height: 100%; object-fit: contain; }

        /* === BAGIAN BELAKANG === */
        .card-back-content {
            position: relative;
            z-index: 5;
            padding: 15px;
            height: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .rules-title {
            text-align: center;
            font-weight: 800;
            font-size: 10pt;
            text-transform: uppercase;
            color: #004d40;
            border-bottom: 2px solid #fbbf24;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .rules-list {
            font-size: 7.5pt;
            line-height: 1.4;
            padding-left: 15px;
            margin: 0;
            color: #333;
            flex: 1; /* Mengisi ruang kosong */
        }

        .rules-list li { margin-bottom: 3px; text-align: justify; padding-right: 5px; }

        .signature-area {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto; /* Dorong ke bawah */
            height: 60px;
        }

        .barcode-area {
            text-align: center;
            width: 60%;
        }

        .barcode-img-back {
            height: 35px; 
            max-width: 100%;
            display: block;
            margin: 0 auto;
        }

        .ttd-area {
            width: 35%;
            text-align: center;
            font-size: 6pt;
        }
        .ttd-line {
            border-bottom: 1px solid #000;
            margin-top: 25px;
            margin-bottom: 2px;
        }

        /* === PRINT SETTINGS (PENTING) === */
        @media print {
            body { background: white; padding: 0; margin: 0; }
            
            .card-container { 
                gap: 0; 
                display: block; 
                margin-bottom: 0; 
            }
            
            .id-card {
                float: left;
                margin: 2mm;
                border: 1px dashed #ccc; /* Border tipis saat print untuk panduan potong */
                box-shadow: none;
                page-break-inside: avoid;
            }

            /* Memaksa Halaman Baru antara Depan dan Belakang */
            .page-break {
                clear: both;
                display: block;
                page-break-before: always;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="card-container">
        <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="id-card">
            <div class="bg-pattern"></div>
            
            
            <img src="<?php echo e(asset('uploads/logos/logo-pondok.jpg')); ?>" 
                 onerror="this.src='<?php echo e(asset('image_5a2b20.png')); ?>'"
                 class="watermark" alt="Watermark">

            
            <div class="header">
                <div class="logo-box">
                    <img src="<?php echo e(asset('uploads/logos/logo-pondok.jpg')); ?>" 
                         onerror="this.style.display='none'" alt="Logo">
                </div>
                <div class="header-text">
                    <div class="header-title">Kartu Perpustakaan</div>
                    <div class="school-name">Pondok Pesantren Assa'adah</div>
                    <div class="address">
                        Jl Raya Limbangan Tengah No. 104 Garut 44186
                    </div>
                </div>
            </div>

            
            <div class="card-body-front">
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
                            <td>Alamat</td>
                            <td>:</td>
                            <td><?php echo e($santri->alamat ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <td>Berlaku</td>
                            <td>:</td>
                            <td style="font-size: 7pt; font-weight: 800; color: #004d40;">
                                Selama Menjadi Santri
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            
            <div class="qr-absolute">
                <img class="qr-img" 
                     src="https://bwipjs-api.metafloor.com/?bcid=qrcode&text=<?php echo e($santri->nis); ?>&scale=5" 
                     alt="QR <?php echo e($santri->nis); ?>">
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="page-break"></div>

    <div class="card-container">
        <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="id-card">
            <div class="bg-pattern"></div>
            
            <img src="<?php echo e(asset('uploads/logos/logo-pondok.jpg')); ?>" 
                 onerror="this.src='<?php echo e(asset('image_5a2b20.png')); ?>'"
                 class="watermark" style="opacity: 0.05;" alt="Watermark">

            <div class="card-back-content">
                <div class="rules-title">TATA TERTIB PERPUSTAKAAN</div>
                
                <ol class="rules-list">
                    <li>Kartu ini wajib dibawa setiap kali mengunjungi perpustakaan atau meminjam buku.</li>
                    <li>Kartu tidak dapat dipindahtangankan kepada orang lain.</li>
                    <li>Keterlambatan pengembalian buku akan dikenakan sanksi/denda sesuai ketentuan pondok.</li>
                    <li>Apabila kartu ini hilang atau rusak, harap segera melapor ke petugas perpustakaan.</li>
                    <li>Menjaga kebersihan dan keutuhan buku yang dipinjam adalah kewajiban anggota.</li>
                </ol>

                <div class="signature-area">
                    
                    <div class="barcode-area">
                        <img class="barcode-img-back" 
                             src="https://bwipjs-api.metafloor.com/?bcid=code128&text=<?php echo e($santri->nis); ?>&scale=4&height=14&includetext&textxalign=center&textsize=10" 
                             alt="Barcode <?php echo e($santri->nis); ?>">
                    </div>

                    
                    <div class="ttd-area">
                        <div>Kepala Perpustakaan</div>
                        <div class="ttd-line"></div>
                        <div style="font-weight: bold;">( ........................ )</div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/superadmin/perpus/anggota/print-card.blade.php ENDPATH**/ ?>