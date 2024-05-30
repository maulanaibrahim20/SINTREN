<?php

namespace App\Http\Controllers\WEB\Pertanian\Prediksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrediksiPadiController extends Controller
{
    public function index()
    {
        $content = [
            'title' => 'Prediksi Padi',

        ];
        return view('pertanian.pages.prediksi.padi.index', $content);
    }
}
