<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function operator()
    {
        return view('operator.pages.dashboard.index');
    }

    public function pertanian()
    {
        return view('pertanian.pages.dashboard.index');
    }

    public function uptd()
    {
        return view('uptd.pages.dashboard.index');
    }

    public function penyuluh()
    {
        return view('penyuluh.pages.dashboard.index');
    }
}
