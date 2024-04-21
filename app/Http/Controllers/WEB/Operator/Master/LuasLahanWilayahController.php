<?php

namespace App\Http\Controllers\WEB\Operator\Master;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LuasLahanWilayah;
use App\Models\Wilayah\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class LuasLahanWilayahController extends Controller
{
    protected $luas_wilayah;
    protected $kecamatan;

    public function __construct(LuasLahanWilayah $luas_wilayah, Kecamatan $kecamatan)
    {
        $this->luas_wilayah = $luas_wilayah;
        $this->kecamatan = $kecamatan;
    }
    public function index()
    {
        $content = [
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Luas lahan wilayah',
            'title' => 'Luas Lahan Wilayah',
            'button_create' => 'Tambah Luas Lahan Wilayah',
        ];
        $data = [
            'luas_wilayah' => $this->luas_wilayah::all(),
        ];
        return view('operator.pages.master.luas_lahan_wilayah.index', $data, $content);
    }

    public function create()
    {
        $content = [
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'luas lahan wilayah',
            'breadcrumb_active' => 'Tambah luas lahan wilayah',
            'title' => 'Table Luas Lahan Wilayah',
        ];
        $data = [
            'kecamatan' => $this->kecamatan::all(),
        ];
        return view('operator.pages.master.luas_lahan_wilayah.create', $content, $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kecamatan' => 'required',
            'jenis_lahan' => 'required',
            'luas_lahan' => 'required|numeric',
        ]);
        try {
            DB::beginTransaction();
            $this->luas_wilayah->create([
                'kecamatan_id' => $request->kecamatan,
                'jenis_lahan' => $request->jenis_lahan,
                'luas_lahan_wilayah' => $request->luas_lahan,
            ]);
            Alert::success('success', 'Data Luas Lahan Wilayah berhasil ditambahkan');
            DB::commit();
            return redirect('operator/master/luas_lahan_wilayah')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Gagal Menambahkan Data' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan data');
        }
    }

    public function edit($id)
    {
        $content = [
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'luas lahan wilayah',
            'breadcrumb_active' => 'Edit luas lahan wilayah',
            'title' => 'Table Luas Lahan Wilayah',
        ];
        $data = [
            'luas_wilayah' => $this->luas_wilayah::find($id),
            'kecamatan' => $this->kecamatan::all(),
        ];
        return view('operator.pages.master.luas_lahan_wilayah.update', $content, $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kecamatan' => 'required',
            'jenis_lahan' => 'required',
            'luas_lahan' => 'required|numeric',
        ]);
        try {
            DB::beginTransaction();
            $luas_wilayah = $this->luas_wilayah::find($id);
            $luas_wilayah->update([
                'kecamatan_id' => $request->kecamatan,
                'jenis_lahan' => $request->jenis_lahan,
                'luas_lahan_wilayah' => $request->luas_lahan,
            ]);
            DB::commit();
            Alert::success('success', 'Succes Data Berhasil Diubah');
            return redirect('operator/master/luas_lahan_wilayah')->with('success', 'Succes Data Berhasil Diubah');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Error Data Gagal Diubah' . $e->getMessage());
            return back()->with('error', 'error Data Gagal Diubah');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $luas_wilayah = $this->luas_wilayah::find($id);
            $luas_wilayah->delete();
            DB::commit();
            Alert::success('success', 'Succes Data Berhasil Dihapus');
            return back()->with('succes', 'SUccesss Data Berhasil Dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Error Data Gagal Dihapus' . $e->getMessage());
            return back()->with('error', 'error Data Gagal Dihapus');
        }
    }
}
