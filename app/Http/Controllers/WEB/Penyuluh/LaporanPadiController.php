<?php

namespace App\Http\Controllers\WEB\Penyuluh;

use App\Http\Controllers\Controller;
use App\Http\Requests\Penyuluh\LaporanPadiRequest;
use App\Models\Operator\Padi;
use App\Models\Penyuluh\DetailLaporanPadi;
use App\Models\Penyuluh\DetailLaporanPengairan;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\Master\JenisPadi;
use App\Models\Penyuluh\Pengairan;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Desa;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanPadiController extends Controller
{

    protected $jenis_padi;
    protected $kecamatan;
    protected $desa;
    protected $pengairan;
    protected $detailpadi;
    protected $laporanpadi;
    protected $detailpengairan;


    public function __construct(
        Padi $jenis_padi,
        Desa $desa,
        Kecamatan $kecamatan,
        Pengairan $pengairan,
        DetailLaporanPadi $detailpadi,
        LaporanPadi $laporanpadi,
        DetailLaporanPengairan $detailpengairan
    ) {
        $this->jenis_padi = $jenis_padi;
        $this->kecamatan = $kecamatan;
        $this->desa = $desa;
        $this->pengairan = $pengairan;
        $this->detailpadi = $detailpadi;
        $this->laporanpadi = $laporanpadi;
        $this->detailpengairan = $detailpengairan;
    }
    public function index()
    {
        $data = [
            'padi' => $this->laporanpadi::orderBy('created_at', 'asc')->get(),
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
        ];
        return view('penyuluh.pages.laporan_padi.create', $data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $laporanPadiId = $this->laporanpadi->create([
                'desa_id' => $request->desa,
                'kecamatan_id' => Auth::user()->penyuluh->kecamatan->id,
                'tanaman_akhir_bulan_lalu' => $request->tanaman_akhir_bulan_lalu,
                'nama_pengumpul' => Auth::user()->name,
                'jabatan' => 'penyuluh',
                'jenis_lahan' => $request->jenis_lahan,
                'id_rehab_jaringan_irigasi_tersier' => 0,
            ]);

            $this->detailpadi->create([
                'id_laporan_padi' => $laporanPadiId->id,
                'jenis_padi' => $request->jenis_padi,
                'jenis_bantuan' => $request->jenis_bantuan,
                'tanaman_akhir_bulan_lalu' => $request->tanaman_akhir_bulan_lalu,
                'panen' => $request->panen,
                'tanam' => $request->tanam,
                'puso_rusak' => $request->rusak,
                'tanaman_akhir_bulan_laporan' => $request->tanam_akhir_bulan_laporan,
            ]);

            $this->detailpengairan->create([
                'id_laporan_padi' => $laporanPadiId->id,
                'jenis_pengairan' => $request->pengairan,
                'tanaman_akhir_bulan_lalu' => $request->tanaman_akhir_bulan_lalu_pengairan,
                'panen' => $request->panen_pengairan,
                'tanam' => $request->tanam_pengairan,
                'puso_rusak' => $request->rusak_pengairan,
                'tanaman_akhir_bulan_laporan' => $request->tanam_akhir_bulan_laporan_pengairan,
            ]);

            DB::commit();
            Alert::success('success', 'Data Laporan Padi Berhasil Dibuat!');
            return redirect('/penyuluh/create/laporan_padi')->with('success', 'Data Laporan Padi Berhasil Dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Laporan Padi Gagal DIbuat!' . $e->getMessage());
            return back()->with('error', 'Data Laporan Padi Gagal Dibuat!');
        }
    }

    public function show($id)
    {
        $padi = $this->laporanpadi::findOrFail($id);
        $data = [
            'padi' => $padi->all(),
            'detail_padi' => $this->detailpadi::where('id_laporan_padi', $id)->get(),
            'detail_pengairan' => $this->detailpengairan::where('id_laporan_padi', $id)->get(),
        ];

        return view('penyuluh.pages.laporan_padi.show', $data);
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
