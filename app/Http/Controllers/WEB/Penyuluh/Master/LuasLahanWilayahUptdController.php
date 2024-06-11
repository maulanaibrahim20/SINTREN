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

        // Mendapatkan data luas wilayah berdasarkan kecamatan pengguna
        $luasWilayah = $this->luas_wilayah::where('kecamatan_id', Auth::user()->uptd->kecamatan->id)->get();

        // Menghitung total luas lahan sawah dan non-sawah
        $totalLuasSawah = $luasWilayah->where('jenis_lahan', 'sawah')->sum('luas_lahan_wilayah');
        $totalLuasNonSawah = $luasWilayah->where('jenis_lahan', 'non_sawah')->sum('luas_lahan_wilayah');
        $totalLuasWilayah = $totalLuasSawah + $totalLuasNonSawah;

        // Menyiapkan data untuk view
        $data['luas_wilayah'] = $luasWilayah;
        $data['total_luas_sawah'] = $totalLuasSawah;
        $data['total_luas_non_sawah'] = $totalLuasNonSawah;
        $data['total_luas_wilayah'] = $totalLuasWilayah;

        return view('uptd.pages.master.luas_lahan_wilayah.index', $content, $data);
    }
}
