<?php

namespace App\Http\Controllers\WEB\Pertanian\Prediksi;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\LuasLahanWilayah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    // public function predict(Request $request)
    // {
    //     $tipeData = $request->input('tipeData');
    //     $dariBulan = $request->input('dariBulan');
    //     $dariTahun = $request->input('dariTahun');
    //     $sampaiBulan = $request->input('sampaiBulan');
    //     $sampaiTahun = $request->input('sampaiTahun');

    //     $bulanKeAngka = [
    //         'januari' => '01', 'februari' => '02', 'maret' => '03', 'april' => '04',
    //         'mei' => '05', 'juni' => '06', 'juli' => '07', 'agustus' => '08',
    //         'september' => '09', 'oktober' => '10', 'november' => '11', 'desember' => '12'
    //     ];

    //     $dariBulan = $bulanKeAngka[strtolower($dariBulan)];
    //     $sampaiBulan = $bulanKeAngka[strtolower($sampaiBulan)];

    //     $dariTanggal = Carbon::create($dariTahun, $dariBulan, 1)->startOfMonth()->toDateString();
    //     $sampaiTanggal = Carbon::create($sampaiTahun, $sampaiBulan, 1)->endOfMonth()->toDateString();

    //     $dataTanam = LaporanPadi::where('tipe_data', $tipeData)
    //         ->where('date', '>=', $dariTanggal)
    //         ->where('date', '<=', $sampaiTanggal)
    //         ->get();

    //     if ($dataTanam->isEmpty()) {
    //         return response()->json([
    //             'error' => 'Data tanam tidak ditemukan untuk rentang waktu yang diberikan'
    //         ], 404);
    //     }

    //     $desaId = $dataTanam->pluck('desa_id');

    //     $luasLahan = $this->luasLahanWilayah::whereIn('desa_id', $desaId)->get();

    //     if (!$luasLahan) {
    //         return response()->json([
    //             'error' => 'Data luas lahan tidak ditemukan untuk desa_id yang diberikan'
    //         ], 404);
    //     }

    //     $samples = [];
    //     $targets = [];
    //     $labels = [];

    //     foreach ($dataTanam as $entry) {
    //         $bulan = date('n', strtotime($entry->date));
    //         $tahun = date('Y', strtotime($entry->date));
    //         $bulanNama = date('F', strtotime($entry->date)); // Nama bulan

    //         if ($entry->jenis_lahan == 'sawah') {
    //             $samples[] = [$bulan, $tahun];
    //             $targets[] = $entry->nilai;
    //             $labels[] = $bulanNama . ' ' . $tahun; // Menyimpan label bulan dan tahun
    //         }
    //     }

    //     if (empty($samples) || empty($targets)) {
    //         return response()->json([
    //             'error' => 'Data tanam tidak cukup untuk melakukan prediksi'
    //         ], 400);
    //     }

    //     $regression = new LeastSquares();

    //     try {
    //         $regression->train($samples, $targets);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'Terjadi kesalahan saat melatih model: ' . $e->getMessage()
    //         ], 500);
    //     }

    //     $predictedValues = [];
    //     $predictedLabels = [];
    //     $currentDate = Carbon::create($dariTahun, $dariBulan, 1);

    //     while ($currentDate->lessThanOrEqualTo(Carbon::create($sampaiTahun, $sampaiBulan, 1))) {
    //         $bulanPrediksi = $currentDate->month;
    //         $tahunPrediksi = $currentDate->year;
    //         $predictedValues[] = $regression->predict([$bulanPrediksi, $tahunPrediksi]);
    //         $predictedLabels[] = $currentDate->format('F Y'); // Menyimpan label yang benar
    //         $currentDate->addMonth();
    //     }

    //     return view('pertanian.pages.prediksi.padi.show', compact('predictedLabels', 'predictedValues'));
    // }

    // public function predict(Request $request)
    // {
    //     $tipeData = $request->input('tipeData');
    //     $dariBulan = $request->input('dariBulan');
    //     $dariTahun = $request->input('dariTahun');
    //     $sampaiBulan = $request->input('sampaiBulan');
    //     $sampaiTahun = $request->input('sampaiTahun');

    //     $bulanKeAngka = [
    //         'januari' => '01', 'februari' => '02', 'maret' => '03', 'april' => '04',
    //         'mei' => '05', 'juni' => '06', 'juli' => '07', 'agustus' => '08',
    //         'september' => '09', 'oktober' => '10', 'november' => '11', 'desember' => '12'
    //     ];

    //     $dariBulan = $bulanKeAngka[strtolower($dariBulan)];
    //     $sampaiBulan = $bulanKeAngka[strtolower($sampaiBulan)];

    //     $dariTanggal = Carbon::create($dariTahun, $dariBulan, 1)->startOfMonth()->toDateString();
    //     $sampaiTanggal = Carbon::create($sampaiTahun, $sampaiBulan, 1)->endOfMonth()->toDateString();

    //     $dataTanam = LaporanPadi::where('tipe_data', $tipeData)
    //         ->where('date', '>=', $dariTanggal)
    //         ->where('date', '<=', $sampaiTanggal)
    //         ->get();

    //     if ($dataTanam->isEmpty()) {
    //         return response()->json([
    //             'error' => 'Data tanam tidak ditemukan untuk rentang waktu yang diberikan'
    //         ], 404);
    //     }

    //     $groupedData = $dataTanam->groupBy(function ($date) {
    //         return Carbon::parse($date->date)->format('Y-m');
    //     });

    //     $samples = [];
    //     $targets = [];
    //     $labels = [];

    //     foreach ($groupedData as $month => $data) {
    //         $averageValue = $data->avg('nilai');
    //         $bulan = Carbon::parse($month)->month;
    //         $tahun = Carbon::parse($month)->year;

    //         $samples[] = [$bulan, $tahun];
    //         $targets[] = $averageValue;
    //         $labels[] = Carbon::parse($month)->format('F Y');
    //     }

    //     if (empty($samples) || empty($targets)) {
    //         return response()->json([
    //             'error' => 'Data tanam tidak cukup untuk melakukan prediksi'
    //         ], 400);
    //     }

    //     $regression = new LeastSquares();

    //     try {
    //         $regression->train($samples, $targets);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'Terjadi kesalahan saat melatih model: ' . $e->getMessage()
    //         ], 500);
    //     }

    //     $bulanPrediksi = Carbon::create($sampaiTahun, $sampaiBulan, 1)->addMonth()->month;
    //     $tahunPrediksi = Carbon::create($sampaiTahun, $sampaiBulan, 1)->addMonth()->year;

    //     $predictedValue = $regression->predict([$bulanPrediksi, $tahunPrediksi]);

    //     $labels[] = Carbon::create($tahunPrediksi, $bulanPrediksi, 1)->format('F Y');
    //     $targets[] = $predictedValue;

    //     return view('pertanian.pages.prediksi.padi.show', compact(
    //         'labels',
    //         'targets',
    //         'tipeData',
    //         'dariBulan',
    //         'dariTahun',
    //         'sampaiBulan',
    //         'sampaiTahun',
    //         'bulanKeAngka',
    //         'dariTanggal',
    //         'sampaiTanggal',
    //         'groupedData',
    //         'samples',
    //         'bulanPrediksi',
    //         'tahunPrediksi',
    //         'predictedValue'
    //     ));
    // }

    // public function menghitungRegresi(Request $request)
    // {
    //     $penjualanY = ['15', '17', '20', '24', '29', '35', '40', '45', '52', '55', '60', '67', '73', '80'];
    //     $tahunX = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14'];

    //     $jumlahElemen = count($penjualanY);
    //     $sigmaXY = 0;

    //     for ($i = 0; $i < $jumlahElemen; $i++) {
    //         $sigmaXY += $penjualanY[$i] * $tahunX[$i];
    //     }

    //     echo "sigmaXY = $sigmaXY";
    //     $simgaXxY = $sigmaXY;



    //     $sigmaY = '612';
    //     $sigmaX = '21';

    //     // $sigmaXY = $y * $x;
    //     // $sigmaXpangkat2 =
    // }

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
        $actualData = $data; // Store actual data for comparison

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
            }
        }

        $hasilMape = $totalError / $totalData;
        $tanpaRound = $hasilMape;
        $mape = round($hasilMape);

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
