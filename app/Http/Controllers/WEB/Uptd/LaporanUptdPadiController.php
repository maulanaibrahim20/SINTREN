<?php

namespace App\Http\Controllers\WEB\Uptd;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanUptdPadiController extends Controller
{
    protected $laporanPadi;
    public function __construct(LaporanPadi $laporanPadi)
    {
        $this->laporanPadi = $laporanPadi;
    }
    public function index()
    {
        $kecamatanId = Auth::user()->uptd->kecamatan_id;
        $data['laporanPadi'] = $this->laporanPadi::where('kecamatan_id', $kecamatanId)->get();
        return view('uptd.pages.laporan.padi.index', $data);
    }
}
