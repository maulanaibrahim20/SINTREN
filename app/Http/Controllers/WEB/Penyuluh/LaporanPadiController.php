<?php

namespace App\Http\Controllers\WEB\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\Master\JenisPadi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanPadiController extends Controller
{

    protected $jenis_padi;

    public function __construct(JenisPadi $jenis_padi)
    {
        $this->jenis_padi = $jenis_padi;
    }
    public function index()
    {
        return view('penyuluh.pages.laporan_padi.index');
    }

    public function create()
    {
        $jenis_padi = $this->jenis_padi::where('created_by', Auth::user()->name)->orderBy('created_by', 'asc')->get();
        return view('penyuluh.pages.laporan_padi.create', compact('jenis_padi'));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
