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

    public function grafikHarianIndex(Request $request)
    {
        $selectedDate = $request->input('date', now()->toDateString()); // Mendapatkan tanggal yang dipilih, default ke hari ini

        // Mengambil data laporan pangan dan mengelompokkan berdasarkan subjenis_pangan_id
        $laporanpangan = LaporanPangan::select(
            'subjenis_pangan_id',
            DB::raw('SUM(stok) as total_stok'),
            DB::raw('AVG(harga) as avg_harga'),
            DB::raw('MAX(date) as latest_date')
        )
        ->where('status', '1')
        ->whereDate('date', $selectedDate) // Menggunakan tanggal yang dipilih
        ->groupBy('subjenis_pangan_id')
        ->get();

        // Mengambil data SubjenisPangan beserta informasi terkait, gunakan eager loading untuk 'jenis_pangan'
        $subjenisPangan = SubjenisPangan::with('jenis_pangan')->orderBy('name', 'asc')->get();

        // Membuat data yang dikelompokkan berdasarkan subjenis_pangan_id
        $dataGroupedBySubjenis = $subjenisPangan->keyBy('id')->map(function ($subjenis) use ($laporanpangan) {
            $laporan = $laporanpangan->firstWhere('subjenis_pangan_id', $subjenis->id);

            return [
                'name' => $subjenis->name,
                'jenis_pangan_name' => $subjenis->jenis_pangan->name ?? 'Jenis Pangan Tidak Ditemukan',
                'gambar' => $subjenis->jenis_pangan->gambar ?? null,
                'total_stok' => number_format($laporan->total_stok ?? 0, 0, '.', ''), // Format stok
                'avg_harga' => number_format($laporan->avg_harga ?? 0, 0, '.', ''), // Format harga rata-rata
                'latest_date' => $laporan->latest_date ?? null,
            ];
        });

        // Mengirim data ke view
        $data = [
            'dataGroupedBySubjenis' => $dataGroupedBySubjenis,
            'selectedDate' => $selectedDate, // Kirim tanggal yang dipilih ke view
        ];

        return view('pangan.views.grafik.grafik_harian.index', $data);
    }

    public function grafikBulananIndex(Request $request)
    {
        $year = $request->input('year', now()->year);
        $selectedSubjenis = $request->input('subjenis_pangan', '');

        // Query laporan pangan dengan filter subjenis pangan jika ada
        $laporanpanganQuery = LaporanPangan::select(
            DB::raw('MONTH(date) as month'),
            DB::raw('YEAR(date) as year'),
            'subjenis_pangan_id',
            DB::raw('ROUND(AVG(harga)) as avg_harga'), // Membulatkan rata-rata harga
            DB::raw('SUM(stok) as total_stok')
        )
        ->whereYear('date', $year)
        ->where('status', '1');

        if ($selectedSubjenis) {
            $laporanpanganQuery->where('subjenis_pangan_id', $selectedSubjenis);
        }

        $laporanpangan = $laporanpanganQuery->groupBy('month', 'year', 'subjenis_pangan_id')->get();

        $subjenisPangan = SubjenisPangan::all()->keyBy('id');

        $dataGroupedBySubjenis = [];
        foreach ($subjenisPangan as $subjenis) {
            $dataGroupedBySubjenis[$subjenis->id] = [
                'name' => $subjenis->name,
                'data' => array_fill(1, 12, ['avg_harga' => 0, 'total_stok' => 0])
            ];
        }

        foreach ($laporanpangan as $laporan) {
            $dataGroupedBySubjenis[$laporan->subjenis_pangan_id]['data'][$laporan->month] = [
                'avg_harga' => $laporan->avg_harga,
                'total_stok' => $laporan->total_stok
            ];
        }

        $data = [
            'dataGroupedBySubjenis' => $dataGroupedBySubjenis,
            'months' => range(1, 12),
            'selectedYear' => $year,
            'subjenisPangan' => $subjenisPangan,
            'selectedSubjenis' => $selectedSubjenis
        ];

        return view('pangan.views.grafik.grafik_bulanan.index', $data);
    }

    public function grafikTahunanIndex(Request $request)
    {
        $selectedSubjenisPangan = $request->input('subjenis_pangan');

        // Mengambil data laporan pangan dan mengelompokkan berdasarkan tahun dan subjenis_pangan_id
        $laporanpangan = LaporanPangan::select(
            DB::raw('YEAR(date) as year'),
            'subjenis_pangan_id',
            DB::raw('SUM(stok) as total_stok'),
            DB::raw('AVG(harga) as avg_harga')
        )
        ->where('status', '1')
        ->when($selectedSubjenisPangan, function($query, $subjenis) {
            return $query->where('subjenis_pangan_id', $subjenis);
        })
        ->groupBy(DB::raw('YEAR(date)'), 'subjenis_pangan_id')
        ->get();

        // Mengambil data SubjenisPangan beserta informasi terkait
        $subjenisPangan = SubjenisPangan::orderBy('name', 'asc')->get();

        // Mengelompokkan data berdasarkan tahun
        $dataGroupedByYear = $laporanpangan->groupBy('year');

        // Mendapatkan daftar tahun untuk label sumbu X
        $years = $dataGroupedByYear->keys()->sort()->values(); // Mengurutkan tahun secara ascending

        // Mempersiapkan dataset untuk Chart.js
        $datasets = [];
        foreach ($subjenisPangan as $subjenis) {
            if ($selectedSubjenisPangan && $selectedSubjenisPangan != $subjenis->id) {
                continue; // Lewati subjenis yang tidak dipilih
            }

            $datasetData = $years->map(function ($year) use ($subjenis, $dataGroupedByYear) {
                $yearData = $dataGroupedByYear->get($year);
                // Jika tidak ada data untuk tahun ini, kembalikan 0
                $avgHarga = $yearData->where('subjenis_pangan_id', $subjenis->id)->pluck('avg_harga')->first();
                $totalStok = $yearData->where('subjenis_pangan_id', $subjenis->id)->pluck('total_stok')->first();
                return [
                    'year' => $year,
                    'avg_harga' => $avgHarga ? round($avgHarga, 2) : 0, // Pembulatan untuk menampilkan dua desimal
                    'total_stok' => $totalStok ? round($totalStok, 2) : 0
                ];
            })->values();

            $datasets[] = [
                'label' => $subjenis->name,
                'data' => $datasetData,
                'borderColor' => '#' . dechex(rand(0x000000, 0xFFFFFF)), // Warna acak untuk setiap subjenis
                'backgroundColor' => 'rgba(0, 0, 0, 0)',
                'borderWidth' => 2
            ];
        }

        return view('pangan.views.grafik.grafik_tahunan.index', [
            'dataGroupedByYear' => $years,
            'datasets' => $datasets,
            'subjenisPangan' => $subjenisPangan,
            'selectedSubjenisPangan' => $selectedSubjenisPangan
        ]);
    }


}


