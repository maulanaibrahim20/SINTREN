<?php

namespace App\Http\Controllers\WEB\Operator\Master;

use App\Http\Controllers\Controller;
use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kecamatan;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Data Wilayah Kecamatan Di Kabupaten Indramayu'
        ];
        $wilayah = Kecamatan::orderBy('name', 'asc')->get();
        return view('operator.pages.master.wilayah.index', $data, compact('wilayah'));
    }

    public function view_desa($id)
    {
        $data = [
            'title' => 'Data Desa'
        ];
        $desa = Desa::where('district_id', $id)
            ->orderBy('name', 'asc')
            ->get();

        if ($desa->isEmpty()) {
            return back()->with('error', 'Data Desa Tidak Ditemukan');
        }
        return view('operator.pages.master.wilayah.view', compact('desa'), $data);
    }
}
