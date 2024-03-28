<?php

namespace App\Http\Controllers\WEB\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\Operator\Palawija;
use App\Models\Penyuluh\DetailLaporanPalawija;
use App\Models\Penyuluh\JenisPalawija;
use App\Models\Penyuluh\LaporanPalawija;
use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kecamatan;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LaporanPalawijaController extends Controller
{
    protected $laporanPalawija;
    protected $detailPalawija;
    protected $desa;
    protected $tanamanPalawija;
    protected $jenisPalawija;

    public function __construct(
        LaporanPalawija $laporanPalawija,
        DetailLaporanPalawija $detailPalawija,
        Desa $desa,
        Palawija $tanamanPalawija,
        JenisPalawija $jenisPalawija,
    ) {
        $this->laporanPalawija = $laporanPalawija;
        $this->detailPalawija = $detailPalawija;
        $this->desa = $desa;
        $this->tanamanPalawija = $tanamanPalawija;
        $this->jenisPalawija = $jenisPalawija;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'palawija' => $this->laporanPalawija::all(),
            'detailLaporan' => $this->detailPalawija::all(),
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
            'desa' => $this->desa::where('district_id', $kecamatan)->get(),
            'tanamanPalawija' => $this->tanamanPalawija::all(),
            'jenisPalawija' => $this->jenisPalawija::all(),
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
            $palawija = $this->laporanPalawija->create([
                'jenis_lahan' => $request->jenis_lahan,
                'nama_pengumpul' => Auth::user()->name,
                'desa_id' => $request->desa,
                'kecamatan_id' => Auth::user()->penyuluh->kecamatan->id,
            ]);
            $this->detailPalawija->create([
                'id_laporan_palawija' => $palawija->id,
                'id_jenis_palawija' => $request->jenis_palawija,
                'jenis_bantuan' => $request->jenis_bantuan,
                'tanaman_akhir_bulan_lalu' => $request->tanaman_akhir_bulan_lalu,
                'panen' => $request->panen,
                'panen_muda' => $request->panen_muda,
                'panen_pakan_ternak' => $request->panen_pakan_ternak,
                'tanam' => $request->tanam,
                'puso_rusak' => $request->puso_rusak,
                'tanaman_akhir_bulan_laporan' => $request->tanaman_akhir_bulan_laporan,
                'total_produksi' => 0,
            ]);
            DB::commit();

            Alert::success('Success', 'Data Laporan Palawija Berhasil Ditambahkan!');
            return redirect('/penyuluh/create/laporan_palawija')->with('success', 'Data Laporan Palawija Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();

            Alert::error('error', 'Data Laporan Palawija Gagal Ditambahkan!' . $e->getMessage());
            return back()->with('error', 'Error Data Laporan Palawija Gagal Ditambahkan!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
