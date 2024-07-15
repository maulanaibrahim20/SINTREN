<?php

namespace App\Http\Controllers\WEB\Uptd;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Uptd\VerifyPadi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanUptdPadiController extends Controller
{
    protected $laporanPadi, $verifyPadi;
    public function __construct(LaporanPadi $laporanPadi, VerifyPadi $verifyPadi)
    {
        $this->laporanPadi = $laporanPadi;
        $this->verifyPadi = $verifyPadi;
    }
    public function index()
    {
        $results = DB::table('laporan_padis')
            ->select(
                DB::raw("DATE_FORMAT(laporan_padis.date, '%Y-%m') AS month_year"),
                'laporan_padis.desa_id',
                'desas.name',
                'laporan_padis.kecamatan_id',
                DB::raw('SUM(laporan_padis.nilai) AS total_nilai'),
                'laporan_padis.created_at' // Menambahkan created_at ke dalam select
            )
            ->join('desas', 'desas.id', '=', 'laporan_padis.desa_id')
            ->groupBy(
                DB::raw("DATE_FORMAT(laporan_padis.date, '%Y-%m')"),
                'laporan_padis.desa_id',
                'desas.name',
                'laporan_padis.kecamatan_id',
                'laporan_padis.created_at' // Menambahkan created_at ke dalam group by
            )
            ->orderBy('month_year', 'desc') // Mengurutkan berdasarkan month_year dari yang terbaru
            ->orderBy('laporan_padis.desa_id')
            ->orderBy('laporan_padis.created_at', 'desc') // Mengurutkan berdasarkan created_at dari yang terbaru
            ->get();

        $kecamatanId = Auth::user()->uptd->kecamatan_id;
        $data['laporanPadi'] = $results->where('kecamatan_id', $kecamatanId);

        return view('uptd.pages.laporan.padi.index', $data);
    }

    public function showDetailLaporanKecamatan($desa_id, $month_year)
    {
        $data['desa'] = $this->laporanPadi::where('desa_id', $desa_id)
            ->where(DB::raw("DATE_FORMAT(date, '%Y-%m')"), $month_year)
            ->first();

        if (!$data['desa']) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $data['verify'] = $this->verifyPadi::where('laporan_id', $data['desa']->id)->get();
        $data['showDesa'] = $this->laporanPadi::with('verify')
            ->where('desa_id', $desa_id)
            ->where(DB::raw("DATE_FORMAT(date, '%Y-%m')"), $month_year)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('uptd.pages.laporan.padi.showDetailLaporan', $data);
    }


    public function changeStatus(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $verifyPadi = VerifyPadi::where('laporan_id', $id)->first();

            if ($verifyPadi) {
                $verifyPadi->status = $request->status;
                $verifyPadi->catatan = $request->catatan;

                $verifyPadi->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Status berhasil diubah');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Status gagal diubah: ' . $e->getMessage());
        }
    }
}
