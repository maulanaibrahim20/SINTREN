<?php

namespace App\Http\Controllers\WEB\Pertanian\Data;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kecamatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DataLaporanPadiController extends Controller
{
    protected $laporanPadi, $kecamatan, $desa;

    public function __construct(LaporanPadi $laporanPadi, Kecamatan $kecamatan, Desa $desa)
    {
        $this->laporanPadi = $laporanPadi;
        $this->kecamatan = $kecamatan;
        $this->desa = $desa;
    }
    public function index()
    {
        $laporanPadi = Cache::remember('laporanPadi', 600, function () {
            return $this->laporanPadi->with('kecamatan')->get();
        });

        $filterKecamatan = Cache::remember('filterKecamatan', 600, function () use ($laporanPadi) {
            return $this->kecamatan::whereIn('id', $laporanPadi->pluck('kecamatan_id'))->get();
        });

        $data['laporanPadi'] = $laporanPadi;
        $data['filterKecamatan'] = $filterKecamatan;

        return view('pertanian.pages.data.padi.index', $data);
    }

    public function filter(Request $request)
    {
        $messages = [
            "required" => "Kolom :attribute Harus Diisi",
        ];

        $this->validate($request, [
            'filterKecamatan' => 'required',
            'filterDesa' => 'required',
            'dateRange' => 'required',
        ], $messages);

        return DB::transaction(function () use ($request) {
            $filterKecamatan = $request->input('filterKecamatan');
            $filterDesa = $request->input('filterDesa');
            $dateRange = $request->input('dateRange');

            list($startDate, $endDate) = explode(' to ', $dateRange);

            $filter = $this->laporanPadi
                ::where('kecamatan_id', $filterKecamatan)
                ->where('desa_id', $filterDesa)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            return back()->with([
                'filtering' => $filter,
                'messages' => 'Data berhasil difilter',
                'status' => 'success',
                'filterKecamatan' => $filterKecamatan,
                'filterDesa' => $filterDesa,
                'filterDate' => $dateRange,
            ]);
        });
    }

    public function exportPdf(Request $request)
    {
        $filterKecamatan = $request->input('filterKecamatan');
        $filterDesa = $request->input('filterDesa');
        $filterDate = $request->input('filterDate');
        dd($filterKecamatan);

        if (empty($filterKecamatan | $filterDesa)) {
            $dataTransaksi = $this->laporanPadi::all();
        } else {
            $filter = $this->laporanPadi::where('kecamatan_id', $filterKecamatan)
                ->where('desa_id', $filterDesa)
                ->get();
        }



        $viewData = [
            'title' => 'Laporan Luas Tanaman Padi',
            'date' => Carbon::now()->locale('id')->translatedFormat('d F Y'),
        ];

        if (empty($filterKecamatan | $filterDesa)) {
            $data['laporanPadiPdf'] = $dataTransaksi;
        } else {
            $data['laporanPadiPdf'] = $filter;
            dd($data);
        }
        $pdf = Pdf::loadView('pertanian.pages.data.padi.pdf.index', $data, $viewData)->setPaper("a4");
        return $pdf->stream('data_laporan_padi.pdf');
    }
}
