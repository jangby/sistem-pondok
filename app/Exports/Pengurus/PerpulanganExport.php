<?php

namespace App\Exports\Pengurus;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PerpulanganExport implements WithMultipleSheets
{
    protected $eventId;
    protected $status;

    public function __construct($eventId, $status)
    {
        $this->eventId = $eventId;
        $this->status = $status;
    }

    public function sheets(): array
    {
        // PERBAIKAN: Gunakan 'Laki-laki' dan 'Perempuan' sesuai database
        return [
            new PerpulanganSheet($this->eventId, $this->status, 'Laki-laki', 'Santri Putra'),
            new PerpulanganSheet($this->eventId, $this->status, 'Perempuan', 'Santri Putri'),
        ];
    }
}