<?php

namespace App\Http\Controllers\WEB\Penyuluh;

use App\Http\Controllers\Controller;
use App\Http\Requests\Penyuluh\LaporanPalawija\StoreRequest;
use App\Http\Requests\Penyuluh\LaporanPalawija\UpdateRequest;
use App\Models\Operator\TanamanPalawija;
use App\Models\Penyuluh\DetailLaporanPalawija;
use App\Models\Penyuluh\JenisPalawija;
use App\Models\Penyuluh\LaporanPalawija;
use App\Models\Uptd\PenugasanPenyuluh;
use App\Models\Uptd\VerifyPalawija;
use App\Models\Wilayah\Desa;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LaporanPalawijaController extends Controller
{
    protected $laporanPalawija;
    protected $desa;
    protected $tanamanPalawija;
    protected $jenisPalawija;
    protected $penugasan;
    protected $verifyPalawija;

    public function __construct(
        LaporanPalawija $laporanPalawija,
        Desa $desa,
        TanamanPalawija $tanamanPalawija,
        TanamanPalawija $jenisPalawija,
        PenugasanPenyuluh $penugasanPenyuluh,
        VerifyPalawija $verifyPalawija,
    ) {
        $this->laporanPalawija = $laporanPalawija;
        $this->desa = $desa;
        $this->tanamanPalawija = $tanamanPalawija;
        $this->jenisPalawija = $jenisPalawija;
        $this->penugasan = $penugasanPenyuluh;
        $this->verifyPalawija = $verifyPalawija;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = DB::table('laporan_palawijas')
            ->select(
                DB::raw("DATE_FORMAT(laporan_palawijas.date, '%Y-%m') AS month_year"),
                'laporan_palawijas.desa_id',
                'desas.name',
                DB::raw("SUM(laporan_palawijas.nilai) AS total_nilai")
            )
            ->join('desas', 'desas.id', '=', 'laporan_palawijas.desa_id')
            ->groupBy('month_year', 'laporan_palawijas.desa_id', 'desas.name')
            ->orderBy('month_year', 'desc')
            ->orderBy('laporan_palawijas.desa_id')
            ->get();

        $desaId = $this->penugasan::pluck('desa_id')->toArray();
        $data = [
            'palawija' => $results->whereIn('desa_id', $desaId)->sortBy('created_at'),
        ];
        return view('penyuluh.pages.laporan_palawija.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'tanamanPalawija' => $this->tanamanPalawija::all(),
            'jenisPalawija' => $this->jenisPalawija::all(),
            'penugasanPenyuluh' => $this->penugasan::where('user_id', Auth::user()->id)->get(),
        ];
        return view('penyuluh.pages.laporan_palawija.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $laporanPalawija = $this->laporanPalawija->create([
                'user_id' => Auth::user()->id,
                'desa_id' => $request->desa,
                'kecamatan_id' => Auth::user()->penyuluh->kecamatan_id,
                'jenis_lahan' => $request->jenis_lahan,
                'jenis_bantuan' => $request->jenis_bantuan,
                'id_jenis_palawija' => $request->jenis_palawija,
                'date' => $request->date,
                'tipe_data' => $request->jenis_data,
                'nilai' => $request->nilai,
            ]);
            $this->verifyPalawija->create([
                'laporan_id' => $laporanPalawija['id'],
                'user_id' => Auth::user()->id,
                'status' => 'tunggu',
                'catatan' => null
            ]);
            DB::commit();

            return redirect('/penyuluh/create/laporan_palawija')->with('success', 'Data Laporan Palawija Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error Data Laporan Palawija Gagal Ditambahkan!' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */

    public function showDesa($desa_id)
    {
        $data['desa'] = $this->laporanPalawija::where('desa_id', $desa_id)->first();
        $data['verify'] = $this->verifyPalawija::where('laporan_id', $data['desa']->id)->get();
        $data['showDesa'] = $this->laporanPalawija::with('verify')->where('desa_id', $desa_id)->orderBy('created_at', 'desc')->get();
        return view('penyuluh.pages.laporan_palawija.showDesa', $data)->with('success', 'Data Desa Berhasil Ditampilkan!');
    }

    public function show(string $id)
    {
        $data['laporanPalawija'] = $this->laporanPalawija::findOrFail($id);
        return view('penyuluh.pages.laporan_palawija.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $editPalawija = $this->laporanPalawija::findOrFail($id);
        $data = [
            'editPalawija' => $editPalawija,
            'tanamanPalawija' => $this->tanamanPalawija::all(),
            'jenisPalawija' => $this->jenisPalawija::all(),
            'penugasanPenyuluh' => $this->penugasan::where('user_id', Auth::user()->id)->get(),
        ];

        return view('penyuluh.pages.laporan_palawija.update', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $laporanPalawija = $this->laporanPalawija::findOrFail($id);
            $laporanPalawija->update([
                'user_id' => Auth::user()->id,
                'desa_id' => $request->desa,
                'kecamatan_id' => Auth::user()->penyuluh->kecamatan_id,
                'jenis_lahan' => $request->jenis_lahan,
                'jenis_bantuan' => $request->jenis_bantuan,
                'id_jenis_palawija' => $request->jenis_palawija,
                'date' => $request->date,
                'tipe_data' => $request->jenis_data,
                'nilai' => $request->nilai,
            ]);

            DB::commit();
            return redirect('/penyuluh/create/laporan_palawija')->with('success', 'Data Laporan Palawija Berhasil Diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error Data Laporan Palawija Gagal Diperbarui! ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();


            $laporanPalawija = $this->laporanPalawija::findOrFail($id);
            $laporanPalawija->delete();

            DB::commit();
            return redirect('/penyuluh/create/laporan_palawija')->with('success', 'Data Laporan Palawija Berhasil Dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error Data Laporan Palawija Gagal Dihapus! ' . $e->getMessage());
        }
    }
}
