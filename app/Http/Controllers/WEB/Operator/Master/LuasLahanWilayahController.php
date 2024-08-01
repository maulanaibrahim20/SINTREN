<?php

namespace App\Http\Controllers\WEB\Operator\Master;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LuasLahanWilayah;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class LuasLahanWilayahController extends Controller
{
    protected $luas_wilayah, $kecamatan, $desa;

    public function __construct(LuasLahanWilayah $luas_wilayah, Kecamatan $kecamatan, Desa $desa)
    {
        $this->luas_wilayah = $luas_wilayah;
        $this->kecamatan = $kecamatan;
        $this->desa = $desa;
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
            'luas_wilayah' => $this->luas_wilayah::orderBy('created_at', 'desc')->get(),
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
            'kecamatan' => $this->kecamatan::orderBy('name', 'asc')->get(),
        ];
        return view('operator.pages.master.luas_lahan_wilayah.create', $content, $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'desa' => 'required',
            'kecamatan' => 'required',
            'lahan_sawah' => 'required',
            'lahan_non_sawah' => 'required|numeric',
        ]);

        $exists = $this->luas_wilayah->where('desa_id', $request->desa)
            ->where('kecamatan_id', $request->kecamatan)
            ->exists();

        if ($exists) {
            return back()->withErrors(['desa' => 'Desa ini sudah ada dalam kecamatan yang dipilih.']);
        }

        try {
            DB::beginTransaction();
            $this->luas_wilayah->create([
                'kecamatan_id' => $request->kecamatan,
                'desa_id' => $request->desa,
                'lahan_sawah' => $request->lahan_sawah,
                'lahan_non_sawah' => $request->lahan_non_sawah,
            ]);
            Alert::success('success', 'Data Luas Lahan Wilayah berhasil ditambahkan');
            DB::commit();
            return redirect('/operator/master/luas_lahan_wilayah')->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Gagal Menambahkan Data' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan data');
        }
    }

    public function edit($id)
    {
        $luas_wilayah = $this->luas_wilayah::findOrFail($id);
        $data = [
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'luas lahan wilayah',
            'breadcrumb_active' => 'Edit luas lahan wilayah',
            'title' => 'Table Luas Lahan Wilayah',
            'kecamatan' => $this->kecamatan::all(),
            'desa' => $this->desa::all(),
            'selected_kec' => $luas_wilayah->kecamatan_id,
            'selected_des' => $luas_wilayah->desa_id,
        ];
        return view('operator.pages.master.luas_lahan_wilayah.update', $data, compact('luas_wilayah'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'desa' => 'required',
            'kecamatan' => 'required',
            'lahan_sawah' => 'required',
            'lahan_non_sawah' => 'required|numeric',
        ]);
        try {
            DB::beginTransaction();
            $luas_wilayah = $this->luas_wilayah::find($id);
            $luas_wilayah->update([
                'desa_id' => $request->desa,
                'kecamatan_id' => $request->kecamatan,
                'lahan_sawah' => $request->lahan_sawah,
                'lahan_non_sawah' => $request->lahan_non_sawah,
            ]);
            DB::commit();
            Alert::success('success', 'Succes Data Wilayah Berhasil Diubah');
            return redirect('operator/master/luas_lahan_wilayah')->with('success', 'Succes Data Berhasil Diubah');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Error Data Gagal Diubah' . $e->getMessage());
            return back()->with('error', 'error Data Wilayah Gagal Diubah');
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
