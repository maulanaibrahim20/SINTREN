<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use Illuminate\Http\Request;

class AdminPadiController extends Controller
{
    public function showAllByKecamatan($id)
    {
        try {
            if ($id == "dinas") {
                $laporanPadi = LaporanPadi::with(['desa', 'pengairan','padi'])->get();
            } else {
                $laporanPadi = LaporanPadi::where('kecamatan_id', $id)->with(['desa', 'pengairan','padi'])->get();
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
}
