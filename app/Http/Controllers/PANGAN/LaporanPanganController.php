<?php

namespace App\Http\Controllers\Pangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportLaporanPangan;
use App\Models\Pangan\JenisPangan;
use App\Models\Pangan\LaporanPangan;
use App\Models\Pasar\Pasar;
use App\Models\User;
use App\Models\Pangan\SubjenisPangan;
use Illuminate\Support\Facades\DB;

class LaporanPanganController extends Controller
{
    protected $laporanpangan;
    protected $pasar;
    protected $jenispangan;
    protected $subjenisPangan;
    protected $user;

    public function __construct(
        LaporanPangan $laporanpangan,
        Pasar $pasar,
        JenisPangan $jenispangan,
        SubjenisPangan $subjenisPangan,
        User $user
    ) {
        $this->laporanpangan = $laporanpangan;
        $this->pasar = $pasar;
        $this->jenispangan = $jenispangan;
        $this->subjenisPangan = $subjenisPangan;
        $this->user = $user;
    }

    public function index(Request $request)
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
        ->whereHas('jenis_pangan') // Pastikan hanya data dengan jenis_pangan yang ada
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
            'selectedDate' => $selectedDate,
        ];

        return view('pangan.views.pangan.laporan.laporan_harian.index', $data);
    }

    public function exportHarian(Request $request)
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

        // Mengambil hanya subjenis_pangan yang ada di laporan pangan yang sudah difilter
        $subjenisPanganIds = $laporanpangan->pluck('subjenis_pangan_id');
        $subjenisPangan = SubjenisPangan::with('jenis_pangan')
            ->whereIn('id', $subjenisPanganIds)
            ->orderBy('name', 'asc')
            ->get();

        // Membuat data yang dikelompokkan berdasarkan subjenis_pangan_id
        $dataGroupedBySubjenis = $subjenisPangan->keyBy('id')->map(function ($subjenis) use ($laporanpangan) {
            $laporan = $laporanpangan->firstWhere('subjenis_pangan_id', $subjenis->id);

            return [
                'name' => $subjenis->name,
                'jenis_pangan_name' => $subjenis->jenis_pangan->name ?? 'Jenis Pangan Tidak Ditemukan',
                'total_stok' => $laporan->total_stok ?? 0,
                'avg_harga' => $laporan->avg_harga ?? 0,
                'latest_date' => $laporan->latest_date ?? null,
            ];
        });

        // Mengatur nama file dengan tanggal yang dipilih
        $fileName = 'laporan_pangan_harian_' . Carbon::parse($selectedDate)->format('Y_m_d') . '.xlsx';

        // Mengatur data yang akan diekspor
        return Excel::download(new ExportLaporanPangan($dataGroupedBySubjenis), $fileName);
    }

    public function bulanan(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $today = now()->toDateString(); // Mendapatkan tanggal hari ini dalam format 'Y-m-d'

        // Mengambil data laporan pangan dan mengelompokkan berdasarkan subjenis_pangan_id
        $laporanpangan = LaporanPangan::select(
            'subjenis_pangan_id',
            DB::raw('SUM(stok) as total_stok'),
            DB::raw('AVG(harga) as avg_harga'),
            DB::raw('MAX(date) as latest_date')
        )
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->where('status', '1')
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
            'title' => 'Laporan Pangan Bulanan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Laporan Pangan Bulanan',
            'dataGroupedBySubjenis' => $dataGroupedBySubjenis,
            'pasarList' => $pasarList,
            'jenisPangan' => $jenisPangan,
            'noData' => $dataGroupedBySubjenis->isEmpty(),
        ];

        return view('pangan.views.pangan.laporan.laporan_bulanan.index', $data);
    }

    public function exportBulanan(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $today = now()->toDateString(); // Mendapatkan tanggal hari ini dalam format 'Y-m-d'

        // Mengambil nama bulan dalam format 'F' (January, February, etc.)
        $monthName = Carbon::create()->month($month)->format('F');

        // Mengambil data laporan pangan dan mengelompokkan berdasarkan subjenis_pangan_id
        $laporanpangan = LaporanPangan::select(
            'subjenis_pangan_id',
            DB::raw('SUM(stok) as total_stok'),
            DB::raw('AVG(harga) as avg_harga'),
            DB::raw('MAX(date) as latest_date')
        )
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->where('status', '1')
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

                // Mengatur nama file dengan nama bulan dan tahun
            $fileName = 'laporan_pangan_bulan_' . $monthName . '_' . $year . '.xlsx';

            // Mengatur data yang akan diekspor
            return Excel::download(new ExportLaporanPangan($dataGroupedBySubjenis), $fileName);
        // Mengatur data yang akan diekspor
        // return Excel::download(new ExportLaporanPangan($dataGroupedBySubjenis), 'laporan_pangan_bulan.xlsx');
    }

    public function tahunan(Request $request)
    {
        $year = $request->input('year', now()->year);

        // Mengambil data laporan pangan dan mengelompokkan berdasarkan subjenis_pangan_id
        $laporanpangan = LaporanPangan::select(
            'subjenis_pangan_id',
            DB::raw('SUM(stok) as total_stok'),
            DB::raw('AVG(harga) as avg_harga'),
            DB::raw('MAX(date) as latest_date')
        )
        ->whereYear('date', $year)
        ->where('status', '1')
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
            'title' => 'Laporan Pangan Tahunan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Laporan Pangan Tahunan',
            'dataGroupedBySubjenis' => $dataGroupedBySubjenis,
            'pasarList' => $pasarList,
            'jenisPangan' => $jenisPangan,
            'noData' => $dataGroupedBySubjenis->isEmpty(),
        ];

        return view('pangan.views.pangan.laporan.laporan_tahunan.index', $data);
    }

    public function exportTahunan(Request $request)
    {
        $year = $request->input('year', now()->year);

        $yearName = Carbon::create()->year($year)->format('F');


        // Mengambil data laporan pangan dan mengelompokkan berdasarkan subjenis_pangan_id
        $laporanpangan = LaporanPangan::select(
            'subjenis_pangan_id',
            DB::raw('SUM(stok) as total_stok'),
            DB::raw('AVG(harga) as avg_harga'),
            DB::raw('MAX(date) as latest_date')
        )
        ->whereYear('date', $year)
        ->where('status', '1')
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
        // Mengatur nama file dengan nama bulan dan tahun
        $fileName = 'laporan_pangan_tahun_' . $year . '.xlsx';
        // Mengatur data yang akan diekspor
        return Excel::download(new ExportLaporanPangan($dataGroupedBySubjenis), $fileName);
    }
}
