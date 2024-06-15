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
use Illuminate\Support\Facades\DB;
use Phpml\Regression\LeastSquares;

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
        $dariTahun = 2010;
        $sampaiTahun = 2021;

        $laporanPadi = DB::table('laporan_padis')
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') AS bulan")
            ->selectRaw("SUM(CASE WHEN tipe_data = 'panen' THEN nilai ELSE 0 END) AS total_panen")
            ->selectRaw("SUM(CASE WHEN tipe_data = 'tanam' THEN nilai ELSE 0 END) AS total_tanam")
            ->selectRaw("SUM(CASE WHEN tipe_data = 'puso/rusak' THEN nilai ELSE 0 END) AS total_puso_rusak")
            ->whereYear('date', '>=', $dariTahun)
            ->whereYear('date', '<=', $sampaiTahun)
            ->groupBy('bulan')
            ->orderBy('bulan', 'ASC')
            ->get();

        $hasilPerTahun = [];

        foreach ($laporanPadi as $laporan) {
            $tahun = substr($laporan->bulan, 0, 4);

            if (!isset($hasilPerTahun[$tahun])) {
                $hasilPerTahun[$tahun] = 0;
            }
            $hasilPerTahun[$tahun] += ($laporan->total_panen + $laporan->total_tanam - $laporan->total_puso_rusak);
        }
        $actualData = $hasilPerTahun;

        $fitur = [];
        $target = [];
        $labels = [];
        foreach ($hasilPerTahun as $tahun => $hasil) {
            $fitur[] = [(int) $tahun];
            $target[] = $hasil;
            $labels[] = $tahun;
        }

        $regression = new LeastSquares();
        $regression->train($fitur, $target);

        $hasilPrediksi = [];
        $prevValue = null;
        for ($tahun = $dariTahun; $tahun <= 2030; $tahun++) {
            // for ($tahun = max(array_keys($hasilPerTahun)) + 1; $tahun <= 2030; $tahun++) {
            $hasilPrediksi[$tahun] = $regression->predict([$tahun]);

            if ($tahun > $sampaiTahun) {
                $samples[] = [$tahun];
                $targets[] = $hasilPrediksi[$tahun];
                $labels[] = $tahun;
                $regression->train($samples, $targets);
            }

            $change = null;
            if ($prevValue !== null) {
                $change = $hasilPrediksi[$tahun] - $prevValue;
            }

            $predictions[] = [
                'year' => $tahun,
                'predicted_value' => $hasilPrediksi[$tahun],
                'change_from_previous_year' => $change
            ];

            $prevValue = $hasilPrediksi[$tahun];
        }

        $totalError = 0;
        $n = 0;
        foreach ($actualData as $tahun => $aktual) {
            if (isset($hasilPrediksi[$tahun])) {
                $prediksi = $hasilPrediksi[$tahun];
                $totalError += abs(($aktual - $prediksi) / $aktual);
                $n++;
            }
        }
        $mape = round(($totalError / $n) * 100, 2);


        // return view('pertanian.pages.prediksi.padiSp.regresiSp', [
        //     'labels' => $labels,
        //     'actualData' => array_values($actualData),
        //     'predictedData' => array_column($predictions, 'predicted_value'),
        //     'mape' => $mape
        // ]);
        return view('pertanian.pages.dashboard.index', $data, [
            'labels' => $labels,
            'actualData' => array_values($actualData),
            'predictedData' => array_column($predictions, 'predicted_value'),
            'mape' => $mape
        ]);
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
