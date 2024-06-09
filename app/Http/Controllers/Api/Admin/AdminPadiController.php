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

            $result = $laporanPadi->map(function ($item) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'desa_id' => $item->desa_id,
                    'desa_name' => $item->desa->name,
                    'kecamatan_id' => $item->kecamatan_id,
                    'jenis_lahan' => $item->jenis_lahan,
                    'id_jenis_padi' => $item->id_jenis_padi,
                    'padi_name' => $item->padi->name,
                    'jenis_bantuan' => $item->jenis_bantuan,
                    'id_jenis_pengairan' => $item->id_jenis_pengairan,
                    'pengairan_name' => $item->pengairan->name,
                    'tipe_data' => $item->tipe_data,
                    'nilai' => $item->nilai,
                    'date' => $item->date,
                    'status' => $item->verify->status,
                    'catatan' => $item->verify->catatan,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data',
                'data' => $result
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

    public function menghitungRegresiSP()
    {
        $earliestYear = DB::table('laporan_padis')->orderBy('date', 'ASC')->value(DB::raw('YEAR(date)'));
        $latestYear = DB::table('laporan_padis')->orderBy('date', 'DESC')->value(DB::raw('YEAR(date)'));

        $dariTahun = $earliestYear;
        $sampaiTahun = $latestYear;

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
        for ($tahun = $dariTahun; $tahun <= $sampaiTahun; $tahun++) {
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
