<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pangan\SubJenisPangan\CreateRequest;
use App\Http\Requests\Pangan\SubJenisPangan\UpdateRequest;
use App\Models\Pangan\SubjenisPangan;
use App\Models\Pangan\JenisPangan;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class SubjenisPanganController extends Controller
{
    protected $subjenisPangan;
    protected $jenisPangan;

    public function __construct(SubjenisPangan $subjenisPangan, JenisPangan $jenisPangan)
    {
        $this->subjenisPangan = $subjenisPangan;
        $this->jenisPangan = $jenisPangan;
    }

    public function index()
    {
        $data = [
            'title' => 'Subjenis Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Subjenis Pangan',
            'button_create' => 'Tambah Subjenis Pangan',
        ];

        // Mengambil semua data Subjenis Pangan dengan relasi Jenis Pangan dan mengurutkan berdasarkan Jenis Pangan
        $subjenisPangan = $this->subjenisPangan
            ->join('jenis_pangans', 'subjenis_pangans.jenis_pangan_id', '=', 'jenis_pangans.id')
            ->orderBy('jenis_pangans.name', 'asc')
            ->select('subjenis_pangans.*')
            ->with('jenis_pangan')
            ->get();

        $jenisPangan = JenisPangan::orderBy('name', 'asc')->get();

        return view('pangan.views.pangan.subjenis_pangan.index', compact('subjenisPangan', 'jenisPangan') + $data);
    }


    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $subjenisPangan = $this->subjenisPangan->create([
                'jenis_pangan_id' => $request->jenis_pangan_id,
                'name' => $request->name,
            ]);

            DB::commit();

            Alert::success('Success', 'Data Subjenis Pangan Berhasil Ditambahkan!');
            return back()->with('success', 'Data Subjenis Pangan Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Data Subjenis Pangan Gagal Ditambahkan: ' . $e->getMessage());
            return back()->with('error', 'Data Subjenis Pangan Gagal Ditambahkan!');
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            // Mencari data Subjenis Pangan berdasarkan ID
            $subjenisPangan = $this->subjenisPangan->findOrFail($id);

            // Memperbarui data subjenis pangan
            $subjenisPangan->update([
                'jenis_pangan_id' => $request->jenis_pangan_id,
                'name' => $request->name,
            ]);

            DB::commit();

            Alert::success('Success', 'Data Subjenis Pangan Berhasil Diubah!');
            return back()->with('success', 'Data Subjenis Pangan Berhasil Diubah!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Data Subjenis Pangan Gagal Diubah: ' . $e->getMessage());
            return back()->with('error', 'Data Subjenis Pangan Gagal Diubah!');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $subjenisPangan = $this->subjenisPangan->findOrFail($id);
            $subjenisPangan->delete();

            DB::commit();

            Alert::success('Success', 'Data Subjenis Pangan Berhasil Dihapus!');
            return back()->with('success', 'Data Subjenis Pangan Berhasil Dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Data Subjenis Pangan Gagal Dihapus: ' . $e->getMessage());
            return back()->with('error', 'Data Subjenis Pangan Gagal Dihapus!');
        }
    }
}
