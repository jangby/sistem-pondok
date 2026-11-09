<?php

namespace App\Exports\Pendidikan;

use App\Models\Mustawa;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RaporLengkapExport implements WithMultipleSheets
{
    use Exportable;

    protected int $pondokId;
    protected $jenis_ujian;
    protected $semester;

    public function __construct(int $pondokId, $jenis_ujian, $semester)
    {
        $this->pondokId = $pondokId;
        $this->jenis_ujian = $jenis_ujian;
        $this->semester = $semester;
    }

    public function sheets(): array
    {
        $sheets = [];
        $mustawas = Mustawa::where('pondok_id', $this->pondokId)->get();

        foreach ($mustawas as $mustawa) {
            // Membuat satu sheet per kelas dengan membawa parameter ujian
            $sheets[] = new RaporLengkapSheet($mustawa->id, $mustawa->nama, $this->jenis_ujian, $this->semester, $this->pondokId);
        }

        return $sheets;
    }
}