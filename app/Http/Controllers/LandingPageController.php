<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LaporanPangan;
use App\Models\SubjenisPangan;

class LandingPageController extends Controller
{
    public function showChart()
    {
        // Mengambil data laporan pangan dan mengelompokkan berdasarkan tahun dan subjenis_pangan_id
        $laporanpangan = LaporanPangan::select(
            DB::raw('YEAR(date) as year'),
            'subjenis_pangan_id',
            DB::raw('AVG(harga) as avg_harga')
        )
        ->where('status', '1')
        ->groupBy(DB::raw('YEAR(date)'), 'subjenis_pangan_id')
        ->get();

        // Mengambil data SubjenisPangan beserta informasi terkait
        $subjenisPangan = SubjenisPangan::orderBy('name', 'asc')->get();

        // Mengelompokkan data berdasarkan tahun
        $dataGroupedByYear = $laporanpangan->groupBy('year');

        // Mendapatkan daftar tahun untuk label sumbu X
        $years = $dataGroupedByYear->keys()->sort()->values(); // Mengurutkan tahun secara ascending

        // Mempersiapkan dataset untuk Chart.js
        $datasets = [];
        foreach ($subjenisPangan as $subjenis) {
            $datasets[] = [
                'label' => $subjenis->name,
                'data' => $years->map(function ($year) use ($subjenis, $dataGroupedByYear) {
                    $yearData = $dataGroupedByYear->get($year);
                    // Jika tidak ada data untuk tahun ini, kembalikan 0
                    $avgHarga = $yearData->where('subjenis_pangan_id', $subjenis->id)->pluck('avg_harga')->first();
                    return $avgHarga ? round($avgHarga, 2) : 0; // Pembulatan untuk menampilkan dua desimal
                })->values(),
                'borderColor' => '#' . dechex(rand(0x000000, 0xFFFFFF)), // Warna acak untuk setiap subjenis
                'backgroundColor' => 'rgba(0, 0, 0, 0)',
                'borderWidth' => 2
            ];
        }

        return response()->json([
            'years' => $years,
            'datasets' => $datasets
        ]);
    }
}

