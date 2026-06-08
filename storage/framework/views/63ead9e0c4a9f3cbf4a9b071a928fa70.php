<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Data Santri</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px; /* Ukuran font diperkecil agar muat 11 kolom */
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h2 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .header h3 { margin: 5px 0 0 0; font-size: 12px; font-weight: normal; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <?php $__currentLoopData = $dataEkspor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="header">
            <h2>DATA BIODATA SANTRI</h2>
            <h3>KELAS PESANTREN: <b><?php echo e($data['kelas']); ?></b> - KELOMPOK: <b><?php echo e($data['kategori']); ?></b></h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="7%">NIS</th>
                    <th width="7%">NISN</th>
                    <th width="10%">NIK</th>
                    <th width="15%">NAMA SANTRI</th>
                    <th width="12%">TEMPAT & TGL LAHIR</th>
                    <th width="3%">L/P</th>
                    <th width="10%">DETAIL EMIS</th>
                    <th width="10%">NAMA ORANG TUA</th>
                    <th width="8%">NO HP WALI</th>
                    <th width="15%">ALAMAT LENGKAP SANTRI</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data['santris']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-center"><?php echo e($index + 1); ?></td>
                    <td class="text-center"><?php echo e($santri->nis ?? '-'); ?></td>
                    <td class="text-center">-</td> <td class="text-center"><?php echo e($santri->nik ?? '-'); ?></td>
                    <td><?php echo e($santri->full_name); ?></td>
                    <td>
                        <?php echo e($santri->tempat_lahir ?? '-'); ?>, <br>
                        <?php echo e($santri->tanggal_lahir ? \Carbon\Carbon::parse($santri->tanggal_lahir)->format('d-m-Y') : '-'); ?>

                    </td>
                    <td class="text-center"><?php echo e($santri->jenis_kelamin == 'Laki-laki' ? 'L' : 'P'); ?></td>
                    <td><?php echo e($santri->detail_emis ?? '-'); ?></td>
                    <td>
                        <?php echo e($santri->nama_ayah ?? ($santri->orangTua->nama_ayah ?? '-')); ?>

                    </td>
                    <td class="text-center"><?php echo e($santri->orangTua->no_hp ?? '-'); ?></td>
                    <td>
                        <?php echo e($santri->alamat ?? '-'); ?> 
                        <?php if($santri->desa): ?> Ds. <?php echo e($santri->desa); ?> <?php endif; ?>
                        <?php if($santri->kecamatan): ?> Kec. <?php echo e($santri->kecamatan); ?> <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        
        <?php if(!$loop->last): ?>
            <div class="page-break"></div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/adminpondok/santris/pdf_export_mustawa.blade.php ENDPATH**/ ?>