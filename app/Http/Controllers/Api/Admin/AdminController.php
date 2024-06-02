<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LuasLahanWilayah;
use App\Models\User;
use App\Models\Wilayah\Desa;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getDesa($id)
    {
        try {
            if($id == "dinas"){
                $assignments = LuasLahanWilayah::with('desa')->get();
            }else{
                $assignments = LuasLahanWilayah::with('desa')->where('kecamatan_id',$id)->get();
            }

            if (is_null($assignments)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data kosong',
                    'data' => null
                ], 201);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil didapatkan',
                'data' => $assignments
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan.',
                'data' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendapatkan data: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
