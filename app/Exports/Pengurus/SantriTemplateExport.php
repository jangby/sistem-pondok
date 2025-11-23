<?php

namespace App\Exports\Pengurus;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SantriTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function array(): array
    {
        // Data contoh (Dummy) agar user paham formatnya
        return [
            [
                '123456',           // NIS
                '2025',
                'Ahmad Santri',     // Nama Lengkap
                'Laki-laki',        // Jenis Kelamin (Laki-laki/Perempuan)
                'Jakarta',          // Tempat Lahir
                '2010-05-20',       // Tanggal Lahir (YYYY-MM-DD)
                '10A',              // Nama Kelas (Harus sesuai data kelas)
                'Budi Wali',        // Nama Wali (Untuk Akun Aplikasi)
                '081234567890',     // No HP Wali (PENTING: Unik untuk login)
                'Jl. Merdeka No 1', // Alamat
                '001', '002',       // RT/RW
                'Sukamaju',         // Desa
                'Cilandak',         // Kecamatan
                '12430',            // Kode Pos
                'Ali Ayah',         // Nama Ayah (EMIS)
                '3201234567890001', // NIK Ayah
                '1980',             // Thn Lahir Ayah
                'S1',               // Pendidikan Ayah
                'PNS',              // Pekerjaan Ayah
                '3 - 5 Juta',       // Penghasilan Ayah
                'Siti Ibu',         // Nama Ibu (EMIS)
                '3201234567890002', // NIK Ibu
                '1982',             // Thn Lahir Ibu
                'D3',               // Pendidikan Ibu
                'Ibu Rumah Tangga', // Pekerjaan Ibu
                '< 1 Juta',         // Penghasilan Ibu
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Tahun Masuk',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Nama Kelas',
            'Nama Wali (Akun App)',
            'No HP Wali (Wajib Unik)',
            'Alamat',
            'RT', 'RW',
            'Desa', 'Kecamatan', 'Kode Pos',
            'Nama Ayah', 'NIK Ayah', 'Thn Lahir Ayah', 'Pendidikan Ayah', 'Pekerjaan Ayah', 'Penghasilan Ayah',
            'Nama Ibu', 'NIK Ibu', 'Thn Lahir Ibu', 'Pendidikan Ibu', 'Pekerjaan Ibu', 'Penghasilan Ibu',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Baris header tebal
        ];
    }
}