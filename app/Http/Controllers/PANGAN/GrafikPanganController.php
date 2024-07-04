<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pangan\LaporanPangan;

class GrafikPanganController extends Controller
{
    public function grafikStokPanganindex()
    {
        return view('pangan.views.grafik.stok_pangan.index');
    }

    public function grafikNeracaPanganindex()
    {
        return view('pangan.views.grafik.neraca_pangan.index');
    }
    public function grafikHargaPanganindex()
    {
        return view('pangan.views.grafik.harga_pangan.index');
    }

    public function grafikTrenKetahananPanganindex()
    {
        return view('pangan.views.grafik.tren_ketahanan_pangan.index');
    }
    // public function grafikStokPangan()
    // {
    //     $laporanPangan = LaporanPangan::all();

    //     $data = $laporanPangan->map(function ($laporan) {
    //         return [
    //             'date' => $laporan->date,
    //             'ketersediaan' => $laporan->ketersediaan,
    //         ];
    //     });

    //     return response()->json(['data' => $data]);
    // }
}


