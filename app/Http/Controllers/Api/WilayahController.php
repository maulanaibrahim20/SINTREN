<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kecamatan;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function getDesa()
    {
        $desa = Desa::all();
        $responseData = [
            'status' => 'success',
            'message' => 'Get data successful',
            'data' => $desa
        ];
        return response()->json($responseData);
    }

    public function getKecamatan()
    {
        $kecamatan = Kecamatan::all();
        $responseData = [
            'status' => 'success',
            'message' => 'Get data successful',
            'data' => $kecamatan
        ];
        return response()->json($responseData);
    }
}
