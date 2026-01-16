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
        return [
            new PerpulanganSheet($this->eventId, $this->status, 'L', 'Santri Putra'),
            new PerpulanganSheet($this->eventId, $this->status, 'P', 'Santri Putri'),
        ];
    }
}