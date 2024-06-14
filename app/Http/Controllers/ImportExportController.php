<?php

namespace App\Http\Controllers;

use App\Imports\ExportLaporanPadi;
use App\Imports\ImportLaporanPadi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller
{
   
    public function import()
    {
        Excel::import(new ImportLaporanPadi, request()->file('file'));

        return back();
    }
}
