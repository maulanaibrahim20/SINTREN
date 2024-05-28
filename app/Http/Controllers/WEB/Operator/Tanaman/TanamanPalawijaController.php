<?php

namespace App\Http\Controllers\WEB\Operator\Tanaman;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\Tanaman\Palawija\CreateRequest;
use App\Http\Requests\Operator\Tanaman\Palawija\UpdateRequest;
use App\Models\Operator\TanamanPalawija;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;


class TanamanPalawijaController extends Controller
{
    protected $palawija;

    public function __construct(TanamanPalawija $palawija,)
    {
        $this->palawija = $palawija;
    }
    public function index()
    {
        $data = [
            'title' => 'Tanaman Palawija',
            'breadcrumb' => 'Dashbord',
            'breadcrumb_active' => 'Tanaman Palawija',
            'button_create' => 'Tambah Tanaman Palawija',
            'palawija' => $this->palawija::all(),
        ];
        return view('operator.pages.tanaman.palawija.index', $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->palawija->create($request->all());
            DB::commit();
            return back()->with('success', 'Data Palawija Berhasil Dibuat!');
        } catch (\Exception $th) {
            DB::rollback();
            return back()->with('error', 'Data Palawija Gagal Dibuat!' . $th->getMessage());
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $palawija = $this->palawija->find($id);
            $palawija->update($request->all());
            DB::commit();
            return back()->with('success', 'Data Palawija Berhasil Diubah!');
        } catch (\Exception $th) {
            DB::rollback();
            return back()->with('error', 'Data Palawija Gagal Diubah!' . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $palawija = $this->palawija->find($id);
            $palawija->delete();
            DB::commit();
            Alert::success('success', 'Data Palawija Berhasil Dihapus!');
            return redirect('/operator/tanaman/palawija')->with('success', 'Data Palawija Berhasil Dihapus!');
        } catch (\Exception $th) {
            DB::rollback();

            Alert::error('Error', 'Data Palawija Gagal Dihapus!' . $th->getMessage());
            return back()->with('error', 'Data Palawija Gagal Dihapus!' . $th->getMessage());
        }
    }
}
