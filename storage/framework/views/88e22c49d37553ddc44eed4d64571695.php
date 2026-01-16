<!DOCTYPE html>
<html>
<head>
    <title>Laporan Perpulangan</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .section-title { font-size: 12pt; font-weight: bold; margin-top: 20px; margin-bottom: 10px; text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; font-size: 9pt; }
        th { background-color: #f0f0f0; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">Data Perpulangan Santri</h2>
        <h3 style="margin:5px 0;"><?php echo e($event->judul); ?></h3>
        <p>Kategori: <?php echo e($status == 'all' ? 'Semua Data' : ucwords(str_replace('_', ' ', $status))); ?></p>
    </div>

    <?php $__currentLoopData = ['Putra' => $recordsPutra, 'Putri' => $recordsPutri]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="section-title">Santri <?php echo e($label); ?></div>
        
        <?php if($data->isEmpty()): ?>
            <p><i>Tidak ada data.</i></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%" class="text-center">No</th>
                        <th style="width: 30%">Nama</th>
                        <th style="width: 15%">Kelas</th>
                        <th style="width: 50%">Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $alamat = $rec->santri->alamat ?? '';
                        $alamat .= $rec->santri->desa ? ", Ds. {$rec->santri->desa}" : '';
                        $alamat .= $rec->santri->kecamatan ? ", Kec. {$rec->santri->kecamatan}" : '';
                    ?>
                    <tr>
                        <td class="text-center"><?php echo e($index + 1); ?></td>
                        <td>
                            <?php echo e($rec->santri->full_name); ?><br>
                            <small style="color: #666;"><?php echo e($rec->santri->nis); ?></small>
                        </td>
                        <td><?php echo e($rec->santri->kelas->nama_kelas ?? '-'); ?></td>
                        <td><?php echo e($alamat); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pengurus/perpulangan/pdf_export.blade.php ENDPATH**/ ?>