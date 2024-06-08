<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\PrediksiSp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Phpml\Regression\LeastSquares;

class AdminPadiController extends Controller
{
    public function showAllByKecamatan($id)
    {
        try {
            if ($id == "dinas") {
                $laporanPadi = LaporanPadi::with(['desa', 'pengairan', 'padi', 'verify'])->get();
            } else {
                $laporanPadi = LaporanPadi::where('kecamatan_id', $id)->with(['desa', 'pengairan', 'padi', 'verify'])->get();
            }


            if ($laporanPadi->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kosong.',
                    'data' => null
                ], 201);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data',
                'data' => $laporanPadi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan ketika mendapatkan data: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    // public function menghitungRegresi(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'tipeData' => 'required|in:tanam,panen,puso/rusak',
    //         'dariTahun' => 'required|integer|min:2010|max:2023',
    //         'sampaiTahun' => 'required|integer|min:2010|max:2023|gte:dariTahun',
    //     ]);

    //     $tipeData = $request->input('tipeData');
    //     $dariTahun = $request->input('dariTahun');
    //     $sampaiTahun = $request->input('sampaiTahun');

    //     $tipeDataDescriptions = [
    //         'tanam' => 'Data Tanam',
    //         'panen' => 'Data Panen',
    //         'puso/rusak' => 'Data Puso/Rusak'
    //     ];
    //     $tipeDataDescription = $tipeDataDescriptions[$tipeData] ?? 'Jenis Data Tidak Diketahui';

    //     $data = $this->getTotalPerYear($tipeData, $dariTahun, $sampaiTahun);

    //     $samples = [];
    //     $targets = [];
    //     $labels = [];

    //     foreach ($data as $year => $total_nilai) {
    //         $samples[] = [(int)$year];
    //         $targets[] = $total_nilai;
    //         $labels[] = $year;
    //     }

    //     $regression = new LeastSquares();
    //     $regression->train($samples, $targets);

    //     $predictions = [];
    //     $prevValue = null;

    //     for ($year = $dariTahun; $year <= $sampaiTahun + 1; $year++) {
    //         $predictedValue = $regression->predict([$year]);

    //         if ($year > $sampaiTahun) {
    //             $samples[] = [$year];
    //             $targets[] = $predictedValue;
    //             $labels[] = $year;
    //             $regression->train($samples, $targets);
    //         }

    //         if ($prevValue !== null) {
    //             $change = $predictedValue - $prevValue;
    //             $predictions[] = [
    //                 'year' => $year,
    //                 'predicted_value' => $predictedValue,
    //                 'change_from_previous_year' => $change
    //             ];
    //         } else {
    //             $predictions[] = [
    //                 'year' => $year,
    //                 'predicted_value' => $predictedValue,
    //                 'change_from_previous_year' => null
    //             ];
    //         }
    //         $prevValue = $predictedValue;
    //     }

    //     $detailedPredictions = [];
    //     foreach ($predictions as $prediction) {
    //         if ($prediction['change_from_previous_year'] === null) {
    //             $description = "Pada tahun {$prediction['year']} diprediksi mendapatkan nilai " . number_format($prediction['predicted_value'], 2) . ".";
    //         } else {
    //             $description = "Pada tahun {$prediction['year']} diprediksi mendapatkan nilai "
    //                 . number_format($prediction['predicted_value'], 2) . " dengan perubahan sebesar " . number_format($prediction['change_from_previous_year'], 2) . " dari tahun sebelumnya.";
    //         }
    //         $detailedPredictions[] = $description;
    //     }

    //     $dataPrediksi = [
    //         'tipeData' => $tipeDataDescription,
    //         'labels' => $labels,
    //         'targets' => $targets,
    //         'detailedPredictions' => $detailedPredictions
    //     ];

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Berhasil mendapatkan data',
    //         'data' => $dataPrediksi
    //     ], 200);
    // }

    // private function getTotalPerYear($tipeData, $dariTahun, $sampaiTahun)
    // {
    //     $result = DB::table('laporan_padis')
    //         ->select(DB::raw('YEAR(date) as year, SUM(nilai) as total_nilai'))
    //         ->where('tipe_data', $tipeData)
    //         ->whereBetween(DB::raw('YEAR(date)'), [$dariTahun, $sampaiTahun])
    //         ->groupBy(DB::raw('YEAR(date)'))
    //         ->get();

    //     return $result->pluck('total_nilai', 'year')->all();
    // }

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

        $data = [
            'labels' => $labels,
            'actualData' => array_values($actualData),
            'predictedData' => array_column($predictions, 'predicted_value'),
            'mape' => $mape
        ];
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendapatkan data',
            'data' => $data
        ], 200);
    }
}
