<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\LaporanPalawija;
use App\Models\Penyuluh\LuasLahanWilayah;
use App\Models\Penyuluh\Penyuluh;
use App\Models\Prediksi;
use App\Models\PrediksiSp;
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
    protected $luasLahanWilayah;

    public function __construct(
        Penyuluh $penyuluh,
        LaporanPalawija $laporanPalawija,
        LaporanPadi $laporanPadi,
        PenugasanPenyuluh $penugasan,
        LuasLahanWilayah $luasLahanWilayah
    ) {
        $this->penyuluh = $penyuluh;
        $this->laporanPadi = $laporanPadi;
        $this->laporanPalawija = $laporanPalawija;
        $this->penugasan = $penugasan;
        $this->luasLahanWilayah = $luasLahanWilayah;
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

        $prediksi = Prediksi::select('tahun', 'nilai_prediksi', 'nilai_aktual', 'tipe_data')->get();
        $prediksiSP = PrediksiSp::select('tahun', 'nilai_prediksi', 'nilai_aktual')->get();

        // Kirim data ke view
        return view('pertanian.pages.dashboard.index', array_merge($data, ['prediksi' => $prediksi, 'prediksiSPData' => $prediksiSP]));
    }


    public function uptd()
    {
        $data = [
            'countPenyuluh' => $this->penyuluh->count(),
            'CountLaporanPadi' => $this->laporanPadi::where('kecamatan_id', Auth::user()->uptd->kecamatan->id)->count(),
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
        $userId = Auth::user()->id;

        $data['penugasan'] = $this->penugasan::where('user_id', $userId)->get();
        $data['laporanPadi'] = $this->laporanPadi::where('user_id', $userId)->get();

        $desaIds = $data['penugasan']->pluck('desa_id');

        $data['luasLahanWilayah'] = $this->luasLahanWilayah::whereIn('desa_id', $desaIds)->get();

        $data['perbandinganNilai'] = [];

        foreach ($desaIds as $desaId) {
            $luasLahan = $data['luasLahanWilayah']->where('desa_id', $desaId)->first();
            $laporanPadi = $data['laporanPadi']->where('desa_id', $desaId);

            $totalLahanSawah = $luasLahan ? $luasLahan->lahan_sawah : 0;
            $totalLahanNonSawah = $luasLahan ? $luasLahan->lahan_non_sawah : 0;

            $totalLaporanSawah = $laporanPadi->where('jenis_lahan', 'sawah')->sum('nilai');
            $totalLaporanNonSawah = $laporanPadi->where('jenis_lahan', 'non sawah')->sum('nilai');

            $persentaseSawah = $totalLahanSawah > 0 ? ($totalLaporanSawah / $totalLahanSawah) * 100 : 0;
            $persentaseNonSawah = $totalLahanNonSawah > 0 ? ($totalLaporanNonSawah / $totalLahanNonSawah) * 100 : 0;

            $data['perbandinganNilai'][$desaId] = [
                'sawah' => $persentaseSawah,
                'non_sawah' => $persentaseNonSawah,
                'has_value' => $laporanPadi->isNotEmpty()
            ];
        }

        return view('penyuluh.pages.dashboard.index', $data);
    }




    public function pangan()
    {
        return view('pangan.views.dashboard.index');
    }
}
