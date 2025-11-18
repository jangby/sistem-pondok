<!DOCTYPE html>
<html>
<head>
    <title>Data Asrama</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .section { margin-bottom: 30px; page-break-inside: avoid; }
        .asrama-title { font-size: 14pt; font-weight: bold; background-color: #eee; padding: 5px; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; font-size: 10pt; }
        th, td { border: 1px solid #444; padding: 4px; }
        th { background-color: #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h2>DATA ASRAMA & PENGHUNI</h2>
        <p>Dicetak pada: {{ now()->format('d M Y') }}</p>
    </div>

    {{-- PUTRA --}}
    <h3 style="text-align:center; text-decoration:underline;">ASRAMA PUTRA</h3>
    @foreach($asramaPutra as $asrama)
        <div class="section">
            <div class="asrama-title">{{ $asrama->nama_asrama }} ({{ $asrama->komplek }}) - Ketua: {{ $asrama->ketua_asrama }}</div>
            <table>
                <thead>
                    <tr><th width="30">No</th><th>NIS</th><th>Nama Santri</th><th>Kelas</th></tr>
                </thead>
                <tbody>
                    @foreach($asrama->penghuni as $i => $s)
                        <tr>
                            <td align="center">{{ $i + 1 }}</td>
                            <td align="center">{{ $s->nis }}</td>
                            <td>{{ $s->full_name }}</td>
                            <td align="center">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                        </tr>
                    @endforeach
                    @if($asrama->penghuni->isEmpty())
                        <tr><td colspan="4" align="center">Belum ada penghuni.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endforeach

    <div style="page-break-after: always;"></div>

    {{-- PUTRI --}}
    <h3 style="text-align:center; text-decoration:underline;">ASRAMA PUTRI</h3>
    @foreach($asramaPutri as $asrama)
        <div class="section">
            <div class="asrama-title">{{ $asrama->nama_asrama }} ({{ $asrama->komplek }}) - Ketua: {{ $asrama->ketua_asrama }}</div>
            <table>
                <thead>
                    <tr><th width="30">No</th><th>NIS</th><th>Nama Santri</th><th>Kelas</th></tr>
                </thead>
                <tbody>
                    @foreach($asrama->penghuni as $i => $s)
                        <tr>
                            <td align="center">{{ $i + 1 }}</td>
                            <td align="center">{{ $s->nis }}</td>
                            <td>{{ $s->full_name }}</td>
                            <td align="center">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</body>
</html>