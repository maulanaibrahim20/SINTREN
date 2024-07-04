<?php

namespace App\Http\Controllers\Api\Pangan;

use App\Http\Controllers\Controller;
use App\Models\Pangan\JenisPangan;
use Illuminate\Http\Request;

class JenisPanganController extends Controller
{
    public function getJenisPangan()
    {
        $jenispangan = JenisPangan::all();
        $responseData = [
            'status' => 'success',
            'message' => 'Get data successful',
            'jenis_pangan' => $jenispangan
        ];
        return response()->json($responseData);
    }
}
