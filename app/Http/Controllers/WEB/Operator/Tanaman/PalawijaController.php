<?php

namespace App\Http\Controllers\WEB\Operator\Tanaman;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\Tanaman\Palawija\CreateRequest;
use App\Http\Requests\Operator\Tanaman\Palawija\UpdateRequest;
use App\Models\Operator\KategoriTanamanPalawija;
use App\Models\Operator\Palawija;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;


class PalawijaController extends Controller
{
    protected $palawija;
    protected $kategoritanaman;

    public function __construct(Palawija $palawija, KategoriTanamanPalawija $kategoritanaman)
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
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('image_palawija'), $imageName);

            $data = $request->all();
            $data['gambar'] = '/image_palawija/' . $imageName;

            $this->palawija->create($data);

            DB::commit();
            Alert::success('success', 'Data Palawija Berhasil Dibuat!');
            return redirect('/operator/tanaman/palawija')->with('success', 'Data Palawija Berhasil Dibuat!');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $th) {
            DB::rollback();
            Alert::error('Error', 'Data Palawija Gagal Dibuat!' . $th->getMessage());
            return back()->with('error', 'Data Palawija Gagal Dibuat!' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $kategori = $this->kategoritanaman::all();
        $palawija = $this->palawija::find($id);
        return view('operator.pages.tanaman.palawija.update', compact('palawija', 'kategori'));
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();

            $palawija = $this->palawija->find($id);

            if ($request->hasFile('image')) {
                if ($palawija->gambar && file_exists(public_path($palawija->gambar))) {
                    unlink(public_path($palawija->gambar));
                }
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('image_palawija'), $imageName);
                $data['gambar'] = '/image_palawija/' . $imageName;
            }

            $palawija->update($data);

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
            $this->palawija::find($id)->delete();
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
