<?php

namespace App\Http\Controllers\WEB\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\Operator\TanamanPadi;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\Pengairan;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Desa;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Uptd\PenugasanPenyuluh;
use App\Models\Verification;

class LaporanPadiController extends Controller
{

    protected $jenis_padi;
    protected $kecamatan;
    protected $desa;
    protected $pengairan;
    protected $laporanpadi;
    protected $verification;
    protected $penugasanDesa;


    public function __construct(
        TanamanPadi $jenis_padi,
        Desa $desa,
        Kecamatan $kecamatan,
        Pengairan $pengairan,
        LaporanPadi $laporanpadi,
        PenugasanPenyuluh $penugasanDesa,
        Verification $verification,
    ) {
        $this->jenis_padi = $jenis_padi;
        $this->kecamatan = $kecamatan;
        $this->desa = $desa;
        $this->pengairan = $pengairan;
        $this->laporanpadi = $laporanpadi;
        $this->penugasanDesa = $penugasanDesa;
        $this->verification = $verification;
    }

    public function index()
    {
        $subquery = DB::table('laporan_padis')
            ->select(
                DB::raw("DATE_FORMAT(laporan_padis.date, '%Y-%m') AS month_year"),
                'laporan_padis.desa_id',
                'desas.name',
                DB::raw("SUM(laporan_padis.nilai) AS total_nilai"),
                DB::raw("MAX(verifications.id) AS id"),
                DB::raw("MAX(verifications.isVerify) AS isVerify")
            )
            ->join('desas', 'desas.id', '=', 'laporan_padis.desa_id')
            ->leftJoin('verifications', function ($join) {
                $join->on('laporan_padis.desa_id', '=', 'verifications.desa_id')
                    ->whereRaw("DATE_FORMAT(laporan_padis.date, '%Y-%m') = DATE_FORMAT(verifications.date, '%Y-%m')");
            })
            ->groupBy('month_year', 'laporan_padis.desa_id', 'desas.name');

        $query = DB::table(DB::raw("({$subquery->toSql()}) as subquery"))
            ->mergeBindings($subquery)
            ->select('month_year', 'desa_id', 'name', 'total_nilai', DB::raw("CASE WHEN id IS NOT NULL THEN isVerify ELSE 'false' END AS isVerify"))
            ->orderBy('month_year', 'asc')
            ->orderBy('desa_id', 'asc')
            ->get();

        $desaId = $this->penugasanDesa::pluck('desa_id')->toArray();
        $data = [
            'padi' => $query->whereIn('desa_id', $desaId)->sortBy('created_at'),
        ];

        return view('penyuluh.pages.laporan_padi.index', $data);
    }


    public function create()
    {
        $kecamatanId = Auth::user()->penyuluh->kecamatan->id;
        $data = [
            'jenis_padi' => $this->jenis_padi::orderBy('created_at', 'asc')->get(),
            'desa' => $this->desa::where('district_id', $kecamatanId)->get(),
            'pengairan' => $this->pengairan::all(),
            'penugasanDesa' => $this->penugasanDesa::where('user_id', Auth::user()->id)->get(),
        ];
        return view('penyuluh.pages.laporan_padi.create', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->laporanpadi->create([
                'user_id' => Auth::user()->id,
                'desa_id' => $request['desa'],
                'kecamatan_id' => Auth::user()->penyuluh->kecamatan->id,
                'jenis_lahan' => $request['jenis_lahan'],
                'jenis_bantuan' => $request['jenis_bantuan'],
                'id_jenis_padi' => $request['jenis_padi'],
                'id_jenis_pengairan' => $request['jenis_pengairan'],
                'date' => $request->date,
                'tipe_data' => $request['jenis_data'],
                'nilai' => $request['nilai'],
            ]);

            DB::commit();
            return redirect('/penyuluh/create/laporan_padi')->with('success', 'Data Laporan Padi Berhasil Dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Data Laporan Padi Gagal Dibuat!' . $e->getMessage());
        }
    }

    public function showDesa($desa_id)
    {
        $data['showDesa'] = $this->laporanpadi::where('desa_id', $desa_id)->get();
        $data['desa'] = $this->laporanpadi::where('desa_id', $desa_id)->first();
        return view('penyuluh.pages.laporan_padi.showDesa', $data)->with('success', 'Data Desa Berhasil Ditampilkan!');
    }

    public function show($id)
    {
        $data['show'] = $this->laporanpadi::findOrFail($id);
        return view('penyuluh.pages.laporan_padi.show', $data);
    }

    public function edit($id)
    {
        $data['edit'] = $this->laporanpadi::findOrFail($id);
        $data['penugasanDesa'] = $this->penugasanDesa::where('user_id', Auth::user()->id)->get();
        $data['pengairan'] = $this->pengairan::all();
        $data['jenis_padi'] = $this->jenis_padi::orderBy('created_at', 'asc')->get();
        return view('penyuluh.pages.laporan_padi.update', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            $laporan = $this->laporanpadi->findOrFail($id);

            $laporan->update([
                'desa_id' => $request['desa'],
                'jenis_lahan' => $request['jenis_lahan'],
                'jenis_bantuan' => $request['jenis_bantuan'],
                'id_jenis_pengairan' => $request['jenis_pengairan'],
                'id_jenis_padi' => $request['jenis_padi'],
                'date' => $request['date'],
                'tipe_data' => $request['jenis_data'],
                'nilai' => $request['nilai'],
            ]);

            DB::commit();
            return redirect('/penyuluh/create/laporan_padi')->with('success', 'Data Laporan Padi Berhasil Diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Data Laporan Padi Gagal Diperbarui! ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $penyuluh = $this->laporanpadi->findOrfail($id);
            $penyuluh->delete();
            DB::commit();
            Alert::success('success', 'Data Berhasil Dihapus!');
            return back()->with('success', 'Data Berhasil Dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Terjadi Kesalahan Saat Menghapus Data!' . $e->getMessage());
            return back()->with('error', 'Terjadi Kesalahan Saat Menghapus Data!' . $e->getMessage());
        }
    }

    public function getDesa(Request $request)
    {
        $id = $request->kecamatan;
        $desa = Desa::where('district_id', $id)->get();

        foreach ($desa as $data) {
            echo "<option value='" . $data['id'] . "'>" . $data['name'] . "</option>";
        }
    }
}
