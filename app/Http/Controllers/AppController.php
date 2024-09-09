<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Phpml\Regression\LeastSquares;
use App\Models\Pangan\LaporanPangan;
use App\Models\Pangan\SubjenisPangan;


class AppController extends Controller
{
    public function index()
    {
        $dariTahun = 2010;
        $sampaiTahun = 2021;

        $laporanPadi = DB::table('laporan_padis')
            ->join('verify_padis', 'laporan_padis.id', '=', 'verify_padis.laporan_id') // Menggunakan join untuk menghubungkan tabel verify
            ->selectRaw('YEAR(laporan_padis.date) AS tahun')
            ->selectRaw('SUM(CASE WHEN laporan_padis.tipe_data = "panen" THEN laporan_padis.nilai ELSE 0 END) AS total_panen')
            ->whereYear('laporan_padis.date', '>=', $dariTahun)
            ->whereYear('laporan_padis.date', '<=', $sampaiTahun)
            ->where('verify_padis.status', 'terima') // Menambahkan kondisi untuk memfilter berdasarkan status verify
            ->groupBy('tahun')
            ->orderBy('tahun', 'ASC')
            ->get();

        $hasilPerTahun = [];

        foreach ($laporanPadi as $laporan) {
            $tahun = $laporan->tahun;

            if (!isset($hasilPerTahun[$tahun])) {
                $hasilPerTahun[$tahun] = 0;
            }
            $hasilPerTahun[$tahun] += $laporan->total_panen;
        }
        $actualData = $hasilPerTahun;

        $fitur = [];
        $target = [];
        $labels = [];
        foreach ($hasilPerTahun as $tahun => $hasil) {
            $fitur[] = [(int) $tahun];
            $target[] = $hasil;
            $labels[] = $tahun;
        }

        $regression = new LeastSquares();
        // $regression->train($fitur, $target);

        $hasilPrediksi = [];
        $prevValue = null;
        for ($tahun = $dariTahun; $tahun <= 2030; $tahun++) {
            $prediksi = round($regression->predict([$tahun])); // Membulatkan hasil prediksi
            $hasilPrediksi[$tahun] = $prediksi;

            if ($tahun > $sampaiTahun) {
                $samples[] = [$tahun];
                $targets[] = $hasilPrediksi[$tahun];
                $labels[] = $tahun;
                // $regression->train($samples, $targets);
            }

            $change = null;
            if ($prevValue !== null) {
                $change = $hasilPrediksi[$tahun] - $prevValue;
            }

            $predictions[] = [
                'year' => $tahun,
                'predicted_value' => $hasilPrediksi[$tahun],
                'change_from_previous_year' => $change
            ];

            $prevValue = $hasilPrediksi[$tahun];
        }

        $totalError = 0;
        $n = 0;
        foreach ($actualData as $tahun => $aktual) {
            if (isset($hasilPrediksi[$tahun])) {
                $prediksi = $hasilPrediksi[$tahun];
                $error = abs(($aktual - $prediksi) / $aktual);
                $totalError += $error;
                $n++;
                $predictions[$tahun]['error'] = $error;
            }
        }
        // $mape = round(($totalError / $n) * 100, 2);

        //pangan

        // Bagian laporan pangan
        $laporanpangan = LaporanPangan::select(
            DB::raw('YEAR(date) as year'),
            'subjenis_pangan_id',
            DB::raw('AVG(harga) as avg_harga')
        )
        ->where('status', '1')
        ->groupBy(DB::raw('YEAR(date)'), 'subjenis_pangan_id')
        ->get();

        $subjenisPangan = SubjenisPangan::orderBy('name', 'asc')->get();

        $dataGroupedByYear = $laporanpangan->groupBy('year');

        $years = $dataGroupedByYear->keys()->sort()->values();

        $datasets = [];
        foreach ($subjenisPangan as $subjenis) {
            $datasets[] = [
                'label' => $subjenis->name,
                'data' => $years->map(function ($year) use ($subjenis, $dataGroupedByYear) {
                    $yearData = $dataGroupedByYear->get($year, collect());
                    $avgHarga = $yearData->where('subjenis_pangan_id', $subjenis->id)->pluck('avg_harga')->first();
                    return $avgHarga ? round($avgHarga, 2) : 0;
                })->values(),
                'borderColor' => '#' . dechex(rand(0x000000, 0xFFFFFF)),
                'backgroundColor' => 'rgba(0, 0, 0, 0)',
                'borderWidth' => 2
            ];
        }

        return view('landing', [
            'labels' => $labels,
            'actualData' => array_values($actualData),
            'predictedData' => array_column($predictions, 'predicted_value'),
            // 'mape' => $mape,
            'predictions' => $predictions,
            'years' => $years,
            'datasets' => $datasets
        ]);
    }

    //     return view('landing', [
    //         'labels' => $labels,
    //         'actualData' => array_values($actualData),
    //         'predictedData' => array_column($predictions, 'predicted_value'),
    //         // 'mape' => $mape,
    //         'predictions' => $predictions
    //     ]);
    // }



}
