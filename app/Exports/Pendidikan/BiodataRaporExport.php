<?php

namespace App\Exports\Pendidikan;

use App\Models\Mustawa;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BiodataRaporExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(private int $pondokId)
    {
    }

    public function sheets(): array
    {
        $sheets = [];

        $mustawas = Mustawa::where('pondok_id', $this->pondokId)->get();

        foreach ($mustawas as $mustawa) {
            // CUKUP SATU SHEET PER KELAS
            // Hanya mengirim parameter: ID Kelas dan Nama Kelas saja
            $sheets[] = new BiodataRaporSheet($mustawa->id, $mustawa->nama, $this->pondokId);
        }

        return $sheets;
    }
}