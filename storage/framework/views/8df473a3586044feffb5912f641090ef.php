<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo e($judul); ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header h3 { margin: 5px 0; font-size: 14px; }
        
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { padding: 2px; vertical-align: top;}
        
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 8px 5px; vertical-align: middle; }
        .data-table th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        .center { text-align: center; }
        .nama { text-align: left; padding-left: 8px; }
        
        .footer { margin-top: 40px; width: 100%; }
        .ttd-box { float: right; width: 200px; text-align: center; }
        .ttd-line { margin-top: 60px; border-bottom: 1px solid #000; }
    </style>
</head>
<body>
    <div class="header">
        <h2>DAFTAR HADIR PESERTA UJIAN</h2>
        <h3><?php echo e($pondok->nama_pondok ?? 'PONDOK PESANTREN'); ?></h3>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Ujian</strong></td>
            <td width="35%">: <?php echo e(ucfirst($jadwal->jenis_ujian)); ?> <?php echo e(ucfirst($jadwal->semester)); ?></td>
            <td width="15%"><strong>Mapel/Kitab</strong></td>
            <td width="35%">: <?php echo e($jadwal->mapel->nama_mapel); ?></td>
        </tr>
        <tr>
            <td><strong>Mustawa</strong></td>
            <td>: <?php echo e($jadwal->mustawa->nama); ?></td>
            <td><strong>Waktu</strong></td>
            <td>: <?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->isoFormat('D MMMM Y')); ?></td>
        </tr>
    </table>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">NIS</th>
                <th style="width: 45%;">Nama Santri</th>
                <th style="width: 35%;">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="center"><?php echo e($index + 1); ?></td>
                    <td class="center"><?php echo e($santri->nis); ?></td>
                    <td class="nama"><?php echo e($santri->full_name); ?></td>
                    <td>
                        
                        <?php if(($index + 1) % 2 != 0): ?>
                            <div style="text-align: left; padding-left: 10px;"><?php echo e($index + 1); ?>. ..........</div>
                        <?php else: ?>
                            <div style="text-align: center; padding-left: 50px;"><?php echo e($index + 1); ?>. ..........</div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd-box">
            <p>Pengawas Ujian,</p>
            <div class="ttd-line"></div>
            <p>( <?php echo e($jadwal->pengawas->nama_lengkap ?? '.......................'); ?> )</p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/ujian/pdf/daftar-hadir.blade.php ENDPATH**/ ?>