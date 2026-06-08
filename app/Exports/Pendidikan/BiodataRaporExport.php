<?php

namespace App\Exports\Pendidikan;

use App\Models\Mustawa;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BiodataRaporExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [];

        // Ambil semua data Kelas (Mustawa)
        $mustawas = Mustawa::all();

        foreach ($mustawas as $mustawa) {
            // Buat Sheet untuk Putra (Laki-laki)
            // Parameter: ID Kelas, Nama Kelas (menggunakan ->nama), Jenis Kelamin DB, Label Sheet
            $sheets[] = new BiodataRaporSheet($mustawa->id, $mustawa->nama, 'Laki-laki', 'Putra');
            
            // Buat Sheet untuk Putri (Perempuan)
            $sheets[] = new BiodataRaporSheet($mustawa->id, $mustawa->nama, 'Perempuan', 'Putri');
        }

        return $sheets;
    }
}