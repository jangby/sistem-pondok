<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul ?? 'Kartu Ujian' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', serif;
            background-color: #fff;
        }
        
        .page {
            position: relative;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            page-break-after: always;
            overflow: hidden;
        }
        .page:last-child {
            page-break-after: auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table td, table th {
            border: 1px solid #000;
            padding: 2px;
        }
        /* CSS untuk Margin Template */
        @page {
            margin: {{ $template->margin_top ?? 5 }}mm {{ $template->margin_right ?? 5 }}mm {{ $template->margin_bottom ?? 5 }}mm {{ $template->margin_left ?? 5 }}mm;
        }
        
        /* Supaya Page Break TinyMCE jalan */
        .mce-pagebreak {
            page-break-before: always;
            display: block;
            height: 0;
            border: 0;
        }
    </style>
</head>
<body>
    @foreach($cards as $cardHtml)
        <div class="page">
            {!! $cardHtml !!}
        </div>
    @endforeach

    <script>
        @if(!request()->has('download'))
            window.print();
        @endif
    </script>
</body>
</html>