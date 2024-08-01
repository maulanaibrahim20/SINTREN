<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\LuasLahanWilayah;
use App\Models\Wilayah\Desa;
use Illuminate\Http\Request;

class GetWilayahController extends Controller
{
    protected $luas_wilayah, $desa, $laporan_padi;

    public function __construct(Desa $desa, LaporanPadi $laporan_padi, LuasLahanWilayah $luas_wilayah)
    {
        $this->desa = $desa;
        $this->laporan_padi = $laporan_padi;
        $this->luas_wilayah = $luas_wilayah;
    }
    public function ambil_desa(Request $request)
    {
        $kecamatan = $request->kecamatan;
        $desa = Desa::where('district_id', $kecamatan)->get();
        $existingDesaIds = $this->luas_wilayah->where('kecamatan_id', $kecamatan)->pluck('desa_id')->toArray();

        foreach ($desa as $des) {
            $disabled = in_array($des->id, $existingDesaIds) ? 'disabled' : '';
            echo "<option value='" . $des->id . "' $disabled>" . $des->name . "</option>";
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
