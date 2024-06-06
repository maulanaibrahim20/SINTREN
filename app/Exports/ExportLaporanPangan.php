<?php

namespace App\Exports;

use App\Models\Pangan\LaporanPangan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportLaporanPangan implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil data laporan pangan dari model
        $laporanPangan = LaporanPangan::all();

        // Inisialisasi variabel total
        $totalKebutuhan = 0;
        $totalKetersediaan = 0;
        $totalNeraca = 0;
        $totalHarga = 0;

        // Ubah data menjadi collection
        $collection = collect($laporanPangan)->map(function ($laporan) use (&$totalKebutuhan, &$totalKetersediaan, &$totalNeraca, &$totalHarga) {
            $status = $laporan->status ? 'Terkirim' : 'Belum Terkirim'; // Ubah kondisi status di sini

            // Tambahkan nilai total
            $totalKebutuhan += $laporan->kebutuhan;
            $totalKetersediaan += $laporan->ketersediaan;
            $totalNeraca += $laporan->neraca;
            $totalHarga += $laporan->harga;

            return [
                'No' => $laporan->id,
                'Status' => $status,
                'Pasar' => $laporan->pasar->name,
                'Nama Pangan' => $laporan->name,
                'Tanggal' => $laporan->date,
                'Kebutuhan (Ton)' => $laporan->kebutuhan,
                'Ketersediaan (Ton)' => $laporan->ketersediaan,
                'Neraca (Ton)' => $laporan->neraca,
                'Harga (Rp/Kg)' => $laporan->harga,
            ];
        });

        // Tambahkan baris total ke collection
        $collection->push([
            'No' => 'Total',
            'Status' => '',
            'Pasar' => '',
            'Nama Pangan' => '',
            'Tanggal' => '',
            'Kebutuhan (Ton)' => $totalKebutuhan,
            'Ketersediaan (Ton)' => $totalKetersediaan,
            'Neraca (Ton)' => $totalNeraca,
            'Harga (Rp/Kg)' => $totalHarga,
        ]);

        return $collection;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Status',
            'Pasar',
            'Nama Pangan',
            'Tanggal',
            'Kebutuhan (Ton)',
            'Ketersediaan (Ton)',
            'Neraca (Ton)',
            'Harga (Rp/Kg)',
        ];
    }
}
