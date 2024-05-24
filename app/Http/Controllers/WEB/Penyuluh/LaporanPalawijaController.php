<?php

namespace App\Http\Controllers\WEB\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\Operator\TanamanPalawija;
use App\Models\Penyuluh\DetailLaporanPalawija;
use App\Models\Penyuluh\JenisPalawija;
use App\Models\Penyuluh\LaporanPalawija;
use App\Models\Uptd\PenugasanPenyuluh;
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

    public function __construct(
        LaporanPalawija $laporanPalawija,
        Desa $desa,
        TanamanPalawija $tanamanPalawija,
        JenisPalawija $jenisPalawija,
        PenugasanPenyuluh $penugasanPenyuluh,
    ) {
        $this->laporanPalawija = $laporanPalawija;
        $this->desa = $desa;
        $this->tanamanPalawija = $tanamanPalawija;
        $this->jenisPalawija = $jenisPalawija;
        $this->penugasan = $penugasanPenyuluh;
    }

    public function kirimkan(Request $request)
    {
        try {
            $this->laporanPalawija::where('id', $request->id)->update([
                'status' => 'terkirim',
            ]);
            Alert::success('success', 'Success Data Berhasil Dikirimkan!');
            return back()->with('success', 'Data Berhasil DiKirimkan!');
        } catch (\Exception $e) {
            Alert::error('error', 'Error' . $e->getMessage());
            return back()->with('error' . $e->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'palawija' => $this->laporanPalawija::where('user_id', Auth::user()->id)->get(),
        ];
        return view('penyuluh.pages.laporan_palawija.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kecamatan = Auth::user()->penyuluh->kecamatan->id;
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
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->laporanPalawija->create([
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

            return redirect('/penyuluh/create/laporan_palawija')->with('success', 'Data Laporan Palawija Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error Data Laporan Palawija Gagal Ditambahkan!'.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $laporanPalawija = $this->laporanPalawija::findOrFail($id);
        $data = [
            'laporanPalawija' => $laporanPalawija,


        ];
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

        return view ('penyuluh.pages.laporan_palawija.update',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
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
    public function destroy(string $id)
    {
        //
    }
}
