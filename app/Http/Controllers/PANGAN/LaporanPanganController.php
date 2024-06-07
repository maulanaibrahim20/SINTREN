<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportLaporanPangan;
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
            // 'button_create' => 'Tambah Data Stok Pangan',
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
        return Excel::download(new ExportLaporanPangan($datapangan), 'laporan_pangan.xlsx');
    }

    public function kirimkan(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $laporanpangan = LaporanPangan::findOrFail($id);
            $laporanpangan->status = 1; // Ubah status menjadi 1 (Terkirim)
            $laporanpangan->save();

            DB::commit();
            return redirect()->back()->with('success', 'Data Stok Pangan Berhasil Dikirim!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error Data Stok Pangan Gagal Dikirim! ' . $e->getMessage());
        }
    }
}
