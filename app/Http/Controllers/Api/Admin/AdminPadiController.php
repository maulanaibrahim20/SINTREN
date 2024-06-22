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
                $laporanPadi = LaporanPadi::with(['desa', 'pengairan', 'padi', 'verify', 'kecamatan'])->get();
            } else {
                $laporanPadi = LaporanPadi::where('kecamatan_id', $id)->with(['desa', 'pengairan', 'padi', 'verify', 'kecamatan'])->get();
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
                    'desa_name' => $item->desa ? $item->desa->name : "",
                    'kecamatan_id' => $item->kecamatan_id,
                    'kecamatan_name' => $item->kecamatan ? $item->kecamatan->name : "",
                    'jenis_lahan' => $item->jenis_lahan,
                    'id_jenis_padi' => $item->id_jenis_padi,
                    'padi_name' => $item->padi ? $item->padi->name : "",
                    'jenis_bantuan' => $item->jenis_bantuan,
                    'id_jenis_pengairan' => $item->id_jenis_pengairan,
                    'pengairan_name' => $item->pengairan ? $item->pengairan->name : "",
                    'tipe_data' => $item->tipe_data,
                    'nilai' => $item->nilai,
                    'date' => $item->date,
                    'status' => $item->verify ? $item->verify->status : "",
                    'catatan' => $item->verify ? $item->verify->catatan : "",
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

    public function prediksi()
    {
        $dariTahun = DB::table('laporan_padis')->orderBy('date', 'ASC')->value(DB::raw('YEAR(date)'));
        $sampaiTahun = DB::table('laporan_padis')->orderBy('date', 'DESC')->value(DB::raw('YEAR(date)'));

        $laporanPadi = DB::table('laporan_padis')
            ->selectRaw('YEAR(date) AS tahun')
            ->selectRaw('SUM(CASE WHEN tipe_data = "panen" THEN nilai ELSE 0 END) AS total_panen')
            ->whereYear('date', '>=', $dariTahun)
            ->whereYear('date', '<=', $sampaiTahun)
            ->groupBy('tahun')
            ->orderBy('tahun', 'ASC')
            ->get();

        $hasilPerTahun = [];

        for ($tahun = $dariTahun; $tahun <= $sampaiTahun; $tahun++) {
            $hasilPerTahun[$tahun] = 0;
        }

        foreach ($laporanPadi as $laporan) {
            $tahun = $laporan->tahun;
            $hasilPerTahun[$tahun] = $laporan->total_panen;
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
