<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran #<?php echo e($transaksi->id); ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 14px; color: #333; max-width: 80mm; margin: 0 auto; padding: 10px; background: #fff; }
        .header { text-align: center; border-bottom: 1px dashed #333; padding-bottom: 10px; margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .info { margin-bottom: 10px; }
        .info table { width: 100%; }
        .info td { vertical-align: top; }
        .nominal { font-size: 18px; font-weight: bold; text-align: right; margin: 15px 0; border-top: 1px dashed #333; border-bottom: 1px dashed #333; padding: 10px 0; }
        .footer { text-align: center; font-size: 10px; margin-top: 20px; color: #666; }
        @media print {
            body { max-width: 100%; margin: 0; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2><?php echo e(config('app.name')); ?></h2>
        <p>Bukti Pembayaran PPDB</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td>Tgl</td>
                <td>: <?php echo e($transaksi->created_at->format('d/m/Y H:i')); ?></td>
            </tr>
            <tr>
                <td>Reff</td>
                <td>: <?php echo e($transaksi->order_id); ?></td>
            </tr>
            <tr>
                <td>Santri</td>
                <td>: <?php echo e($transaksi->calonSantri->nama_lengkap); ?></td>
            </tr>
            <tr>
                <td>Metode</td>
                <td>: <?php echo e(strtoupper($transaksi->payment_type)); ?></td>
            </tr>
        </table>
    </div>

    <div class="nominal">
        Rp <?php echo e(number_format($transaksi->gross_amount, 0, ',', '.')); ?>

    </div>

    <div style="text-align: right; font-size: 12px;">
        <p>Sisa Tagihan: Rp <?php echo e(number_format($transaksi->calonSantri->sisa_tagihan, 0, ',', '.')); ?></p>
    </div>

    <div class="footer">
        <p>Terima kasih.<br>Simpan struk ini sebagai bukti pembayaran yang sah.</p>
        <p>Admin: <?php echo e(auth()->user()->name ?? 'Petugas'); ?></p>
    </div>

    <button class="no-print" style="margin-top:20px; width:100%; padding:10px; cursor:pointer;" onclick="window.print()">Cetak Ulang</button>

</body>
</html><?php /**PATH C:\laragon\www\keuangan-pesantren\resources\views/adminpondok/ppdb/pendaftar/struk.blade.php ENDPATH**/ ?>