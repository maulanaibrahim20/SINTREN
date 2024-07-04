<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Phpml\Regression\LeastSquares;

class AppController extends Controller
{
    public function index()
    {
        $dariTahun = 2010;
        $sampaiTahun = 2021;

        $laporanPadi = DB::table('laporan_padis')
            ->selectRaw('YEAR(date) AS tahun')
            ->selectRaw('SUM(CASE WHEN tipe_data = "panen" THEN nilai ELSE 0 END) AS total_panen')
            ->whereYear('date', '>=', $dariTahun)
            ->whereYear('date', '<=', $sampaiTahun)
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
        $regression->train($fitur, $target);

        $hasilPrediksi = [];
        $prevValue = null;
        for ($tahun = $dariTahun; $tahun <= 2030; $tahun++) {
            $prediksi = round($regression->predict([$tahun])); // Membulatkan hasil prediksi
            $hasilPrediksi[$tahun] = $prediksi;

            if ($tahun > $sampaiTahun) {
                $samples[] = [$tahun];
                $targets[] = $hasilPrediksi[$tahun];
                $labels[] = $tahun;
                $regression->train($samples, $targets);
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
        $mape = round(($totalError / $n) * 100, 2);

        return view('landing', [
            'labels' => $labels,
            'actualData' => array_values($actualData),
            'predictedData' => array_column($predictions, 'predicted_value'),
            'mape' => $mape,
            'predictions' => $predictions
        ]);
    }
}
