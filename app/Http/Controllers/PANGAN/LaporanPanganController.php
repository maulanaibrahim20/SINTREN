<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportLaporanPangan;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Pangan\JenisPangan;
use App\Models\Pangan\LaporanPangan;
use App\Models\Pasar\Pasar;
use App\Models\User;
use App\Models\Pangan\SubjenisPangan;

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
        $today = now()->toDateString(); // Mendapatkan tanggal hari ini dalam format 'Y-m-d'

        // Default subjenis_pangan_id
        $defaultSubjenisPanganId = 1;

        $query = LaporanPangan::with(['pasar', 'jenis_pangan', 'subjenis_pangan'])
            ->where('status', '1') // Filter hanya data dengan status '1' (terkirim)
            ->where('date', '>=', $today) // Filter hanya data yang tanggalnya adalah hari ini atau lebih baru
            ->orderBy('date', 'desc');

        // Filter berdasarkan pasar jika dipilih
        if ($request->has('pasar_id') && $request->pasar_id) {
            $query->where('pasar_id', $request->pasar_id);
        }

        // Filter berdasarkan subjenis pangan jika dipilih atau gunakan default
        if ($request->has('subjenis_pangan_id') && $request->subjenis_pangan_id) {
            $query->where('subjenis_pangan_id', $request->subjenis_pangan_id);
        } else {
            $query->where('subjenis_pangan_id', $defaultSubjenisPanganId);
        }

        // Ambil data laporan pangan
        $datapangan = $query->get();

        // Ambil daftar untuk filter
        $subjenisPangan = SubjenisPangan::with('jenis_pangan')->orderBy('name', 'asc')->get();
        $jenisPangan = JenisPangan::orderBy('name', 'asc')->get();
        $pasarList = Pasar::orderBy('name', 'asc')->get();

        // Ambil data rata-rata harga per subjenis pangan
        $groupedData = LaporanPangan::select('subjenis_pangan_id', DB::raw('avg(harga) as rata_rata_harga'))
            ->where('status', '1')
            ->where('date', '>=', $today)
            ->groupBy('subjenis_pangan_id')
            ->get();

        $data = [
            'title' => 'Laporan Pangan Harian',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Laporan Pangan Harian',
            'datapangan' => $datapangan,
            'pasarList' => $pasarList,
            'jenisPangan' => $jenisPangan,
            'subjenisPangan' => $subjenisPangan,
            'groupedData' => $groupedData, // Masukkan data rata-rata harga ke dalam data yang dikirimkan ke view
        ];

        return view('pangan.views.pangan.laporan.laporan_harian.index', $data);
    }

    public function export(Request $request)
    {
        $today = now()->toDateString();
        $pasarId = $request->get('pasar_id');
        $subjenisPanganId = $request->get('subjenis_pangan_id');

        return Excel::download(new ExportLaporanPangan($pasarId, $subjenisPanganId), 'laporan_pangan_harian_' . $today . '.xlsx');
    }

    public function bulanan(Request $request)
    {
        $bulanIni = now()->format('Y-m'); // Mendapatkan format tahun-bulan ini (contoh: '2024-07')

        // Default subjenis_pangan_id
        $defaultSubjenisPanganId = 1;

        $query = LaporanPangan::with(['pasar', 'jenis_pangan', 'subjenis_pangan'])
            ->where('status', '1') // Filter hanya data dengan status '1' (terkirim)
            ->whereRaw('DATE_FORMAT(date, "%Y-%m") = ?', [$bulanIni]) // Filter hanya data bulan ini
            ->orderBy('date', 'desc');

        // Filter berdasarkan pasar jika dipilih
        if ($request->has('pasar_id') && $request->pasar_id) {
            $query->where('pasar_id', $request->pasar_id);
        }

        // Filter berdasarkan subjenis pangan jika dipilih atau gunakan default
        if ($request->has('subjenis_pangan_id') && $request->subjenis_pangan_id) {
            $query->where('subjenis_pangan_id', $request->subjenis_pangan_id);
        } else {
            $query->where('subjenis_pangan_id', $defaultSubjenisPanganId);
        }

        // Ambil data laporan pangan
        $datapangan = $query->get();

        // Ambil daftar untuk filter
        $subjenisPangan = SubjenisPangan::with('jenis_pangan')->orderBy('name', 'asc')->get();
        $jenisPangan = JenisPangan::orderBy('name', 'asc')->get();
        $pasarList = Pasar::orderBy('name', 'asc')->get();

        // Ambil data rata-rata harga per subjenis pangan
        $groupedData = LaporanPangan::select('subjenis_pangan_id', DB::raw('avg(harga) as rata_rata_harga'))
            ->where('status', '1')
            ->whereRaw('DATE_FORMAT(date, "%Y-%m") = ?', [$bulanIni]) // Filter hanya data bulan ini
            ->groupBy('subjenis_pangan_id')
            ->get();

        // Tentukan nilai $selectedMonth sesuai dengan request atau default
        $selectedMonth = $request->get('month', date('n')); // Ambil bulan dari request atau default ke bulan ini

        $data = [
            'title' => 'Laporan Pangan Bulanan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Laporan Pangan Bulanan',
            'datapangan' => $datapangan,
            'pasarList' => $pasarList,
            'jenisPangan' => $jenisPangan,
            'subjenisPangan' => $subjenisPangan,
            'groupedData' => $groupedData, // Masukkan data rata-rata harga ke dalam data yang dikirimkan ke view
            'selectedMonth' => $selectedMonth, // Tambahkan variabel selectedMonth ke dalam data yang dikirimkan ke view
        ];

        return view('pangan.views.pangan.laporan.laporan_bulanan.index', $data);
    }



    public function exportBulanan(Request $request)
    {
        $bulanIni = now()->format('Y-m'); // Mendapatkan format tahun-bulan ini (contoh: '2024-07')
        $pasarId = $request->get('pasar_id');
        $subjenisPanganId = $request->get('subjenis_pangan_id');

        return Excel::download(new ExportLaporanPangan($pasarId, $subjenisPanganId), 'laporan_pangan_bulanan_' . $bulanIni . '.xlsx');
    }

    public function tahunan(Request $request)
    {
        $tahunIni = now()->format('Y'); // Mendapatkan format tahun ini (contoh: '2024')

        // Default subjenis_pangan_id
        $defaultSubjenisPanganId = 1;

        $query = LaporanPangan::with(['pasar', 'jenis_pangan', 'subjenis_pangan'])
            ->where('status', '1') // Filter hanya data dengan status '1' (terkirim)
            ->whereYear('date', $tahunIni) // Filter hanya data tahun ini
            ->orderBy('date', 'desc');

        // Filter berdasarkan pasar jika dipilih
        if ($request->has('pasar_id') && $request->pasar_id) {
            $query->where('pasar_id', $request->pasar_id);
        }

        // Filter berdasarkan subjenis pangan jika dipilih atau gunakan default
        if ($request->has('subjenis_pangan_id') && $request->subjenis_pangan_id) {
            $query->where('subjenis_pangan_id', $request->subjenis_pangan_id);
        } else {
            $query->where('subjenis_pangan_id', $defaultSubjenisPanganId);
        }

        // Ambil data laporan pangan
        $datapangan = $query->get();

        // Ambil daftar untuk filter
        $subjenisPangan = SubjenisPangan::with('jenis_pangan')->orderBy('name', 'asc')->get();
        $jenisPangan = JenisPangan::orderBy('name', 'asc')->get();
        $pasarList = Pasar::orderBy('name', 'asc')->get();

        // Ambil data rata-rata harga per subjenis pangan
        $groupedData = LaporanPangan::select('subjenis_pangan_id', DB::raw('avg(harga) as rata_rata_harga'))
            ->where('status', '1')
            ->whereYear('date', $tahunIni) // Filter hanya data tahun ini
            ->groupBy('subjenis_pangan_id')
            ->get();

        // Tentukan nilai $selectedYear sesuai dengan request atau default
        $selectedYear = $request->get('year', $tahunIni); // Ambil tahun dari request atau default ke tahun ini

        $data = [
            'title' => 'Laporan Pangan Tahunan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Laporan Pangan Tahunan',
            'datapangan' => $datapangan,
            'pasarList' => $pasarList,
            'jenisPangan' => $jenisPangan,
            'subjenisPangan' => $subjenisPangan,
            'groupedData' => $groupedData, // Masukkan data rata-rata harga ke dalam data yang dikirimkan ke view
            'selectedYear' => $selectedYear, // Tambahkan variabel selectedYear ke dalam data yang dikirimkan ke view
        ];

        return view('pangan.views.pangan.laporan.laporan_tahunan.index', $data);
    }

    public function exportTahunan(Request $request)
    {
        $tahunIni = now()->format('Y'); // Mendapatkan format tahun ini (contoh: '2024')
        $pasarId = $request->get('pasar_id');
        $subjenisPanganId = $request->get('subjenis_pangan_id');

        return Excel::download(new ExportLaporanPangan($pasarId, $subjenisPanganId), 'laporan_pangan_tahunan_' . $tahunIni . '.xlsx');
    }
}

