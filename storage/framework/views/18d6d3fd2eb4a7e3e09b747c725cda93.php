<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Buku Tamu - <?php echo e($namaBulan); ?></title>
    <style>
        /* Pengaturan Dasar A4 Landscape */
        @page { margin: 30px 40px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10pt; color: #111; line-height: 1.4; }
        
        /* Kop Surat */
        .kop-surat { width: 100%; border-bottom: 3px solid #047857; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat td { vertical-align: middle; }
        .logo { width: 75px; height: auto; }
        .title-header { text-align: center; }
        .title-header h1 { font-size: 18pt; font-weight: bold; margin: 0; color: #047857; text-transform: uppercase; }
        .title-header p { font-size: 10pt; margin: 3px 0 0 0; color: #444; }

        /* Judul Laporan */
        .report-title { text-align: center; margin-bottom: 15px; }
        .report-title h3 { margin: 0; font-size: 14pt; text-transform: uppercase; text-decoration: underline;}
        .report-title p { margin: 5px 0 0 0; font-size: 11pt; font-weight: bold; }

        /* Tabel Data */
        table.table-data { width: 100%; border-collapse: collapse; font-size: 9.5pt; }
        .table-data th, .table-data td { border: 1px solid #94a3b8; padding: 8px 6px; vertical-align: top; }
        .table-data th { background-color: #047857; color: #fff; font-weight: bold; text-align: center; text-transform: uppercase; }
        .table-data tbody tr:nth-child(even) { background-color: #f8fafc; }
        
        /* Utilitas Text */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .badge { background-color: #f1f5f9; border: 1px solid #cbd5e1; padding: 2px 5px; border-radius: 3px; font-size: 8pt; }
        .waktu { font-family: 'Courier New', Courier, monospace; font-weight: bold; font-size: 9pt; }

        /* Tanda Tangan */
        .footer { margin-top: 30px; width: 100%; }
        .ttd-container { width: 250px; float: right; text-align: center; font-size: 10pt; }
        .ttd-name { font-weight: bold; text-decoration: underline; margin-top: 60px; text-transform: uppercase; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

    <table class="kop-surat">
        <tr>
            <td width="10%" style="text-align: center;">
                <?php $logoPath = public_path('logo-pondok.jpg'); ?>
                <?php if(file_exists($logoPath)): ?>
                    <img src="<?php echo e($logoPath); ?>" class="logo" alt="Logo">
                <?php endif; ?>
            </td>
            <td width="90%" class="title-header">
                <h1>Buku Tamu Pondok Pesantren</h1>
                <p>Bagian Keamanan dan Penerimaan Tamu</p>
            </td>
        </tr>
    </table>

    <div class="report-title">
        <h3>Laporan Rekapitulasi Kunjungan Tamu</h3>
        <p>Periode Bulan: <?php echo e($namaBulan); ?></p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Tanggal</th>
                <th width="18%">Nama Tamu</th>
                <th width="15%">Instansi / Asal</th>
                <th width="15%">Bertemu Dengan</th>
                <th width="22%">Keperluan</th>
                <th width="14%">Waktu (Masuk - Keluar)</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $tamus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tamu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($index + 1); ?></td>
                    <td class="text-center"><?php echo e(\Carbon\Carbon::parse($tamu->jam_masuk)->locale('id')->isoFormat('D MMM Y')); ?></td>
                    <td>
                        <span class="text-bold"><?php echo e($tamu->nama_tamu); ?></span><br>
                        <span style="font-size: 8pt; color: #555;"><?php echo e($tamu->no_hp ? 'HP: '.$tamu->no_hp : ''); ?></span>
                    </td>
                    <td><?php echo e($tamu->instansi_asal ?? '-'); ?></td>
                    <td class="text-bold"><?php echo e($tamu->bertemu_dengan); ?></td>
                    <td><?php echo e($tamu->keperluan); ?></td>
                    <td class="text-center">
                        <span class="waktu" style="color: #059669;"><?php echo e(\Carbon\Carbon::parse($tamu->jam_masuk)->format('H:i')); ?> WIB</span>
                        <br>s/d<br>
                        <?php if($tamu->jam_keluar): ?>
                            <span class="waktu" style="color: #dc2626;"><?php echo e(\Carbon\Carbon::parse($tamu->jam_keluar)->format('H:i')); ?> WIB</span>
                        <?php else: ?>
                            <span class="badge">Belum Keluar</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; font-style: italic; color: #64748b;">
                        Tidak ada catatan kunjungan tamu pada bulan ini.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer clearfix">
        <div class="ttd-container">
            <p style="margin-bottom: 5px;">Mengetahui,<br><?php echo e(\Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y')); ?></p>
            <p><strong>Komandan Keamanan</strong></p>
            <p class="ttd-name">Pengurus Jaga Gerbang</p>
        </div>
    </div>

</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/absensi/gerbang/pdf_buku_tamu.blade.php ENDPATH**/ ?>