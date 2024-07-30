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
use App\Models\Pasar\PetugasPasar;
use App\Models\Pangan\LaporanPangan;
use App\Models\Pangan\SubjenisPangan;
use App\Models\Pangan\JenisPangan;
use App\Models\Pasar\Pasar;
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
            'LaporanPadi' => LaporanPadi::count(),
            'luasLahanWilayah' => LuasLahanWilayah::count(),
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

        // $laporanPadi = DB::table('laporan_padis')
        //     ->selectRaw("DATE_FORMAT(date, '%Y-%m') AS bulan")
        //     ->selectRaw("SUM(CASE WHEN tipe_data = 'panen' THEN nilai ELSE 0 END) AS total_panen")
        //     ->selectRaw("SUM(CASE WHEN tipe_data = 'tanam' THEN nilai ELSE 0 END) AS total_tanam")
        //     ->selectRaw("SUM(CASE WHEN tipe_data = 'puso/rusak' THEN nilai ELSE 0 END) AS total_puso_rusak")
        //     ->whereYear('date', '>=', $dariTahun)
        //     ->whereYear('date', '<=', $sampaiTahun)
        //     ->groupBy('bulan')
        //     ->orderBy('bulan', 'ASC')
        //     ->get();


        $laporanPadi = DB::table('laporan_padis')
            ->selectRaw('YEAR(date) AS tahun')
            ->selectRaw('SUM(CASE WHEN tipe_data = "panen" THEN nilai ELSE 0 END) AS total_panen')
            ->whereYear('date', '>=', $dariTahun)
            ->whereYear('date', '<=', $sampaiTahun)
            ->groupBy('tahun')
            ->orderBy('tahun', 'ASC')
            ->get();

        $hasilPerTahun = [];

        foreach ($laporanPadi as $laporan) {
            $tahun = $laporan->tahun;

            if (!isset($hasilPerTahun[$tahun])) {
                $hasilPerTahun[$tahun] = 0;
            }
            $hasilPerTahun[$tahun] += $laporan->total_panen;
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
            $prediksi = round($regression->predict([$tahun])); // Membulatkan hasil prediksi
            $hasilPrediksi[$tahun] = $prediksi;

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
            $jumlahPetugasPasar = PetugasPasar::count();
            // $jumlahDataPangan = LaporanPangan::count();
            $jumlahDataPangan = LaporanPangan::where('status', 1)->count();


            $today = now()->toDateString(); // Mendapatkan tanggal hari ini dalam format 'Y-m-d'

            // Mengambil data laporan pangan dan mengelompokkan berdasarkan subjenis_pangan_id
            $laporanpangan = LaporanPangan::select(
                'subjenis_pangan_id',
                DB::raw('SUM(stok) as total_stok'),
                DB::raw('AVG(harga) as avg_harga'),
                DB::raw('MAX(date) as latest_date')
            )
            ->where('status', '1')
            ->where('date', '>=', $today)
            ->groupBy('subjenis_pangan_id')
            ->get();

            // Mengambil data SubjenisPangan beserta informasi terkait
            $subjenisPangan = SubjenisPangan::with('jenis_pangan')->orderBy('name', 'asc')->get();

            // Membuat data yang dikelompokkan berdasarkan subjenis_pangan_id
            $dataGroupedBySubjenis = $subjenisPangan->keyBy('id')->map(function ($subjenis) use ($laporanpangan) {
                $laporan = $laporanpangan->firstWhere('subjenis_pangan_id', $subjenis->id);

                return [
                    'name' => $subjenis->name,
                    'jenis_pangan_name' => $subjenis->jenis_pangan->name ?? 'Jenis Pangan Tidak Ditemukan',
                    'gambar' => $subjenis->jenis_pangan->gambar ?? null,
                    'total_stok' => $laporan->total_stok ?? 0,
                    'avg_harga' => $laporan->avg_harga ?? 0,
                    'latest_date' => $laporan->latest_date ?? null,
                ];
            });

            $jenisPangan = JenisPangan::orderBy('name', 'asc')->get();
            $pasarList = Pasar::orderBy('name', 'asc')->get();

            $data = [
                'title' => 'Laporan Pangan Harian',
                'breadcrumb' => 'Dashboard',
                'breadcrumb_active' => 'Laporan Pangan Harian',
                'dataGroupedBySubjenis' => $dataGroupedBySubjenis,
                'pasarList' => $pasarList,
                'jenisPangan' => $jenisPangan,
            ];

            return view('pangan.views.dashboard.index',$data, compact('jumlahPetugasPasar','jumlahDataPangan'));
        }

    }
