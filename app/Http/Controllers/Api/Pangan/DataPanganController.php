<?php

namespace App\Http\Controllers\Api\Pangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pangan\JenisPangan;
use App\Models\Pangan\LaporanPangan;
use App\Models\Pasar\Pasar;
// use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class DataPanganController extends Controller
{
    public function getDataPangan()
    {
        $laporanpangan = LaporanPangan::all();
        $responseData = [
            'status' => 'success',
            'message' => 'Get data successful',
            'data' => $laporanpangan
        ];
        return response()->json($responseData);
    }
}
