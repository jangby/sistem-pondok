<?php

namespace App\Exports\Pengurus;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SantriTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        // Data Contoh (Dummy) agar user paham format pengisian
        return [
            [
                '123456',           // NIS
                '0012345678',       // NISN
                '3201012005100001', // NIK (16 Digit)
                '3201012005100002', // No KK
                '2024',             // Tahun Masuk
                'Ahmad Santri',     // Nama Lengkap
                'Laki-laki',        // Jenis Kelamin (Laki-laki/Perempuan)
                'Jakarta',          // Tempat Lahir
                '2010-05-20',       // Tanggal Lahir (YYYY-MM-DD)
                '1',                // Anak Ke
                '3',                // Jumlah Saudara
                'O',                // Gol Darah
                'Tidak ada',        // Riwayat Penyakit
                '10A',              // Nama Kelas (Wajib sama dengan data Kelas)
                'Asrama Al-Fatih',  // Nama Asrama (Opsional)
                'Jl. Merdeka No 1', // Alamat
                '001', '002',       // RT/RW
                'Sukamaju',         // Desa
                'Cilandak',         // Kecamatan
                '12430',            // Kode Pos
                
                // DATA AYAH
                'Budi Ayah',        // Nama Ayah
                '3201234567890001', // NIK Ayah
                '1980',             // Thn Lahir Ayah
                'S1',               // Pendidikan Ayah
                'PNS',              // Pekerjaan Ayah
                '3 - 5 Juta',       // Penghasilan Ayah
                '081234567890',     // No HP Ayah (WA)

                // DATA IBU
                'Siti Ibu',         // Nama Ibu
                '3201234567890002', // NIK Ibu
                '1982',             // Thn Lahir Ibu
                'D3',               // Pendidikan Ibu
                'Ibu Rumah Tangga', // Pekerjaan Ibu
                '< 1 Juta',         // Penghasilan Ibu
                '081234567891',     // No HP Ibu (WA)
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'NIS', 'NISN', 'NIK', 'No KK', 
            'Tahun Masuk', 'Nama Lengkap', 'Jenis Kelamin', 
            'Tempat Lahir', 'Tanggal Lahir (YYYY-MM-DD)', 
            'Anak Ke', 'Jumlah Saudara', 'Golongan Darah', 'Riwayat Penyakit',
            'Nama Kelas', 'Nama Asrama',
            'Alamat', 'RT', 'RW', 'Desa', 'Kecamatan', 'Kode Pos',
            'Nama Ayah', 'NIK Ayah', 'Thn Lahir Ayah', 'Pendidikan Ayah', 'Pekerjaan Ayah', 'Penghasilan Ayah', 'No HP Ayah',
            'Nama Ibu', 'NIK Ibu', 'Thn Lahir Ibu', 'Pendidikan Ibu', 'Pekerjaan Ibu', 'Penghasilan Ibu', 'No HP Ibu'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling Header agar terlihat jelas
        $sheet->getStyle('A1:AI1')->getFont()->setBold(true);
        $sheet->getStyle('A1:AI1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD9D9D9'); // Abu-abu muda

        // Border untuk semua sel yang ada datanya
        $sheet->getStyle('A1:AI2')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        return [];
    }
}