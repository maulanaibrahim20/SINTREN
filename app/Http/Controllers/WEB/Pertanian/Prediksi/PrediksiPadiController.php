<?php

namespace App\Http\Controllers\WEB\Pertanian\Prediksi;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\LuasLahanWilayah;
use App\Models\Prediksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Phpml\Dataset\ArrayDataset;
use Phpml\Regression\LeastSquares;

class PrediksiPadiController extends Controller
{
    protected $laporanPadi, $luasLahanWilayah;

    public function __construct(LaporanPadi $laporanPadi, LuasLahanWilayah $luasLahanWilayah)
    {
        $this->laporanPadi = $laporanPadi;
        $this->luasLahanWilayah = $luasLahanWilayah;
    }

    public function index()
    {
        $content = [
            'title' => 'Prediksi Padi',
        ];
        $data['laporanPadi'] = $this->laporanPadi::where('tipe_data', 'tanam')->get();

        $tahunTerkecil = $this->laporanPadi::where('tipe_data', 'tanam')->min(DB::raw('YEAR(date)'));
        $tahunTerbesar = $this->laporanPadi::where('tipe_data', 'tanam')->max(DB::raw('YEAR(date)'));

        $data['tahunTerkecil'] = $tahunTerkecil;
        $data['tahunTerbesar'] = $tahunTerbesar;

        return view('pertanian.pages.prediksi.padi.index', array_merge($content, $data));
    }


    public function menghitungRegresi(Request $request)
    {
        $request->validate([
            'tipeData' => 'required|in:tanam,panen,puso/rusak',
            'dariTahun' => 'required|integer|min:2010|max:2023',
            'sampaiTahun' => 'required|integer|min:2010|max:2023|gte:dariTahun',
        ]);

        $tipeData = $request->input('tipeData');
        $dariTahun = $request->input('dariTahun');
        $sampaiTahun = $request->input('sampaiTahun');

        $tipeDataDescriptions = [
            'tanam' => 'Data Tanam',
            'panen' => 'Data Panen',
            'puso/rusak' => 'Data Puso/Rusak'
        ];
        $tipeDataDescription = $tipeDataDescriptions[$tipeData] ?? 'Jenis Data Tidak Diketahui';

        $data = $this->getTotalPerYear($tipeData, $dariTahun, $sampaiTahun);
        $actualData = $data;

        $samples = [];
        $targets = [];
        $labels = [];

        foreach ($data as $year => $total_nilai) {
            $samples[] = [(int) $year];
            $targets[] = $total_nilai;
            $labels[] = $year;
        }

        $regression = new LeastSquares();
        $regression->train($samples, $targets);

        $predictions = [];
        $prevValue = null;
        $totalError = 0;
        $totalData = $sampaiTahun - $dariTahun + 1;

        for ($year = $dariTahun; $year <= 2030; $year++) {
            $predictedValue = round($regression->predict([$year]));

            if ($year > $sampaiTahun) {
                $samples[] = [$year];
                $targets[] = $predictedValue;
                $labels[] = $year;
                $regression->train($samples, $targets);
            }

            if ($prevValue !== null) {
                $change = $predictedValue - $prevValue;
                $predictions[] = [
                    'year' => $year,
                    'predicted_value' => $predictedValue,
                    'change_from_previous_year' => $change
                ];
            } else {
                $predictions[] = [
                    'year' => $year,
                    'predicted_value' => $predictedValue,
                    'change_from_previous_year' => null
                ];
            }
            $prevValue = $predictedValue;

            // Hitung MAPE
            if (isset($actualData[$year])) {
                $actualValue = $actualData[$year];
                $error = abs(($actualValue - $predictedValue) / $actualValue) * 100;
                $totalError += $error;
                $predictions[count($predictions) - 1]['nilai_aktual'] = $actualValue;
                $predictions[count($predictions) - 1]['error'] = $error;
            }
        }

        // Hitung MAPE rata-rata
        $hasilmape = $totalError / count($actualData);
        $mape = round($hasilmape, 2);

        return view('pertanian.pages.prediksi.padi.view', [
            'tipeData' => $tipeDataDescription,
            'labels' => $labels,
            'actualData' => array_values($actualData),
            'predictedData' => array_column($predictions, 'predicted_value'),
            'mape' => $mape
        ]);
    }




    private function getTotalPerYear($tipeData, $dariTahun, $sampaiTahun)
    {
        $result = DB::table('laporan_padis')
            ->select(DB::raw('YEAR(date) as year, SUM(nilai) as total_nilai'))
            ->where('tipe_data', $tipeData)
            ->whereBetween(DB::raw('YEAR(date)'), [$dariTahun, $sampaiTahun])
            ->groupBy(DB::raw('YEAR(date)'))
            ->get();

        return $result->pluck('total_nilai', 'year')->all();
    }
}
