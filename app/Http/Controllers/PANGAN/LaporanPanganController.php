<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Export\LaporanPanganExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Pangan\KategoriPangan;
use App\Models\Pangan\LaporanPangan;
use App\Models\Pasar\Pasar;
use App\Models\User;

class LaporanPanganController extends Controller
{
    protected $laporanpangan;
    protected $pasar;
    protected $kategoripangan;
    protected $user;

    public function __construct(
        LaporanPangan $laporanpangan,
        Pasar $pasar,
        KategoriPangan $kategoripangan,
        User $user
    ) {
        $this->laporanpangan = $laporanpangan;
        $this->pasar = $pasar;
        $this->kategoripangan = $kategoripangan;
        $this->user = $user;
    }

    public function index(Request $request)
    {
        // Mulai query dengan data yang memiliki status terkirim (status = 1)
        $query = $this->laporanpangan::with('pasar')
            ->where('user_id', Auth::user()->id)
            ->where('status', 1); // Filter data dengan status terkirim

        if ($request->has('start_date') && $request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        $datapangan = $query->get();

        $data = [
            'title' => 'Laporan Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Laporan Pangan',
            'button_create' => 'Tambah Data Stok Pangan',
            'datapangan' => $datapangan,
        ];

        return view('pangan.views.pangan.laporan_pangan.index', $data);
    }

    public function export(Request $request)
    {
        $query = $this->laporanpangan::with('pasar')
            ->where('user_id', Auth::user()->id)
            ->where('status', 1); // Filter data dengan status terkirim

        if ($request->has('start_date') && $request->start_date) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('date', '<=', $request->end_date);
        }

        $datapangan = $query->get();

        // Proses ekspor ke file Excel
        return Excel::download(new LaporanPanganExport($datapangan), 'laporan_pangan.xlsx');
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Stok Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Stok Pangan',
            'breadcrumb_active' => 'Tambah Data Stok Pangan',
            'kategoripangan' => $this->kategoripangan::all(),
            'pasar' => $this->pasar::all(),
            'datapangan' => $this->laporanpangan::where('user_id', Auth::user()->id)->get(),
        ];

        return view('pangan.views.pangan.laporan_pangan.create', $data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->laporanpangan->create($request->all() +[
                'user_id' => Auth::user()->id,
                'pasar_id' => $request->pasar_id,
                'kategori_pangan_id' => $request->kategori_pangan_id,
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

    public function kirimkan(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $laporanpangan = LaporanPangan::findOrFail($id);
            $laporanpangan->update([
                'status' => true,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data Stok Pangan Berhasil Dikirim!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error Data Stok Pangan Gagal Dikirim! ' . $e->getMessage());
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
        return view('pangan.views.pangan.laporan_pangan.show', $data);
    }

    public function edit(string $id)
    {
        $editPangan = $this->laporanpangan::findOrFail($id);
        $data = [
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Stok Pangan',
            'breadcrumb_active' => 'Edit Data Stok Pangan',
            'editPangan' => $editPangan,
            'kategoripangan' => $this->kategoripangan::all(),
            'pasar' => $this->pasar::all(),
            'datapangan' => $this->laporanpangan::where('user_id', Auth::user()->id)->get(),
        ];

        return view('pangan.views.pangan.laporan_pangan.update', $data);
    }

    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $laporanpangan = $this->laporanpangan::findOrFail($id);
            $laporanpangan->update([
                'user_id' => Auth::user()->id,
                'pasar_id' => $request->pasar_id,
                'kategori_pangan_id' => $request->kategori_pangan_id,
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
