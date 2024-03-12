<?php

namespace App\Http\Controllers\WEB\Operator\Tanaman;

use App\Http\Controllers\Controller;
use App\Models\Operator\Padi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class PadiController extends Controller
{
    protected $padi;

    public function __construct(Padi $padi)
    {
        $this->padi = $padi;
    }
    public function index()
    {
        $data = [
            'title' => 'Tanaman Padi',
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
                'category' => $request->kategori,
                'description' => $request->description,
            ]);
            DB::commit();
            Alert::success('success', 'success Data Padi Berhasil Ditambahkan!');
            return back()->with('success', 'Success Data Padi Berhasil Ditambahkan!');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Padi Gagal Ditambahkan!' . $e->getMessage());
            return back()->with('error', 'Error Data Padi Gagal Ditambahkan!');
        }
    }

    //please make update function here
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $padi = $this->padi->findOrFail($id);
            $padi->update([
                'name' => $request->name,
                'category' => $request->kategori,
                'description' => $request->description,
            ]);
            DB::commit();
            Alert::success('success', 'success Data Padi Berhasil Diubah!');
            return back()->with('success', 'Success Data Padi Berhasil Diubah!');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Padi Gagal Diubah!' . $e->getMessage());
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
            Alert::success('success', 'success Data Padi Berhasil Dihapus!');
            return back()->with('success', 'Success Data Padi Berhasil Dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Padi Gagal Dihapus!' . $e->getMessage());
            return back()->with('error', 'Error Data Padi Gagal Dihapus!');
        }
    }
}
