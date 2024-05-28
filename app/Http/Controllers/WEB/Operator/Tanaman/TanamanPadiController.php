<?php

namespace App\Http\Controllers\WEB\Operator\Tanaman;

use App\Http\Controllers\Controller;
use App\Models\Operator\TanamanPadi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class TanamanPadiController extends Controller
{
    protected $padi;

    public function __construct(TanamanPadi $padi)
    {
        $this->padi = $padi;
    }
    public function index()
    {
        $data = [
            'title' => 'Tanaman Padi',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Tanaman Padi',
            'button_create' => 'Tambah Data Padi',
        ];
        $padi = $this->padi::all();
        return view('operator.pages.tanaman.padi.index', compact('padi'), $data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->padi->create([
                'name' => $request->name,
            ]);
            DB::commit();
            return back()->with('success', 'Success Data Padi Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error Data Padi Gagal Ditambahkan!');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $padi = $this->padi->findOrFail($id);
            $padi->update([
                'name' => $request->name,
            ]);
            DB::commit();
            return back()->with('success', 'Success Data Padi Berhasil Diubah!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error Data Padi Gagal Diubah!');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $padi = $this->padi->findOrFail($id);
            $padi->delete();
            DB::commit();
            return back()->with('success', 'Success Data Padi Berhasil Dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error Data Padi Gagal Dihapus!');
        }
    }
}
