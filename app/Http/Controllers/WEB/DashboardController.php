<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\LaporanPalawija;
use App\Models\Penyuluh\Penyuluh;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Uptd\PenugasanPenyuluh;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $penyuluh;
    protected $laporanPadi;
    protected $laporanPalawija;
    protected $penugasan;

    public function __construct(Penyuluh $penyuluh, LaporanPalawija $laporanPalawija, LaporanPadi $laporanPadi, PenugasanPenyuluh $penugasan)
    {
        $this->penyuluh = $penyuluh;
        $this->laporanPadi = $laporanPadi;
        $this->laporanPalawija = $laporanPalawija;
        $this->penugasan = $penugasan;
    }
    public function operator()
    {
        $data = [
            'user' => User::count(),
            'penugasan' => PenugasanPenyuluh::count(),
        ];
        return view('operator.pages.dashboard.index', $data);
    }

    public function pertanian()
    {
        $data = [
            'countPenyuluh' => $this->penyuluh::count(),
            'CountLaporanPadi' => $this->laporanPadi::count(),
            'CountLaporanPalawija' => $this->laporanPalawija::count(),
        ];

        foreach ($data as $key => $value) {
            if ($value === null) {
                $data[$key] = 0;
            }
        }
        return view('pertanian.pages.dashboard.index', $data);
    }

    public function uptd()
    {
        $data = [
            'countPenyuluh' => $this->penyuluh->count(),
            'CountLaporanPadi' => $this->laporanPadi->count(),
            'CountLaporanPalawija' => $this->laporanPalawija->count(),
        ];

        foreach ($data as $key => $value) {
            if ($value === null) {
                $data[$key] = 0;
            }
        }
        return view('uptd.pages.dashboard.index', $data);
    }

    public function penyuluh()
    {
        $penugasan = $this->penugasan::where('user_id', Auth::user()->id)->get();

        if ($penugasan->isEmpty()) {
            $data['penugasan'] = $penugasan;
            return view('penyuluh.pages.dashboard.index', $data)->with('message', 'Tidak ada data penugasan yang tersedia.');
        } else {
            $data['penugasan'] = $penugasan;
            return view('penyuluh.pages.dashboard.index', $data);
        }
    }


    public function pangan()
    {
        return view('pangan.views.dashboard.index');
    }
}
