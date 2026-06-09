<?php

namespace App\Exports\Pendidikan;

use App\Models\Mustawa;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NilaiRaporExport implements WithMultipleSheets
{
    use Exportable;

    protected $jenis_ujian;
    protected $semester;

    // Menerima parameter dari Controller
    public function __construct($jenis_ujian, $semester)
    {
        $this->jenis_ujian = $jenis_ujian;
        $this->semester = $semester;
    }

    public function sheets(): array
    {
        $sheets = [];
        $mustawas = Mustawa::all();

        foreach ($mustawas as $mustawa) {
            // Lempar data ke masing-masing Sheet kelas
            $sheets[] = new NilaiRaporSheet($mustawa->id, $mustawa->nama, $this->jenis_ujian, $this->semester);
        }

        return $sheets;
    }
}