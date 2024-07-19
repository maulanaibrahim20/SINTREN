<?php

namespace App\Exports;

use App\Models\Pangan\LaporanPangan;
use DateTime;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportLaporanPangan implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $pasarId;
    protected $subjenisPanganId;
    protected $defaultSubjenisPanganId;

    public function __construct($pasarId = null, $subjenisPanganId = null, $defaultSubjenisPanganId = 1)
    {
        $this->pasarId = $pasarId;
        $this->subjenisPanganId = $subjenisPanganId;
        $this->defaultSubjenisPanganId = $defaultSubjenisPanganId;
    }

    public function collection()
    {
        $today = now()->toDateString();

        $query = LaporanPangan::with(['pasar', 'jenis_pangan', 'subjenis_pangan'])
            ->where('date', $today)
            ->where('status', 1);

        if ($this->pasarId) {
            $query->where('pasar_id', $this->pasarId);
        }

        if ($this->subjenisPanganId) {
            $query->where('subjenis_pangan_id', $this->subjenisPanganId);
        } else {
            $query->where('subjenis_pangan_id', $this->defaultSubjenisPanganId);
        }

        $laporanPangan = $query->get();

        return collect($laporanPangan);
    }

    public function headings(): array
    {
        return [
            'No',
            'Status',
            'Pasar',
            'Jenis Pangan',
            'Nama Pangan',
            'Tanggal',
            'Stok',
            'Harga',
        ];
    }

    public function map($laporan): array
    {
        $status = $laporan->status ? 'Terkirim' : 'Belum Terkirim';

        return [
            $laporan->id,
            $status,
            $laporan->pasar ? $laporan->pasar->name : 'Pasar Tidak Ditemukan',
            $laporan->jenis_pangan ? $laporan->jenis_pangan->name : 'Jenis Pangan Tidak Ditemukan',
            $laporan->subjenis_pangan ? $laporan->subjenis_pangan->name : 'Subjenis Pangan Tidak Ditemukan',
            $laporan->date,
            $laporan->stok,
            $laporan->harga,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $event->sheet->getDelegate()->getHighestRow();

                // Calculate total and average
                $totalStok = '=SUM(G2:G' . $lastRow . ')';
                $totalHarga = '=SUM(H2:H' . $lastRow . ')';
                $averageHarga = '=AVERAGE(H2:H' . $lastRow . ')';

                // Set total and average in the Excel sheet
                $event->sheet->append([
                    ['', '', '', '', '', 'Total', $totalStok, $totalHarga],
                    ['', '', '', '', '', 'Rata-rata', '', $averageHarga],
                ]);
            },
        ];
    }
}
