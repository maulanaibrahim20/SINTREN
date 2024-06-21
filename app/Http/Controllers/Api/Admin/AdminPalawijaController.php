<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPalawija;
use Illuminate\Http\Request;

class AdminPalawijaController extends Controller
{
    public function showAllByKecamatan($id)
    {
        try {
            if ($id == "dinas") {
                $laporanPalawija = LaporanPalawija::with(['desa', 'palawija', 'verify', 'kecamatan'])->get();
            } else {
                $laporanPalawija = LaporanPalawija::where('kecamatan_id', $id)->with(['desa', 'palawija', 'verify', 'kecamatan'])->get();
            }


            if ($laporanPalawija->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kosong.',
                    'data' => null
                ], 201);
            }

            $result = $laporanPalawija->map(function ($item) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'desa_id' => $item->desa_id,
                    'desa_name' => $item->desa->name,
                    'kecamatan_id' => $item->kecamatan_id,
                    'kecamatan_name' => $item->kecamatan ? $item->kecamatan->name : "",
                    'jenis_lahan' => $item->jenis_lahan,
                    'id_jenis_palawija' => $item->id_jenis_palawija,
                    'palawija_name' => $item->palawija->name,
                    'jenis_bantuan' => $item->jenis_bantuan,
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
}
