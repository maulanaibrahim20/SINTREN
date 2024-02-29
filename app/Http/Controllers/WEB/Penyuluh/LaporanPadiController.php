<?php

namespace App\Http\Controllers\WEB\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\Master\JenisPadi;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanPadiController extends Controller
{

    protected $jenis_padi;
    protected $kecamatan;
    protected $desa;

    public function __construct(JenisPadi $jenis_padi, Desa $desa, Kecamatan $kecamatan)
    {
        $this->jenis_padi = $jenis_padi;
        $this->kecamatan = $kecamatan;
        $this->desa = $desa;
    }
    public function index()
    {
        return view('penyuluh.pages.laporan_padi.index');
    }

    public function create()
    {
        $desa = $this->desa::all();
        $kecamatan = $this->kecamatan::all();
        $jenis_padi = $this->jenis_padi::where('created_by', Auth::user()->name)
            ->orderBy('created_by', 'asc')
            ->get();
        return view('penyuluh.pages.laporan_padi.create', compact('jenis_padi', 'desa', 'kecamatan'));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

    public function getDesa(Request $request)
    {
        $id = $request->kecamatan;
        $desa = Desa::where('district_id', $id)->get();

        foreach ($desa as $data) {
            echo "<option value='" . $data['id'] . "'>" . $data['name'] . "</option>";
        }
    }
}
