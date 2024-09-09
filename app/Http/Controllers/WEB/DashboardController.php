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
use App\Models\Uptd\VerifyPadi;
use Carbon\Carbon;
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
    protected $pasar;

    public function __construct(
        Penyuluh $penyuluh,
        LaporanPalawija $laporanPalawija,
        LaporanPadi $laporanPadi,
        PenugasanPenyuluh $penugasan,
        LuasLahanWilayah $luasLahanWilayah,
        Pasar $pasar
    ) {
        $this->penyuluh = $penyuluh;
        $this->laporanPadi = $laporanPadi;
        $this->laporanPalawija = $laporanPalawija;
        $this->penugasan = $penugasan;
        $this->luasLahanWilayah = $luasLahanWilayah;
        $this->pasar = $pasar;
    }
    public function operator()
    {
        $data = [
            'user' => User::count(),
            'penugasan' => PenugasanPenyuluh::count(),
            'LaporanPadi' => LaporanPadi::count(),
            'luasLahanWilayah' => LuasLahanWilayah::count(),
            'pasar' => Pasar::count(),
        ];
        return view('operator.pages.dashboard.index', $data);
    }

    public function pertanian()
    {
        $latestYear = $this->laporanPadi::orderBy('date', 'desc')->value(DB::raw('YEAR(date)'));

        $laporan = LaporanPadi::where('tipe_data', 'panen')
            ->join('verify_padis', 'laporan_padis.id', '=', 'verify_padis.laporan_id')
            ->whereYear('date', $latestYear)
            ->where('verify_padis.status', 'terima')
            ->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(nilai) as total')
            )
            ->groupBy('month')
            ->get();

        $chartMonths = [];
        $chartData = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthName = \Carbon\Carbon::create()->month($i)->monthName;
            $chartMonths[] = "$monthName $latestYear";
            $chartData[$i] = 0;
        }

        foreach ($laporan as $item) {
            $chartData[$item->month] = $item->total;
        }

        $chartData = array_values($chartData);

        $latestYear = DB::table('laporan_padis')
            ->select(DB::raw('YEAR(MAX(date)) as latest_year'))
            ->first()->latest_year;

        $kecamatanData = DB::table('laporan_padis')
            ->join('verify_padis', 'laporan_padis.id', '=', 'verify_padis.laporan_id')
            ->join('kecamatans', 'laporan_padis.kecamatan_id', '=', 'kecamatans.id')
            ->select('kecamatans.id as kecamatan_id', 'kecamatans.name as kecamatan', DB::raw('SUM(laporan_padis.nilai) as total_produksi'))
            ->where('laporan_padis.tipe_data', 'panen')
            ->whereYear('laporan_padis.date', $latestYear)
            ->where('verify_padis.status', 'terima') // Menambahkan kondisi untuk memfilter berdasarkan status verify
            ->groupBy('kecamatans.id', 'kecamatans.name')
            ->get();

        $desaData = DB::table('laporan_padis')
            ->join('desas', 'laporan_padis.desa_id', '=', 'desas.id')
            ->join('verify_padis', 'laporan_padis.id', '=', 'verify_padis.laporan_id')
            ->select('desas.name as desa', 'desas.district_id as kecamatan_id', DB::raw('SUM(laporan_padis.nilai) as total_produksi'))
            ->where('laporan_padis.tipe_data', 'panen')
            ->whereYear('laporan_padis.date', $latestYear)
            ->where('verify_padis.status', 'terima') // Menambahkan kondisi untuk memfilter berdasarkan status verify
            ->groupBy('desas.name', 'desas.district_id')
            ->get();
        $data = [
            'countPenyuluh' => $this->penyuluh::count(),
            'CountLaporanPadi' => $this->laporanPadi::count(),
            'CountLaporanPalawija' => $this->laporanPalawija::count(),
            'CountLuasLahanWilayah' => $this->luasLahanWilayah::count(),
            'chartMonths' => $chartMonths,
            'chartData' => $chartData,
            'kecamatanData' => $kecamatanData,
            'desaData' => $desaData,
            'latestYear' => $latestYear
        ];
        $dariTahun = 2010;
        $sampaiTahun = 2021;

        $laporanPadi = DB::table('laporan_padis')
            ->join('verify_padis', 'laporan_padis.id', '=', 'verify_padis.laporan_id') // Menggunakan join untuk menghubungkan tabel verify
            ->selectRaw('YEAR(laporan_padis.date) AS tahun')
            ->selectRaw('SUM(CASE WHEN laporan_padis.tipe_data = "panen" THEN laporan_padis.nilai ELSE 0 END) AS total_panen')
            ->whereYear('laporan_padis.date', '>=', '2010')
            ->whereYear('laporan_padis.date', '<=', '2021')
            ->where('verify_padis.status', 'terima')   // Menambahkan kondisi untuk memfilter berdasarkan status verify
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
            $prediksi = round($regression->predict([$tahun]));
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
                $error = abs(($aktual - $prediksi) / $aktual);
                $totalError += $error;
                $n++;
                $predictions[$tahun]['error'] = $error;
            }
        }
        $mape = round(($totalError / $n) * 100, 2);

        return view('pertanian.pages.dashboard.index', $data, [
            'labels' => $labels,
            'actualData' => array_values($actualData),
            'predictedData' => array_column($predictions, 'predicted_value'),
            'mape' => $mape,
            'predictions' => $predictions
        ]);
    }


    public function uptd()
    {
        // Ambil kecamatan_id dari user yang sedang login
        $kecamatanId = Auth::user()->uptd->kecamatan_id;

        // Ambil tahun sekarang
        $tahunSekarang = Carbon::now()->year;

        // Ambil data laporan panen dari 5 tahun terakhir
        $laporanPanen = LaporanPadi::where('kecamatan_id', $kecamatanId)
            ->where('tipe_data', 'panen')
            ->whereYear('date', '>=', $tahunSekarang - 5) // Ambil 5 tahun terakhir
            ->with('desa') // Load relationship 'desa'
            ->get();

        // Inisialisasi array untuk menampung data untuk Highcharts
        $chartData = [];

        // Hitung total panen per desa
        foreach ($laporanPanen as $laporan) {
            $namaDesa = $laporan->desa->name;
            $tahunData = date('Y', strtotime($laporan->date));
            $key = $namaDesa . ' ' . $tahunData;

            // Tambahkan nilai panen ke dalam array
            if (!isset($chartData[$key])) {
                $chartData[$key] = [
                    'name' => $key,
                    'y' => 0, // Inisialisasi nilai total panen
                ];
            }
            $chartData[$key]['y'] += $laporan->nilai;
        }

        // Ubah format data untuk Highcharts
        $chartDataFormatted = array_values($chartData); // Ubah ke array indexed

        // Data untuk dikirim ke view
        $data = [
            'chartData' => json_encode($chartDataFormatted),
            'countPenyuluh' => $this->penyuluh->count(),
            'CountLaporanPadi' => $this->laporanPadi->count(),
            'CountLaporanPalawija' => $this->laporanPalawija->count(),
            'CountLuasLahanWilayah' => $this->luasLahanWilayah->where('kecamatan_id', $kecamatanId)->count(),
            'CountSudahVerifikasi' => VerifyPadi::where('status', 'terima')->count(),
            'CountBelumVerifikasi' => VerifyPadi::where('status', 'tunggu')->count(),
        ];

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
