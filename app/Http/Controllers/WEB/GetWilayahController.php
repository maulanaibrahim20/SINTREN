<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Wilayah\Desa;
use Illuminate\Http\Request;

class GetWilayahController extends Controller
{
    public function ambil_desa(Request $request)
    {
        $kecamatan = $request->kecamatan;
        $desa = Desa::where('district_id', $kecamatan)->get();

        foreach ($desa as $des) {
            echo "<option value='" . $des['id'] . "'>" . $des['name'] . "</option>";
        }
    }

    public function ambil_desa_filtering(Request $request)
    {
        $kecamatan = $request->kecamatan;
        $desa = Desa::whereIn('id', LaporanPadi::pluck('desa_id'))
            ->where('district_id', $kecamatan)
            ->get();

        foreach ($desa as $des) {
            echo "<option value='" . $des->id . "'>" . $des->name . "</option>";
        }
    }
}
