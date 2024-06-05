<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanPangan;
use App\Models\Pangan\KategoriPangan;
use App\Models\Pasar\Pasar;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPanganExport;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        // Ambil data laporan pangan sesuai filter tanggal
        $laporanPangan = LaporanPangan::whereBetween('date', [$request->start_date, $request->end_date])->get();

        // Ekspor data menggunakan class LaporanPanganExport
        return Excel::download(new LaporanPanganExport($laporanPangan), 'laporan_pangan.xlsx');
    }
}
