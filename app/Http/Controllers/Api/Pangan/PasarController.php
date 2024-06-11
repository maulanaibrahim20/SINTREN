<?php

namespace App\Http\Controllers\Api\Pangan;

use App\Http\Controllers\Controller;
use App\Models\Pasar\Pasar;
use Illuminate\Http\Request;

class PasarController extends Controller
{
    public function getPasar()
    {
        $pasar = Pasar::all();
        $responseData = [
            'status' => 'success',
            'message' => 'Get data successful',
            'pasar' => $pasar
        ];
        return response()->json($responseData);
    }
}
