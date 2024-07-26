<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportLaporanPangan implements FromCollection, WithHeadings
{
    protected $dataGroupedBySubjenis;

    public function __construct($dataGroupedBySubjenis)
    {
        $this->dataGroupedBySubjenis = $dataGroupedBySubjenis;
    }

    public function collection()
    {
        $collection = collect();

        foreach ($this->dataGroupedBySubjenis as $subjenis) {
            $collection->push([
                'No' => $collection->count() + 1, // Mengatur nomor urut
                'Jenis Pangan' => $subjenis['jenis_pangan_name'] ?? 'Jenis Pangan Tidak Ditemukan',
                'Nama Pangan' => $subjenis['name'] ?? 'Nama Pangan Tidak Ditemukan',
                'Tanggal' => $subjenis['latest_date'] ?? '',
                'Jumlah Stok' => $subjenis['total_stok'] ?? 0,
                'Harga Rata-rata' => $subjenis['avg_harga'] ?? 0,
            ]);
        }

        return $collection;
    }

    public function headings(): array
    {
        return [
            'No',
            'Jenis Pangan',
            'Nama Pangan',
            'Tanggal',
            'Jumlah Stok',
            'Harga Rata-rata',
        ];
    }
}




