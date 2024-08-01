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
}
