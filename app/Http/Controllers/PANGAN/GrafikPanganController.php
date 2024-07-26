<?php

namespace App\Http\Controllers\PANGAN;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pangan\LaporanPangan;
use App\Models\Pangan\JenisPangan;
use App\Models\Pangan\SubjenisPangan;
use Carbon\Carbon;

class GrafikPanganController extends Controller
{
    public function grafikHarianIndex()
    {
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

        // Mengirim data ke view
        $data = [
            'dataGroupedBySubjenis' => $dataGroupedBySubjenis
        ];

        return view('pangan.views.grafik.grafik_harian.index', $data);
    }


    public function grafikBulananIndex(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Mengambil data laporan pangan dan mengelompokkan berdasarkan bulan
        $laporanpangan = LaporanPangan::select(
            DB::raw('MONTH(date) as month'),
            'subjenis_pangan_id',
            DB::raw('AVG(harga) as avg_harga')
        )
        ->whereYear('date', $year)
        ->where('status', '1')
        ->groupBy('month', 'subjenis_pangan_id')
        ->get();

        // Mengambil subjenis pangan dengan harga rata-rata tertinggi setiap bulannya
        $dataGroupedByMonth = [];
        foreach ($laporanpangan->groupBy('month') as $month => $laporanGroup) {
            $highestAvgHargaLaporan = $laporanGroup->sortByDesc('avg_harga')->first();
            $subjenis = SubjenisPangan::find($highestAvgHargaLaporan->subjenis_pangan_id);

            if ($subjenis) {
                $dataGroupedByMonth[$month] = [
                    'name' => $subjenis->name,
                    'avg_harga' => $highestAvgHargaLaporan->avg_harga
                ];
            }
        }

        // Mengirim data ke view
        $data = [
            'dataGroupedByMonth' => $dataGroupedByMonth
        ];

        return view('pangan.views.grafik.grafik_bulanan.index', $data);
    }

    public function grafikTahunanindex()
    {
        // Mengambil data laporan pangan dan mengelompokkan berdasarkan tahun dan subjenis_pangan_id
        $laporanpangan = LaporanPangan::select(
            DB::raw('YEAR(date) as year'),
            'subjenis_pangan_id',
            DB::raw('AVG(harga) as avg_harga')
        )
        ->where('status', '1')
        ->groupBy(DB::raw('YEAR(date)'), 'subjenis_pangan_id')
        ->get();

        // Mengambil data SubjenisPangan beserta informasi terkait
        $subjenisPangan = SubjenisPangan::with('jenis_pangan')->orderBy('name', 'asc')->get();

        // Mengelompokkan data berdasarkan tahun
        $dataGroupedByYear = $laporanpangan->groupBy('year')->map(function ($yearData) use ($subjenisPangan) {
            return $yearData->map(function ($data) use ($subjenisPangan) {
                $subjenis = $subjenisPangan->firstWhere('id', $data->subjenis_pangan_id);

                return [
                    'year' => $data->year,
                    'subjenis_pangan_name' => $subjenis->name ?? 'Subjenis Pangan Tidak Ditemukan',
                    'avg_harga' => $data->avg_harga,
                ];
            })->sortByDesc('avg_harga')->first(); // Mengambil subjenis pangan dengan rata-rata harga tertinggi per tahun
        });

        return view('pangan.views.grafik.grafik_tahunan.index', [
            'dataGroupedByYear' => $dataGroupedByYear
        ]);
    }


}


