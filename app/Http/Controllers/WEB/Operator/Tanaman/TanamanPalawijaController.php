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
        $kategori = $this->kategoritanaman::all();
        $palawija = $this->palawija::find($id);
        return view('operator.pages.tanaman.palawija.update', compact('palawija', 'kategori'));
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $palawija = $this->palawija->find($id);
            $dataToUpdate = [
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description,
                'updated_at' => Carbon::now(),
            ];

            if ($request->hasFile('gambar')) {
                if (File::exists(public_path($palawija->gambar))) {
                    File::delete(public_path($palawija->gambar));
                }

                $imageName = time() . '.' . $request->gambar->extension();
                $request->gambar->move(public_path('image_palawija'), $imageName);
                $palawija->gambar = '/image_palawija/' . $imageName;
            }

            $palawija->update($dataToUpdate);

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

            $imagePath = public_path('image_palawija/' . basename($palawija->gambar));

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

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
