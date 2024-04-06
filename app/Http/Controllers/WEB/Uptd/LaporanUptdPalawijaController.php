<?php

namespace App\Http\Controllers\WEB\Uptd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanUptdPalawijaController extends Controller
{
    public function index()
    {
        return view('uptd.pages.laporan.palawija.index');
    }
}
