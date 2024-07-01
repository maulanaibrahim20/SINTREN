<?php

namespace App\Http\Controllers\WEB\Penyuluh\Master;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\DetailLaporanPadi;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\LuasLahanWilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LuasLahanWilayahUptdController extends Controller
{
    protected $luas_wilayah, $detail_laporan;

    public function __construct(LuasLahanWilayah $luas_wilayah, DetailLaporanPadi $detail_laporan)
    {
        $this->luas_wilayah = $luas_wilayah;
        $this->detail_laporan = $detail_laporan;
    }
    public function index()
    {
        $content = [
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Luas lahan wilayah',
            'title' => 'Luas Lahan Wilayah',
            'button_create' => 'Tambah Luas Lahan Wilayah',
        ];

        $kecamatanId = Auth::user()->uptd->kecamatan->id;

        $data = DB::table('luas_lahan_wilayah')
            ->join('desas', 'luas_lahan_wilayah.desa_id', '=', 'desas.id')
            ->join('kecamatans', 'desas.district_id', '=', 'kecamatans.id')
            ->select(
                'kecamatans.name as kecamatan_name',
                'desas.name as desa_name',
                DB::raw('SUM(luas_lahan_wilayah.lahan_sawah) as total_lahan_sawah'),
                DB::raw('SUM(luas_lahan_wilayah.lahan_non_sawah) as total_lahan_non_sawah')
            )
            ->where('kecamatans.id', $kecamatanId)
            ->groupBy('kecamatans.name', 'desas.name')
            ->orderBy('desas.name')
            ->get()
            ->toArray();

        return view('uptd.pages.master.luas_lahan_wilayah.index', array_merge($content, ['data' => $data]));
    }
}
