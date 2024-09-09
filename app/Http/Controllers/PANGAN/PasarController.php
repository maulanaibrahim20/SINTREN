<?php

namespace App\Http\Controllers\PANGAN;

use App\Models\Pasar\Pasar;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Pasar\CreateRequest;
use App\Http\Requests\Pasar\UpdateRequest;

class PasarController extends Controller
{
    protected $pasar;

    public function __construct(Pasar $pasar)
    {
        $this->pasar = $pasar;
    }
    public function index()
    {
        $data = [
            'title' => 'Data Pasar',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Data Pasar',
            'button_create' => 'Tambah Data Pasar',
            // 'users' => $this->pasar::orderBy('created_at', 'asc')->get(),
        ];
        $pasar = $this->pasar::orderBy('name', 'asc')->get();
        return view('operator.pages.master.pasar.index', compact('pasar'), $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->pasar->create($request->all());
            DB::commit();
            Alert::success('success', ' Data Pasar Berhasil Ditambahkan!');
            return back()->with('success', 'Data Pasar Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Pasar Gagal Ditambahkan!' . $e->getMessage());
            return back()->with('error', 'Data Pasar Gagal Ditambahkan!');
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $pasar = $this->pasar->find($id);
            $pasar->update($request->all());
            DB::commit();
            Alert::success('success', 'Data Pasar Berhasil Diubah!');
            return back()->with('success', 'Data Pasar Berhasil Diubah!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Pasar Gagal Diubah! ' . $e->getMessage());
            return back()->with('error', 'Data Pasarr Gagal Diubah!');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $pasar = $this->pasar->find($id);
            $pasar->delete();
            DB::commit();
            Alert::success('success', 'Data Pasar Berhasil Dihapus!');
            return back()->with('success', 'Data Pasar Berhasil Dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Pasar Gagal Dihapus! ' . $e->getMessage());
            return back()->with('error', 'Data Pasar Dihapus!');
        }
    }
}
