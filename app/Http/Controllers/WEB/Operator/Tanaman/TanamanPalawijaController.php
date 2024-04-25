<?php

namespace App\Http\Controllers\WEB\Operator\Tanaman;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\Tanaman\Palawija\CreateRequest;
use App\Http\Requests\Operator\Tanaman\Palawija\UpdateRequest;
use App\Models\Operator\KategoriTanamanPalawija;
use App\Models\Operator\TanamanPalawija;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;


class TanamanPalawijaController extends Controller
{
    protected $palawija;
    protected $kategoritanaman;

    public function __construct(TanamanPalawija $palawija, KategoriTanamanPalawija $kategoritanaman)
    {
        $this->palawija = $palawija;
        $this->kategoritanaman = $kategoritanaman;
    }
    public function index()
    {
        $data = [
            'title' => 'Tanaman Palawija',
        ];
        $palawija = $this->palawija::all();
        return view('operator.pages.tanaman.palawija.index', $data, compact('palawija'));
    }

    public function create()
    {
        $kategori = $this->kategoritanaman::all();
        return view('operator.pages.tanaman.palawija.create', compact('kategori'));
    }

    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->palawija->create($request->all());
            DB::commit();
            Alert::success('success', 'Data Palawija Berhasil Dibuat!');
            return redirect('/operator/tanaman/palawija')->with('success', 'Data Palawija Berhasil Dibuat!');
        } catch (\Exception $th) {
            DB::rollback();
            Alert::error('Error', 'Data Palawija Gagal Dibuat!' . $th->getMessage());
            return back()->with('error', 'Data Palawija Gagal Dibuat!' . $th->getMessage());
        }
    }

    public function show($id)
    {
        $data = [
            'palawija' => $this->palawija::find($id),
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Tanaman Palawija',
            'breadcrumb_active' => 'Detail Tanaman Palawija',
        ];

        return view('operator.pages.tanaman.palawija.show', $data);
    }

    public function edit($id)
    {
        $data = [
            'kategori' => $this->kategoritanaman::all(),
            'palawija' => $this->palawija::find($id),
        ];

        return view('operator.pages.tanaman.palawija.update', $data);
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $palawija = $this->palawija->find($id);
            $palawija->update($request->all());
            DB::commit();
            Alert::success('success', 'Data Palawija Berhasil Diubah!');
            return redirect('/operator/tanaman/palawija')->with('success', 'Data Palawija Berhasil Diubah!');
        } catch (\Exception $th) {
            DB::rollback();
            Alert::error('Error', 'Data Palawija Gagal Diubah!' . $th->getMessage());
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
