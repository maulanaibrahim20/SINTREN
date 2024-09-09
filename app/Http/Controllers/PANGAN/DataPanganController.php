<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Pangan\LaporanPangan;
use App\Models\Pasar\Pasar;
use App\Models\Pangan\JenisPangan;
use App\Models\Pangan\SubjenisPangan;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class DataPanganController extends Controller
{
    protected $laporanpangan;
    protected $pasar;
    protected $jenispangan;
    protected $subjenisPangan;
    protected $user;

    public function __construct(
        LaporanPangan $laporanpangan,
        Pasar $pasar,
        JenisPangan $jenispangan,
        SubjenisPangan $subjenisPangan,
        User $user
        ) {
            $this->laporanpangan = $laporanpangan;
            $this->pasar = $pasar;
            $this->jenispangan = $jenispangan;
            $this->subjenisPangan = $subjenisPangan;
            $this->user = $user;
        }

        public function index(Request $request)
        {
        $query = LaporanPangan::with(['pasar', 'jenis_pangan', 'subjenis_pangan'])
            ->where('status', '1') // Filter hanya data dengan status '1' (terkirim)
            ->whereHas('jenis_pangan') // Pastikan hanya data dengan jenis_pangan yang ada
            ->orderBy('date', 'desc');

        // Filter berdasarkan tanggal mulai dan akhir jika tersedia
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->get('start_date'), $request->get('end_date')]);
        }

        // Filter berdasarkan pasar jika dipilih
        if ($request->has('pasar_id') && $request->pasar_id) {
            $query->where('pasar_id', $request->pasar_id);
        }

        // Ambil data laporan pangan
        $datapangan = $query->get();

        // Ambil daftar pasar untuk filter
        $pasarList = Pasar::orderBy('name', 'asc')->get();

        $data = [
            'title' => 'Data Stok Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Data Stok Pangan',
            'button_create' => 'Tambah Data Stok Pangan',
            'datapangan' => $datapangan,
            'pasarList' => $pasarList,
        ];

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
                $this->laporanpangan->create([
                    'user_id' => Auth::user()->id,
                    'pasar_id' => $request->pasar_id,
                    'jenis_pangan_id' => $request->jenis_pangan_id,
                    'subjenis_pangan_id' => $request->subjenis_pangan_id,
                    'stok' => $request->stok,
                    'harga' => $request->harga,
                    'date' => $request->date,
                    'status' => true,
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
                'subjenispangan' => $this->subjenisPangan::all(),
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

                // Update hanya untuk field yang dapat diubah
                $laporanpangan->update([
                    'stok' => $request->stok,
                    'harga' => $request->harga,
                    // 'subjenis_pangan_id' => $request->subjenis_pangan_id,
                    // 'jenis_pangan_id' => $request->jenis_pangan_id,
                    'status' => true, // Pastikan status diset ke true saat mengupdate data
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
