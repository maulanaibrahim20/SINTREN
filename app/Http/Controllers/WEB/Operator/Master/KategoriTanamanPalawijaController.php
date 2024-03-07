<?php

namespace App\Http\Controllers\WEB\Operator\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\Tanaman\KategoriTanaman\CreateRequest;
use App\Http\Requests\Operator\Tanaman\KategoriTanaman\UpdateRequest;
use App\Models\Operator\KategoriTanamanPalawija;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;


class KategoriTanamanPalawijaController extends Controller
{
    protected $kategori;

    public function __construct(KategoriTanamanPalawija $kategori)
    {
        $this->kategori = $kategori;
    }
    public function index()
    {
        $data = [
            'title' => 'Kategori Tanaman Palawija',
        ];
        $tanaman = $this->kategori::all();
        return view('operator.pages.master.kategori_tanaman_palawija.index', compact('tanaman'), $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('image_kategori_palawija'), $imageName);

            $data = $request->all();
            $data['image'] = '/image_kategori_palawija/' . $imageName;

            $this->kategori->create($data);
            DB::commit();
            Alert::success('success', ' Data Kategori Palawija Berhasil Ditambahkan!');
            return back()->with('success', 'Data Kategori Palawija Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Kategori Palawija Gagal Ditambahkan!' . $e->getMessage());
            return back()->with('error', 'Data Kategori Palawija Gagal Ditambahkan!');
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $kategori = $this->kategori->find($id);

            $data = $request->all();

            if ($request->hasFile('image')) {
                $oldImagePath = public_path($kategori->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('image_kategori_palawija'), $imageName);
                $data['image'] = '/image_kategori_palawija/' . $imageName;
            }
            $kategori->update($data);

            DB::commit();
            Alert::success('success', 'Data Kategori Palawija Berhasil Diubah!');
            return back()->with('success', 'Data Kategori Palawija Berhasil Diubah!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Kategori Palawija Gagal Diubah! ' . $e->getMessage());
            return back()->with('error', 'Data Kategori Palawija Gagal Diubah!');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $kategori = $this->kategori->find($id);
            $kategori->delete();
            DB::commit();
            Alert::success('success', 'Data Kategori Palawija Berhasil Dihapus!');
            return back()->with('success', 'Data Kategori Palawija Berhasil Dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Kategori Palawija Gagal Dihapus! ' . $e->getMessage());
            return back()->with('error', 'Data Kategori Palawija Gagal Dihapus!');
        }
    }
}
