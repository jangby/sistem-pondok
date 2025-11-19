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
        .info-table td { padding: 2px; }
        
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 5px; text-align: center; vertical-align: middle; }
        .data-table th { background-color: #f0f0f0; font-weight: bold; }
        .nama { text-align: left; padding-left: 8px; }
        
        .footer { margin-top: 40px; width: 100%; }
        .ttd-box { float: right; width: 200px; text-align: center; }
        .ttd-line { margin-top: 60px; border-bottom: 1px solid #000; }
    </style>
</head>
<body>
    <div class="header">
        <h2>FORMAT PENILAIAN SISWA</h2>
        <h3><?php echo e($kegiatan->sekolah->nama_sekolah); ?></h3>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Kegiatan</strong></td>
            <td width="35%">: <?php echo e($kegiatan->nama_kegiatan); ?></td>
            <td width="15%"><strong>Mata Pelajaran</strong></td>
            <td width="35%">: <?php echo e($mapel->nama_mapel); ?></td>
        </tr>
        <tr>
            <td><strong>Kelas</strong></td>
            <td>: <?php echo e($kelas->nama_kelas); ?></td>
            <td><strong>Tanggal</strong></td>
            <td>: ______________________</td> 
        </tr>
    </table>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">NIS</th>
                <th style="width: 50%;">Nama Siswa</th>
                <th style="width: 25%;">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $santris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $santri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($santri->nis); ?></td>
                    <td class="nama"><?php echo e($santri->full_name); ?></td>
                    <td></td> 
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd-box">
            <p>Pengawas Ujian,</p>
            <div class="ttd-line"></div>
            <p>( ........................................... )</p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/laporan-nilai/pdf/format-nilai.blade.php ENDPATH**/ ?>