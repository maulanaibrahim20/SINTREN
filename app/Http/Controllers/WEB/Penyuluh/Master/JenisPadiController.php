<?php

namespace App\Http\Controllers\WEB\Penyuluh\Master;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\Master\JenisPadi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class JenisPadiController extends Controller
{

    protected $jenis_padi;

    public function __construct(JenisPadi $jenis_padi)
    {
        $this->jenis_padi = $jenis_padi;
    }
    public function index()
    {
        $padi = $this->jenis_padi::where('created_by', Auth::user()->name)->orderBy('created_by', 'asc')->get();
        return view('penyuluh.pages.master.jenis_padi.index', compact('padi'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $UserId = Auth::user();
            $this->jenis_padi->create([
                'nama_padi' => $request->name,
                'user_id' => $UserId->id,
                'created_by' => $UserId->name,
            ]);

            DB::commit();
            Alert::success('success', 'Data Jenis Padi Berhasil Ditambahkan!');
            return back()->with('success', 'Data Padi Berhasil Ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Data Jenis Padi Gagal Ditambahkan!' . $e->getMessage());
            return back()->with('error', 'Data Padi Gagal Ditambahkan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $padi = $this->jenis_padi::findOrFail($id);
            $padi->update([
                'nama_padi' => $request->name,
            ]);
            DB::commit();
            Alert::success('success', 'Data Jenis Padi Berhasil Diubah');
            return back()->with('success', 'Data Jenis Padi Berhasil Diubah');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Error', 'Data Jenis Padi Gagal Ditambahkan!' . $e->getMessage());
            return back()->with('error', 'Data Jenis Padi Gagall Ditambahkan');
        }
    }
}
