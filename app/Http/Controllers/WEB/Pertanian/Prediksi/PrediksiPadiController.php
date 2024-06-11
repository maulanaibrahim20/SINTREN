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
        return view('pertanian.pages.prediksi.padi.index', $content);
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
            $samples[] = [(int)$year];
            $targets[] = $total_nilai;
            $labels[] = $year;
        }

        $regression = new LeastSquares();
        $regression->train($samples, $targets);

        $predictions = [];
        $prevValue = null;
        for ($year = $dariTahun; $year <= 2030; $year++) {
            $predictedValue = $regression->predict([$year]);

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
        }

        $detailedPredictions = [];
        foreach ($predictions as $prediction) {
            if ($prediction['change_from_previous_year'] === null) {
                $description = "Pada tahun {$prediction['year']} diprediksi mendapatkan nilai " . number_format($prediction['predicted_value'], 2) . ".";
            } else {
                $description = "Pada tahun {$prediction['year']} diprediksi mendapatkan nilai "
                    . number_format($prediction['predicted_value'], 2) . " dengan perubahan sebesar " . number_format($prediction['change_from_previous_year'], 2) . " dari tahun sebelumnya.";
            }
            $detailedPredictions[] = $description;
        }

        $totalError = 0;
        $totalData = $sampaiTahun - $dariTahun + 1;
        foreach ($predictions as $key => $prediction) {
            if ($key < count($actualData)) {
                $tahun = $prediction['year'];
                $nilaiPrediksi = $prediction['predicted_value'];
                $error = abs(($targets[$key] - $nilaiPrediksi) / $targets[$key]) * 100;
                $totalError += $error;

                // Simpan nilai aktual ke prediksi
                $predictions[$key]['nilai_aktual'] = $targets[$key];
                $predictions[$key]['error'] = $error;
            }
        }

        $hasilMape = $totalError / $totalData;
        $tanpaRound = $hasilMape;
        $mape = round($hasilMape);

        // Simpan ke database
        foreach ($predictions as $prediction) {
            Prediksi::create([
                'tipe_data' => $tipeData,
                'tahun' => $prediction['year'],
                'nilai_prediksi' => $prediction['predicted_value'],
                'perubahan_dari_tahun_sebelumnya' => $prediction['change_from_previous_year'],
                'nilai_aktual' => $prediction['nilai_aktual'] ?? null,
                'error' => $prediction['error'] ?? null,
                'mape' => $mape
            ]);
        }

        return view('pertanian.pages.prediksi.padi.view', [
            'tipeData' => $tipeDataDescription,
            'labels' => $labels,
            'actualData' => array_values($actualData),
            'predictedData' => array_column($predictions, 'predicted_value'),
            'detailedPredictions' => $detailedPredictions,
            'tanpaRound' => $tanpaRound,
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
