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
        $jenispangan = $this->jenispangan::orderBy('name', 'asc')->get();
        $data = [
            'title' => 'Jenis Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Jenis Pangan',
            'button_create' => 'Tambah Jenis Pangan',
            'jenispangan' => $jenispangan
        ];

        return view('pangan.views.pangan.jenis_pangan.index', $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();

            if ($request->hasFile('gambar')) {
                $image = $request->file('gambar');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/images'); // Simpan di direktori storage/app/public/images
                $image->move($destinationPath, $name);
                $data['gambar'] = 'images/' . $name; // Path yang disimpan di database, perhatikan 'images/' sebagai prefix
            } else {
                $data['gambar'] = 'images/profile.png'; // Default image jika tidak ada gambar yang diunggah
            }

            $this->jenispangan->create($data);
            DB::commit();
            Alert::success('success', 'Data Jenis Pangan Berhasil Ditambahkan!');
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

            $data = $request->all();

            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($jenispangan->gambar && strpos($jenispangan->gambar, 'images') !== false) {
                    $oldImagePath = storage_path('app/public/' . $jenispangan->gambar);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }

                $image = $request->file('gambar');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/images'); // Simpan di direktori storage/app/public/images
                $image->move($destinationPath, $name);
                $data['gambar'] = 'images/' . $name; // Path yang disimpan di database, perhatikan 'images/' sebagai prefix
            }

            $jenispangan->update($data);
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

            // Hapus gambar jika ada
            if ($jenispangan->gambar && strpos($jenispangan->gambar, 'images') !== false) {
                $imagePath = storage_path('app/public/' . $jenispangan->gambar);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

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
