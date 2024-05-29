<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pangan\KategoriPangan\CreateRequest;
use App\Http\Requests\Pangan\KategoriPangan\UpdateRequest;
use App\Models\Pangan\KategoriPangan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class KategoriPanganController extends Controller
{
    protected $kategoripangan;

    public function __construct(KategoriPangan $kategoripangan)
    {
        $this->kategoripangan = $kategoripangan;
    }
    public function index()
    {
        $data = [
            'title' => 'Jenis Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Jenis Pangan',
            'button_create' => 'Tambah Jenis Pangan',
        ];
        $kategoripangan = $this->kategoripangan::all();
        return view('pangan.views.pangan.kategori_pangan.index', compact('kategoripangan'), $data);
    }


    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->kategoripangan->create($request->all());
            DB::commit();
            Alert::success('success', ' Data Kategori Pangan Berhasil Ditambahkan!');
            return back()->with('success', 'Data Kategori Pangan Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Kategori Pangan Gagal Ditambahkan!' . $e->getMessage());
            return back()->with('error', 'Data Kategori Pangan Gagal Ditambahkan!');
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $kategoripangan = $this->kategoripangan->find($id);
            $kategoripangan->update($request->all());
            DB::commit();
            Alert::success('success', 'Data Kategori Pangan Berhasil Diubah!');
            return back()->with('success', 'Data Kategori Pangan Berhasil Diubah!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Kategori Pangan Gagal Diubah! ' . $e->getMessage());
            return back()->with('error', 'Data Kategori Pangan Gagal Diubah!');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $kategoripangan = $this->kategoripangan->find($id);
            $kategoripangan->delete();
            DB::commit();
            Alert::success('success', 'Data Kategori Pangan Berhasil Dihapus!');
            return back()->with('success', 'Data Kategori Pangan Berhasil Dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Kategori Pangan Gagal Dihapus! ' . $e->getMessage());
            return back()->with('error', 'Data Kategori Pangan Gagal Dihapus!');
        }
    }
}
