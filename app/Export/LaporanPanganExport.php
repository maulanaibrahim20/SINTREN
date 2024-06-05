<?php

namespace App\Exports;

use App\Models\Pangan\LaporanPangan;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanPanganExport implements FromCollection
{
    protected $datapangan;

    public function __construct($datapangan)
    {
        $this->datapangan = $datapangan;
    }

    public function collection()
    {
        return $this->datapangan;
    }
}
