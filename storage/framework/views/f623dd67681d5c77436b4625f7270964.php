<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo e($judul); ?></title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 3px; text-align: center; vertical-align: middle; }
        th { background-color: #e0e0e0; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2, .header h3 { margin: 2px; }
        .nama { text-align: left; width: 150px; padding-left: 5px; }
        .legend { margin-top: 10px; font-size: 9px; }
        .text-red { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h2><?php echo e($judul); ?></h2>
        <h3><?php echo e($sekolah->nama_sekolah); ?></h3>
    </div>
    
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 20px;">No</th>
                <th rowspan="2" class="nama">Nama Siswa</th>
                <th rowspan="2">Kelas</th>
                <?php for($i=1; $i<=31; $i++): ?>
                    <th style="width: 16px;"><?php echo e($i); ?></th>
                <?php endfor; ?>
                <th colspan="3" style="background-color: #d1e7dd;">Total</th>
            </tr>
            <tr>
                <?php for($i=1; $i<=31; $i++): ?> <th></th> <?php endfor; ?>
                <th style="width: 20px; background-color: #d1e7dd;">H</th>
                <th style="width: 20px; background-color: #fff3cd;">T</th> 
                <th style="width: 20px; background-color: #f8d7da;">A</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php 
                    $h=0; $t=0; $a=0; 
                ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td class="nama"><?php echo e($item['santri']->full_name); ?></td>
                    <td><?php echo e($item['santri']->kelas->nama_kelas ?? '-'); ?></td>
                    <?php for($d=1; $d<=31; $d++): ?>
                        <?php
                            $isValidDate = checkdate($bulan, $d, $tahun);
                            $kode = '';
                            
                            if ($isValidDate) {
                                $tglObj = \Carbon\Carbon::createFromDate($tahun, $bulan, $d);
                                $key = $tglObj->format('Y-m-d');
                                $log = $item['logs'][$key] ?? null;
                                
                                if ($log) {
                                    if($log->status_masuk == 'tepat_waktu') { 
                                        $kode = 'H'; $h++; 
                                    } elseif($log->status_masuk == 'terlambat') { 
                                        $kode = 'T'; $t++; 
                                    }
                                } else {
                                    // Logika sederhana: jika tidak ada log, anggap '-' atau 'A' jika hari sekolah
                                    // Untuk akurasi lebih baik, perlu cek tabel Hari Libur & Hari Kerja di sini
                                    $kode = '-'; 
                                }
                            }
                        ?>
                        <td class="<?php echo e($kode == 'T' ? 'text-red' : ''); ?>"><?php echo e($isValidDate ? $kode : ''); ?></td>
                    <?php endfor; ?>
                    <td style="background-color: #d1e7dd; font-weight: bold;"><?php echo e($h); ?></td>
                    <td style="background-color: #fff3cd;"><?php echo e($t); ?></td>
                    <td style="background-color: #f8d7da;"><?php echo e($a); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="legend">
        <strong>Keterangan:</strong> H=Hadir Tepat Waktu, T=Terlambat, - = Tidak Ada Data Scan
    </div>
</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/sekolah/admin/laporan/pdf/siswa-sekolah.blade.php ENDPATH**/ ?>