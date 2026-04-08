<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Piket Penjaga Gerbang</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #000;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        
        /* Kop Laporan */
        .kop-surat { width: 100%; border-bottom: 3px solid #1e3a8a; padding-bottom: 10px; margin-bottom: 15px; }
        .kop-surat td { vertical-align: middle; }
        .logo { width: 70px; height: auto; }
        .title-header { text-align: center; }
        .title-header h1 { font-size: 20pt; font-weight: bold; margin: 0; color: #1e3a8a; text-transform: uppercase; }
        .title-header p { font-size: 11pt; margin: 3px 0 0 0; color: #333; }

        /* Waktu Berlaku */
        .periode { 
            text-align: center; font-size: 12pt; font-weight: bold; margin-bottom: 15px; padding: 8px; 
            background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; text-transform: uppercase;
        }

        /* Grid Table 2 Kolom */
        table.grid-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.grid-table td { 
            border: 2px solid #1e3a8a; 
            vertical-align: top; 
            width: 50%; 
            padding: 0; 
            height: 170px; /* Memaksa tinggi kotak agar proporsional */
        }

        /* Header Hari */
        .hari-header { 
            background-color: #1e3a8a; color: #fff; font-size: 13pt; font-weight: bold; 
            text-align: center; padding: 6px 0; text-transform: uppercase; border-bottom: 1px solid #1e3a8a; 
        }

        /* List Nama */
        .nama-list { padding: 5px 15px 5px 5px; }
        .nama-list ol { margin: 0; padding-left: 25px; font-size: 13pt; font-weight: bold; }
        .nama-list ol li { padding: 4px 0; border-bottom: 1px dashed #cbd5e1; }
        .nama-list ol li:last-child { border-bottom: none; }
        .kosong { color: #64748b; font-style: italic; text-align: center; font-size: 11pt; margin-top: 25px; font-weight: normal; }

        /* Kotak Catatan (Pojok Kanan Bawah) */
        .catatan-cell { background-color: #fffbeb; padding: 15px !important; }
        .catatan-cell h4 { margin: 0 0 8px 0; color: #b45309; font-size: 13pt; text-align: center; text-decoration: underline;}
        .catatan-cell ul { margin: 0; padding-left: 18px; font-size: 11pt; color: #92400e; }
        .catatan-cell ul li { margin-bottom: 8px; }

    </style>
</head>
<body>

    <table class="kop-surat">
        <tr>
            <td width="15%" style="text-align: center;">
                <?php $logoPath = public_path('logo-pondok.jpg'); ?>
                <?php if(file_exists($logoPath)): ?>
                    <img src="<?php echo e($logoPath); ?>" class="logo" alt="Logo">
                <?php endif; ?>
            </td>
            <td width="85%" class="title-header">
                <h1>Jadwal Piket Penjaga Gerbang</h1>
                <p>Bagian Keamanan & Ketertiban Pondok Pesantren</p>
            </td>
        </tr>
    </table>

    <div class="periode">
        BERLAKU MULAI: <?php echo e(\Carbon\Carbon::now()->locale('id')->isoFormat('MMMM Y')); ?>

    </div>

    <table class="grid-table">
        
        <tr>
            <td>
                <div class="hari-header">SENIN</div>
                <div class="nama-list">
                    <?php if(isset($jadwalPerHari['Senin']) && $jadwalPerHari['Senin']->count() > 0): ?>
                        <ol>
                            <?php $__currentLoopData = $jadwalPerHari['Senin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($jadwal->santri->full_name); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    <?php else: ?>
                        <p class="kosong">- Kosong (Belum ada jadwal) -</p>
                    <?php endif; ?>
                </div>
            </td>
            
            <td>
                <div class="hari-header">KAMIS</div>
                <div class="nama-list">
                    <?php if(isset($jadwalPerHari['Kamis']) && $jadwalPerHari['Kamis']->count() > 0): ?>
                        <ol>
                            <?php $__currentLoopData = $jadwalPerHari['Kamis']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($jadwal->santri->full_name); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    <?php else: ?>
                        <p class="kosong">- Kosong (Belum ada jadwal) -</p>
                    <?php endif; ?>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div class="hari-header">SELASA</div>
                <div class="nama-list">
                    <?php if(isset($jadwalPerHari['Selasa']) && $jadwalPerHari['Selasa']->count() > 0): ?>
                        <ol>
                            <?php $__currentLoopData = $jadwalPerHari['Selasa']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($jadwal->santri->full_name); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    <?php else: ?>
                        <p class="kosong">- Kosong (Belum ada jadwal) -</p>
                    <?php endif; ?>
                </div>
            </td>
            <td>
                <div class="hari-header">JUMAT</div>
                <div class="nama-list">
                    <?php if(isset($jadwalPerHari['Jumat']) && $jadwalPerHari['Jumat']->count() > 0): ?>
                        <ol>
                            <?php $__currentLoopData = $jadwalPerHari['Jumat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($jadwal->santri->full_name); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    <?php else: ?>
                        <p class="kosong">- Kosong (Belum ada jadwal) -</p>
                    <?php endif; ?>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div class="hari-header">RABU</div>
                <div class="nama-list">
                    <?php if(isset($jadwalPerHari['Rabu']) && $jadwalPerHari['Rabu']->count() > 0): ?>
                        <ol>
                            <?php $__currentLoopData = $jadwalPerHari['Rabu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($jadwal->santri->full_name); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    <?php else: ?>
                        <p class="kosong">- Kosong (Belum ada jadwal) -</p>
                    <?php endif; ?>
                </div>
            </td>
            <td>
                <div class="hari-header">SABTU</div>
                <div class="nama-list">
                    <?php if(isset($jadwalPerHari['Sabtu']) && $jadwalPerHari['Sabtu']->count() > 0): ?>
                        <ol>
                            <?php $__currentLoopData = $jadwalPerHari['Sabtu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($jadwal->santri->full_name); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    <?php else: ?>
                        <p class="kosong">- Kosong (Belum ada jadwal) -</p>
                    <?php endif; ?>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div class="hari-header" style="background-color: #dc2626; border-bottom: 1px solid #dc2626;">MINGGU</div>
                <div class="nama-list">
                    <?php if(isset($jadwalPerHari['Minggu']) && $jadwalPerHari['Minggu']->count() > 0): ?>
                        <ol>
                            <?php $__currentLoopData = $jadwalPerHari['Minggu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($jadwal->santri->full_name); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    <?php else: ?>
                        <p class="kosong">- Kosong (Belum ada jadwal) -</p>
                    <?php endif; ?>
                </div>
            </td>
            
            <td class="catatan-cell">
                <h4>PERHATIAN KHUSUS</h4>
                <ul>
                    <li><strong>WAJIB ABSEN</strong> Pagi & Sore di mesin Kios Absensi menggunakan PIN.</li>
                    <li>Dilarang meninggalkan pos tanpa izin pengurus/ustadz.</li>
                    <li>Jaga kebersihan area pos gerbang.</li>
                    <li>Tamu dari luar wajib melapor & mengisi buku tamu.</li>
                </ul>
            </td>
        </tr>

    </table>

</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/absensi/gerbang/pdf_jadwal.blade.php ENDPATH**/ ?>