<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pangan\JenisPangan\CreateRequest;
use App\Http\Requests\Pangan\JenisPangan\UpdateRequest;
use App\Models\Pangan\JenisPangan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class JenisPanganController extends Controller
{
    protected $jenispangan;

    public function __construct(JenisPangan $jenispangan)
    {
        $this->jenispangan = $jenispangan;
    }
    public function index()
    {
        $data = [
            'title' => 'Jenis Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Jenis Pangan',
            'button_create' => 'Tambah Jenis Pangan',
        ];
        $jenispangan = $this->jenispangan::all();
        return view('pangan.views.pangan.jenis_pangan.index', compact('jenispangan'), $data);
    }


    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->jenispangan->create($request->all());
            DB::commit();
            Alert::success('success', ' Data Jenis Pangan Berhasil Ditambahkan!');
            return back()->with('success', 'Data Jenis Pangan Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Jenis Pangan Gagal Ditambahkan!' . $e->getMessage());
            return back()->with('error', 'Data Jenis Pangan Gagal Ditambahkan!');
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $jenispangan = $this->jenispangan->find($id);
            $jenispangan->update($request->all());
            DB::commit();
            Alert::success('success', 'Data Jenis Pangan Berhasil Diubah!');
            return back()->with('success', 'Data Jenis Pangan Berhasil Diubah!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Jenis Pangan Gagal Diubah! ' . $e->getMessage());
            return back()->with('error', 'Data Jenis Pangan Gagal Diubah!');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $jenispangan = $this->jenispangan->find($id);
            $jenispangan->delete();
            DB::commit();
            Alert::success('success', 'Data Jenis Pangan Berhasil Dihapus!');
            return back()->with('success', 'Data Jenis Pangan Berhasil Dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Jenis Pangan Gagal Dihapus! ' . $e->getMessage());
            return back()->with('error', 'Data Jenis Pangan Gagal Dihapus!');
        }
    }
}
