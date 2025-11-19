<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo e($judul); ?></title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; vertical-align: middle; }
        th { background-color: #e0e0e0; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2, .header h3 { margin: 2px; }
        .keterangan { margin-top: 15px; font-size: 10px; }
        .nama { text-align: left; width: 40%; }
        .nilai { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2><?php echo e($judul); ?></h2>
        <h3>Kelas: <?php echo e($kelas->nama_kelas); ?> | Periode: <?php echo e($kegiatan->tanggal_mulai->format('d M Y')); ?> - <?php echo e($kegiatan->tanggal_selesai->format('d M Y')); ?></h3>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th class="nama">Nama Siswa</th>
                <th style="width: 15%;">NIS</th>
                <th style="width: 15%;">Nilai (0-100)</th>
                <th style="width: 25%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $nilai = $existingNilai[$santri->id] ?? null;
                    $isMissing = is_null($nilai);
                ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td class="nama"><?php echo e($santri->full_name); ?></td>
                    <td><?php echo e($santri->nis); ?></td>
                    <td class="nilai" style="<?php echo e($isMissing ? 'background-color: #f8d7da;' : ''); ?>">
                        <?php echo e($isMissing ? 'BELUM' : number_format($nilai, 1)); ?>

                    </td>
                    <td style="text-align: left;">
                        <?php echo e($isMissing ? 'Nilai belum diinput oleh guru.' : ''); ?>

                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="keterangan">
        <p>Laporan ini mencakup semua siswa aktif di Kelas <?php echo e($kelas->nama_kelas); ?>.</p>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/laporan-nilai/pdf/ledger-nilai.blade.php ENDPATH**/ ?>