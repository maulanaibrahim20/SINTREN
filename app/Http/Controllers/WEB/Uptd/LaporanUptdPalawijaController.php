<?php

namespace App\Http\Controllers\WEB\Uptd;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPalawija;
use App\Models\Uptd\VerifyPalawija;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanUptdPalawijaController extends Controller
{
    protected $laporanPalawija, $verifyPalawija;

    public function __construct(LaporanPalawija $laporanPalawija, VerifyPalawija $verifyPalawija)
    {
        $this->laporanPalawija = $laporanPalawija;
        $this->verifyPalawija = $verifyPalawija;
    }
    public function index()
    {
        $results = DB::table('laporan_palawijas')
            ->select(
                DB::raw("DATE_FORMAT(laporan_palawijas.date, '%Y-%m') AS month_year"),
                'laporan_palawijas.desa_id',
                'desas.name',
                'laporan_palawijas.kecamatan_id',
                DB::raw('SUM(laporan_palawijas.nilai) AS total_nilai')
            )
            ->join('desas', 'desas.id', '=', 'laporan_palawijas.desa_id')
            ->groupBy(
                DB::raw("DATE_FORMAT(laporan_palawijas.date, '%Y-%m')"),
                'laporan_palawijas.desa_id',
                'desas.name',
                'laporan_palawijas.kecamatan_id',
                'laporan_palawijas.created_at'
            )
            ->orderBy('month_year', 'desc')
            ->orderBy('laporan_palawijas.desa_id')
            ->orderBy('laporan_palawijas.created_at', 'desc')
            ->get();
        $kecamatanId = Auth::user()->uptd->kecamatan_id;
        $data['laporanPalawija'] = $results->where('kecamatan_id', $kecamatanId)->sortBy('created_at');
        return view('uptd.pages.laporan.palawija.index', $data);
    }

    public function showDetailLaporanKecamatan($desa_id, $month_year)
    {
        $data['desa'] = $this->laporanPalawija::where('desa_id', $desa_id)
            ->where(DB::raw("DATE_FORMAT(date, '%Y-%m')"), $month_year)
            ->first();

        if (!$data['desa']) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $data['verify'] = $this->verifyPalawija::where('laporan_id', $data['desa']->id)->get();
        $data['showDesa'] = $this->laporanPalawija::with('verify')
            ->where('desa_id', $desa_id)
            ->where(DB::raw("DATE_FORMAT(date, '%Y-%m')"), $month_year)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('uptd.pages.laporan.palawija.showDetailLaporan', $data);
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $verifyPalawija = $this->verifyPalawija::where('laporan_id', $id)->first();

            if ($verifyPalawija) {
                $verifyPalawija->status = $request->status;
                $verifyPalawija->catatan = $request->catatan;

                $verifyPalawija->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Status berhasil diubah');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Status gagal diubah: ' . $e->getMessage());
        }
    }
}
