<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pangan\JenisPangan;
use App\Models\Pangan\LaporanPangan;
use App\Models\Pasar\Pasar;
use App\Models\User;

class DataPanganController extends Controller
{
    protected $laporanpangan;
    protected $pasar;
    protected $jenispangan;
    protected $user;

    public function __construct(
        LaporanPangan $laporanpangan,
        Pasar $pasar,
        JenisPangan $jenispangan,
        User $user
    ) {
        $this->laporanpangan = $laporanpangan;
        $this->pasar = $pasar;
        $this->jenispangan = $jenispangan;
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $query = $this->laporanpangan::with('pasar')
            ->where('user_id', Auth::user()->id)
            ->where('status', true); // Tambahkan kondisi ini untuk hanya mengambil laporan yang terkirim

        if ($request->has('start_date') && $request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        // Ambil data laporan pangan dan urutkan berdasarkan status dan tanggal
        $datapangan = $query->orderBy('status', 'asc')
            ->orderBy('date', 'desc')
            ->get();

        $data = [
            'title' => 'Data Stok Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Data Stok Pangan',
            'button_create' => 'Tambah Data Stok Pangan',
            // 'datapangan' => $this->laporanpangan::with('pasar')
            // ->where('user_id', Auth::user()->id)
            // ->where('status', true)->get(),
            'datapangan' => $this->laporanpangan::where('status', true)->get(),
        ];

        // dd($data);

        return view('pangan.views.pangan.data_pangan.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Stok Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Stok Pangan',
            'breadcrumb_active' => 'Tambah Data Stok Pangan',
            'jenispangan' => $this->jenispangan::all(),
            'pasar' => $this->pasar::all(),
            'datapangan' => $this->laporanpangan::where('user_id', Auth::user()->id)->get(),
        ];

        return view('pangan.views.pangan.data_pangan.create', $data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->laporanpangan->create($request->all() + [
                'user_id' => Auth::user()->id,
                'pasar_id' => $request->pasar_id,
                'jenis_pangan_id' => $request->jenis_pangan_id,
                'name' => $request->name,
                'kebutuhan' => $request->kebutuhan,
                'ketersediaan' => $request->ketersediaan,
                'neraca' => $request->neraca,
                'harga' => $request->harga,
                'date' => $request->date,
            ]);
            DB::commit();

            return redirect('/pangan/create/data_pangan')->with('success', 'Data Stok Pangan Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error Data Stok Pangan Gagal Ditambahkan!' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $laporanpangan = $this->laporanpangan::findOrFail($id);
        $data = [
            'laporanpangan' => $laporanpangan,
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Stok Pangan',
            'breadcrumb_active' => 'View Data Stok Pangan',
        ];
        return view('pangan.views.pangan.data_pangan.show', $data);
    }

    public function edit(string $id)
    {
        $editPangan = $this->laporanpangan::findOrFail($id);
        $data = [
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Stok Pangan',
            'breadcrumb_active' => 'Edit Data Stok Pangan',
            'editPangan' => $editPangan,
            'jenispangan' => $this->jenispangan::all(),
            'pasar' => $this->pasar::all(),
            'datapangan' => $this->laporanpangan::where('user_id', Auth::user()->id)->get(),
        ];

        return view('pangan.views.pangan.data_pangan.update', $data);
    }



    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $laporanpangan = $this->laporanpangan::findOrFail($id);
            $laporanpangan->update([
                'user_id' => Auth::user()->id,
                'pasar_id' => $request->pasar_id,
                'jenis_pangan_id' => $request->jenis_pangan_id,
                'name' => $request->name,
                'kebutuhan' => $request->kebutuhan,
                'ketersediaan' => $request->ketersediaan,
                'neraca' => $request->neraca,
                'harga' => $request->harga,
                'date' => $request->date,
            ]);

            DB::commit();
            return redirect('/pangan/create/data_pangan')->with('success', 'Data Stok Pangan Berhasil Diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error Data Stok Pangan Gagal Diperbarui! ' . $e->getMessage());
        }
    }


    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $laporanpangan = $this->laporanpangan::findOrFail($id);
            $laporanpangan->delete();

            DB::commit();
            return redirect('/pangan/create/data_pangan')->with('success', 'Data Stok Pangan Berhasil Dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error Data Stok Pangan Gagal Dihapus! ' . $e->getMessage());
        }
    }
}
