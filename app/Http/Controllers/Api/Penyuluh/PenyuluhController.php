<?php

namespace App\Http\Controllers\Api\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PenyuluhController extends Controller
{
    public function getDesa($id)
    {
        try {
            $user = User::with(['desas.luasLahanWilayah','desas.kecamatan'])->find($id);

            if (is_null($user)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data kosong',
                    'data' => null
                ], 201);
            }

            $data = [];
            foreach ($user->desas as $desa) {
                if ($desa->luasLahanWilayah) {
                    $data[] = [
                        'id' => $desa->luasLahanWilayah->id,
                        'kecamatan_id' => $desa->luasLahanWilayah->kecamatan_id,
                        'kecamatan_name' => $desa->kecamatan->name,
                        'desa_id' => $desa->luasLahanWilayah->desa_id,
                        'lahan_sawah' => $desa->luasLahanWilayah->lahan_sawah,
                        'lahan_non_sawah' => $desa->luasLahanWilayah->lahan_non_sawah,
                        'desa' => [
                            'id' => $desa->id,
                            'district_id' => $desa->district_id,
                            'name' => $desa->name,
                        ]
                    ];
                }
            }

            if (empty($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data kosong',
                    'data' => null
                ], 201);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil didapatkan',
                'data' => $data
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
