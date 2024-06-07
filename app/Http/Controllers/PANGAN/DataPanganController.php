<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pangan\KategoriPangan;
use App\Models\Pangan\LaporanPangan;
use App\Models\Pasar\Pasar;
use App\Models\User;

class DataPanganController extends Controller
{
    protected $laporanpangan;
    protected $pasar;
    protected $kategoripangan;
    protected $user;

    public function __construct(
        LaporanPangan $laporanpangan,
        Pasar $pasar,
        KategoriPangan $kategoripangan,
        User $user,
        ) {
            $this->laporanpangan = $laporanpangan;
            $this->pasar = $pasar;
            $this->kategoripangan = $kategoripangan;
            $this->user = $user;
        }

        public function index(Request $request)
        {
            $query = $this->laporanpangan::with('pasar')->where('user_id', Auth::user()->id);

            if ($request->has('start_date') && $request->start_date) {
                $query->where('date', '>=', $request->start_date);
            }

            if ($request->has('end_date') && $request->end_date) {
                $query->where('date', '<=', $request->end_date);
            }

            $datapangan = $query->get();

            // Ambil data laporan pangan dan urutkan berdasarkan status
            $datapangan = LaporanPangan::orderBy('status', 'asc')
            ->orderBy('date', 'desc')
            ->get();

            $data = [
                'title' => 'Data Stok Pangan',
                'breadcrumb' => 'Dashboard',
                'breadcrumb_active' => 'Data Stok Pangan',
                'button_create' => 'Tambah Data Stok Pangan',
                'datapangan' => $datapangan,
            ];

            return view('pangan.views.pangan.data_pangan.index', $data);
        }

        // public function index()
        // {
            //     $data = [
                //         'title' => 'Data Stok Pangan',
                //         'breadcrumb' => 'Dashboard',
                //         'breadcrumb_active' => 'Data Stok Pangan',
                //         'button_create' => 'Tambah Data Stok Pangan',
                //         'datapangan' => $this->laporanpangan::with('pasar')->where('user_id', Auth::user()->id)->get(),

                //     ];
                //     return view('pangan.views.pangan.laporan_pangan.index', $data);
                // }

                /**
                * Show the form for creating a new resource.
                */
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

                    return view('pangan.views.pangan.data_pangan.create', $data);
                }


                /**
                * Store a newly created resource in storage.
                */
                public function store(Request $request)
                {
                    // dd($request->kebutuhan);
                    try {
                        DB::beginTransaction();
                        // Bersihkan input harga dari pemisah ribuan dan ganti koma dengan titik
                        // $harga = str_replace('.', '', $request->harga);
                        // $harga = str_replace(',', '.', $harga);
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
                        // return redirect()->route('data_pangan.index')->with('success', 'Data Laporan Pangan Berhasil Dikirim!');
                        return redirect()->back()->with('success', 'Data Stok Pangan Berhasil Dikirim!');
                    } catch (\Exception $e) {
                        DB::rollback();
                        return back()->with('error', 'Error Data Stok Pangan Gagal Dikirim! ' . $e->getMessage());
                    }
                }


                /**
                * Display the specified resource.
                */
                public function show(string $id)
                {
                    $laporanpangan = $this->laporanpangan::findOrFail($id);
                    $data = [
                        'laporanpangan' => $laporanpangan,
                        // 'title' => 'View Data Stok Pangan',
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
                        'kategoripangan' => $this->kategoripangan::all(),
                        'pasar' => $this->pasar::all(),
                        'datapangan' => $this->laporanpangan::where('user_id', Auth::user()->id)->get(),
                    ];

                    return view('pangan.views.pangan.data_pangan.update', $data);
                }


                public function update(Request $request, string $id)
                {
                    // dd($request->pasar_id);
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
