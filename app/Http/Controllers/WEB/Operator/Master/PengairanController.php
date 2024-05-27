<?php

namespace App\Http\Controllers\WEB\Operator\Master;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\Pengairan;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class PengairanController extends Controller
{
    protected $pengairan;

    public function __construct(Pengairan $pengairan)
    {
        $this->pengairan = $pengairan;
    }

    public function index()
    {
        $data = [
            'title' => 'Pengairan'
        ];
        $pengairan = $this->pengairan::all();
        return view('operator.pages.master.pengairan.index', $data, compact('pengairan'));
    }

    public function store(Request $request)
    {
        try {
            DB::begintransaction();
            $this->pengairan->create([
                'name' => $request->name,
            ]);
            DB::commit();
            Alert::success('success', 'Data Pengairan Berhasil Dibuat!');
            return back()->with('success', 'Data Pengairan Berhasil Dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Pengairan Gagal Ditambahkan!' . $e->getMessage());
            return back()->with('error', 'Data Pengairan Gagal Ditambahkan!');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::begintransaction();
            $pengairan = $this->pengairan->find($id);
            $pengairan->update([
                'name' => $request->name,
            ]);
            DB::commit();
            Alert::success('success', 'Data Pengairan Berhasil Diubah!');
            return back()->with('success', 'Data Pengairan Berhasil Diubah!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Pengairan Gagal Diubah!' . $e->getMessage());
            return back()->with('error', 'Data Pengairan Gagal Diubah!');
        }
    }

    public function destroy($id)
    {
        try {
            DB::begintransaction();
            $pengairan = $this->pengairan->find($id);
            $pengairan->delete();
            DB::commit();
            Alert::success('success', 'Data Pengairan Berhasil Dihapus!');
            return back()->with('success', 'Data Pengairan Berhasil Dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Pengairan Gagal Dihapus!' . $e->getMessage());
            return back()->with('error', 'Data Pengairan Gagal Dihapus!');
        }
    }
}
