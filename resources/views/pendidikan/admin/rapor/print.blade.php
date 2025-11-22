<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul ?? 'Cetak Rapor' }}</title>
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

        /* Tambahan agar page break TinyMCE terbaca oleh DomPDF */
        .mce-pagebreak {
            page-break-before: always;
            display: block;
            height: 0;
            border: 0;
            margin: 0;
        }

        /* CSS khusus jika menggunakan dompdf */
        @page {
            margin: {{ $template->margin_top ?? 10 }}mm {{ $template->margin_right ?? 10 }}mm {{ $template->margin_bottom ?? 10 }}mm {{ $template->margin_left ?? 15 }}mm;
        }
    </style>
</head>
<body>

    @foreach($rapors as $raporHtml)
        <div class="page">
            {!! $raporHtml !!}
        </div>
    @endforeach

    <script>
        // Jika tidak mode download (hanya preview HTML), otomatis buka dialog print
        @if(!request()->has('download'))
            window.print();
        @endif
    </script>
</body>
</html>