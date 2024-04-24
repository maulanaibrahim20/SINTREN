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
        $luas_wilayah = $this->luas_wilayah::where('kecamatan_id', Auth::user()->uptd->kecamatan->id)->get();

        $kecamatanId = Auth::user()->uptd->kecamatan->id;
        $getTanamanAkhirBulanLaporan = $this->detail_laporan
            ->leftJoin('laporan_padis', 'detail_laporan_padi.id_laporan_padi', '=', 'laporan_padis.id')
            ->where('laporan_padis.kecamatan_id', $kecamatanId)
            ->select(DB::raw('SUM(panen + tanam + puso_rusak) as total'))
            ->pluck('total')
            ->sum();

        $wilayah_total = $this->luas_wilayah::where('kecamatan_id', Auth::user()->uptd->kecamatan->id)->first();

        if ($wilayah_total !== null) {
            $luas_lahan_wilayah = $wilayah_total->luas_lahan_wilayah;
            $selisih = $luas_lahan_wilayah - $getTanamanAkhirBulanLaporan;
        } else {
            $selisih = "Hasil selisih dari data luas wilayah belum tersedia";
        }
        $data  = [
            'selisih' => $selisih,
            'luas_wilayah' => $luas_wilayah,
            'getTanamanAkhirBulanLaporan' => $getTanamanAkhirBulanLaporan,
        ];

        return view('uptd.pages.master.luas_lahan_wilayah.index', $content, $data);
    }
}
