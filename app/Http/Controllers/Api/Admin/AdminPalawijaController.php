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
                $laporanPalawija = LaporanPalawija::with(['desa', 'palawija','verify'])->get();
            } else {
                $laporanPalawija = LaporanPalawija::where('kecamatan_id', $id)->with(['desa', 'palawija','verify'])->get();
            }


            if ($laporanPalawija->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kosong.',
                    'data' => null
                ], 201);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data',
                'data' => $laporanPalawija
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
