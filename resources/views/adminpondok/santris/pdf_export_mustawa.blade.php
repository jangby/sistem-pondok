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

    @foreach($dataEkspor as $data)
        <div class="header">
            <h2>DATA BIODATA SANTRI</h2>
            <h3>KELAS PESANTREN: <b>{{ $data['kelas'] }}</b> - KELOMPOK: <b>{{ $data['kategori'] }}</b></h3>
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
                @foreach($data['santris'] as $index => $santri)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $santri->nis ?? '-' }}</td>
                    <td class="text-center">-</td> <td class="text-center">{{ $santri->nik ?? '-' }}</td>
                    <td>{{ $santri->full_name }}</td>
                    <td>
                        {{ $santri->tempat_lahir ?? '-' }}, <br>
                        {{ $santri->tanggal_lahir ? \Carbon\Carbon::parse($santri->tanggal_lahir)->format('d-m-Y') : '-' }}
                    </td>
                    <td class="text-center">{{ $santri->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}</td>
                    <td>{{ $santri->detail_emis ?? '-' }}</td>
                    <td>
                        {{ $santri->nama_ayah ?? ($santri->orangTua->nama_ayah ?? '-') }}
                    </td>
                    <td class="text-center">{{ $santri->orangTua->no_hp ?? '-' }}</td>
                    <td>
                        {{ $santri->alamat ?? '-' }} 
                        @if($santri->desa) Ds. {{ $santri->desa }} @endif
                        @if($santri->kecamatan) Kec. {{ $santri->kecamatan }} @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pisahkan halaman untuk kelas selanjutnya --}}
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>