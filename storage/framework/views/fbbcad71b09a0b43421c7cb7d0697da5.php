<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($judul ?? 'Cetak Rapor'); ?></title>
    <style>
        /* Reset CSS sederhana untuk cetak */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
            background-color: #fff; /* Pastikan background putih */
        }

        /* Style khusus Halaman Cetak */
        .page {
            position: relative;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            page-break-after: always; /* Penting: Paksa ganti halaman setiap 1 santri */
            overflow: hidden;
        }

        /* Menghapus page-break di halaman terakhir agar tidak ada kertas kosong */
        .page:last-child {
            page-break-after: auto;
        }

        /* Style tabel bawaan dari TinyMCE agar border tetap muncul saat diprint */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table td, table th {
            border: 1px solid #000;
            padding: 4px;
        }

        /* CSS khusus jika menggunakan dompdf */
        @page {
            margin: <?php echo e($template->margin_top ?? 10); ?>mm <?php echo e($template->margin_right ?? 10); ?>mm <?php echo e($template->margin_bottom ?? 10); ?>mm <?php echo e($template->margin_left ?? 15); ?>mm;
        }
    </style>
</head>
<body>

    <?php $__currentLoopData = $rapors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $raporHtml): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="page">
            <?php echo $raporHtml; ?>

        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <script>
        // Jika tidak mode download (hanya preview HTML), otomatis buka dialog print
        <?php if(!request()->has('download')): ?>
            window.print();
        <?php endif; ?>
    </script>
</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/pendidikan/admin/rapor/print.blade.php ENDPATH**/ ?>