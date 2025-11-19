<!DOCTYPE html>
<html>
<head>
    <title><?php echo e($judul); ?></title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 3px; text-align: center; }
        th { background-color: #f2f2f2; }
        .nama { text-align: left; width: 150px; }
        .libur { background-color: #ddd; }
    </style>
</head>
<body>
    <h2><?php echo e($judul); ?></h2>
    <h3><?php echo e($sekolah->nama_sekolah); ?></h3>
    
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="nama">Nama Guru</th>
                <?php for($i=1; $i<=31; $i++): ?>
                    <th><?php echo e($i); ?></th>
                <?php endfor; ?>
                <th colspan="4">Total</th>
            </tr>
            <tr>
                
                <?php for($i=1; $i<=31; $i++): ?> <th></th> <?php endfor; ?>
                <th>H</th> <th>S</th> <th>I</th> <th>A</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $h=0; $s=0; $i_cnt=0; $a=0; ?>
                <tr>
                    <td class="nama"><?php echo e($item['guru']->name); ?></td>
                    <?php for($d=1; $d<=31; $d++): ?>
                        <?php
                            $tgl = \Carbon\Carbon::createFromDate($tahun, $bulan, $d);
                            $key = $tgl->format('Y-m-d');
                            $log = $item['logs'][$key] ?? null;
                            $kode = '';
                            
                            if ($log) {
                                if($log->status == 'hadir') { $kode = 'H'; $h++; }
                                elseif($log->status == 'sakit') { $kode = 'S'; $s++; }
                                elseif($log->status == 'izin') { $kode = 'I'; $i_cnt++; }
                                elseif($log->status == 'alpa') { $kode = 'A'; $a++; }
                            }
                        ?>
                        <td><?php echo e($kode); ?></td>
                    <?php endfor; ?>
                    <td><?php echo e($h); ?></td> <td><?php echo e($s); ?></td> <td><?php echo e($i_cnt); ?></td> <td><?php echo e($a); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/laporan/pdf/guru-sekolah.blade.php ENDPATH**/ ?>