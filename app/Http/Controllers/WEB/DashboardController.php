<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\LaporanPalawija;
use App\Models\Penyuluh\Penyuluh;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $penyuluh;
    protected $laporanPadi;
    protected $laporanPalawija;

    public function __construct(Penyuluh $penyuluh, LaporanPalawija $laporanPalawija, LaporanPadi $laporanPadi)
    {
        $this->penyuluh = $penyuluh;
        $this->laporanPadi = $laporanPadi;
        $this->laporanPalawija = $laporanPalawija;
    }
    public function operator()
    {
        $user = User::count();
        $verifiedUsersCount = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsersCount = $user - $verifiedUsersCount;
        return view('operator.pages.dashboard.index', compact('user', 'verifiedUsersCount', 'unverifiedUsersCount'));
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
        return view('penyuluh.pages.dashboard.index');
    }
}
