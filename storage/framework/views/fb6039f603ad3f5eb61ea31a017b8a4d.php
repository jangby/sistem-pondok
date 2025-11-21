<!DOCTYPE html>
<html>
<head>
    <title>Ledger Nilai</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LEDGER NILAI UJIAN <?php echo e(strtoupper($jadwal->jenis_ujian)); ?></h2>
        <p><?php echo e($jadwal->mapel->nama_mapel); ?> - <?php echo e($jadwal->mustawa->nama); ?></p>
        <p>Tahun Ajaran: <?php echo e($jadwal->tahun_ajaran); ?> (<?php echo e(ucfirst($jadwal->semester)); ?>)</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Santri</th>
                <th>NIS</th>
                <th>Nilai Tulis</th>
                <th>Nilai Lisan</th>
                <th>Nilai Praktek</th>
                <th>Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $n = $santri->nilai_ujian; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($santri->full_name); ?></td>
                    <td><?php echo e($santri->nis); ?></td>
                    <td><?php echo e($n->nilai_tulis ?? 0); ?></td>
                    <td><?php echo e($n->nilai_lisan ?? 0); ?></td>
                    <td><?php echo e($n->nilai_praktek ?? 0); ?></td>
                    <td><strong><?php echo e($n->nilai_akhir ?? 0); ?></strong></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/ujian/pdf/ledger.blade.php ENDPATH**/ ?>