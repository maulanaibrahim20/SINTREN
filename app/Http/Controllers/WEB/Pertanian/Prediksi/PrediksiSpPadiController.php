<?php

namespace App\Http\Controllers\WEB\Pertanian\Prediksi;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\PrediksiSp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Phpml\Regression\LeastSquares;

class PrediksiSpPadiController extends Controller
{
    protected $laporanPadi;

    public function __construct(LaporanPadi $laporanPadi)
    {
        $this->laporanPadi = $laporanPadi;
    }

    public function indexSp()
    {
        $content = [
            'title' => 'Prediksi Padi',
        ];
        return view('pertanian.pages.prediksi.padiSp.index', $content);
    }

    public function menghitungRegresiSP(Request $request)
    {
        $request->validate([
            'dariTahun' => 'required|integer|min:2010|max:2023',
            'sampaiTahun' => 'required|integer|min:2010|max:2023|gte:dariTahun',
        ]);

        $dariTahun = $request->input('dariTahun');
        $sampaiTahun = $request->input('sampaiTahun');

        $laporanPadi = DB::table('laporan_padis')
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') AS bulan")
            ->selectRaw("SUM(CASE WHEN tipe_data = 'panen' THEN nilai ELSE 0 END) AS total_panen")
            ->selectRaw("SUM(CASE WHEN tipe_data = 'tanam' THEN nilai ELSE 0 END) AS total_tanam")
            ->selectRaw("SUM(CASE WHEN tipe_data = 'puso/rusak' THEN nilai ELSE 0 END) AS total_puso_rusak")
            ->whereYear('date', '>=', $dariTahun)
            ->whereYear('date', '<=', $sampaiTahun)
            ->groupBy('bulan')
            ->orderBy('bulan', 'ASC')
            ->get();

        $hasilPerTahun = [];

        // Mengelompokkan hasil per tahun
        foreach ($laporanPadi as $laporan) {
            $tahun = substr($laporan->bulan, 0, 4);

            if (!isset($hasilPerTahun[$tahun])) {
                $hasilPerTahun[$tahun] = 0;
            }
            $hasilPerTahun[$tahun] += ($laporan->total_panen + $laporan->total_tanam - $laporan->total_puso_rusak);
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
            // for ($tahun = max(array_keys($hasilPerTahun)) + 1; $tahun <= 2030; $tahun++) {
            $hasilPrediksi[$tahun] = $regression->predict([$tahun]);

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
                $totalError += abs(($aktual - $prediksi) / $aktual);
                $n++;
            }
        }
        $mape = round(($totalError / $n) * 100, 2);


        foreach ($predictions as $prediction) {
            PrediksiSp::create([
                'tahun' => $prediction['year'],
                'nilai_prediksi' => $prediction['predicted_value'],
                'perubahan_dari_tahun_sebelumnya' => $prediction['change_from_previous_year'],
                'nilai_aktual' => isset($actualData[$prediction['year']]) ? $actualData[$prediction['year']] : null,
                'error' => abs($prediction['predicted_value'] - ($actualData[$prediction['year']] ?? 0)), // Perubahan ini
                'mape' => $mape
            ]);
        }

        return view('pertanian.pages.prediksi.padiSp.regresiSp', [
            'labels' => $labels,
            'actualData' => array_values($actualData),
            'predictedData' => array_column($predictions, 'predicted_value'),
            'mape' => $mape
        ]);
    }
}
