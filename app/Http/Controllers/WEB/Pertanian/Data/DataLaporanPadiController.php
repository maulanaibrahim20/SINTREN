<?php

namespace App\Http\Controllers\WEB\Pertanian\Data;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kecamatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        ini_set('max_execution_time', 300);

        // Ambil data kecamatan yang ada di laporan padi
        $kecamatanId = $this->laporanPadi::pluck('kecamatan_id');
        $data['filterKecamatan'] = $this->kecamatan::whereIn('id', $kecamatanId)->get();

        // Ambil data laporan padi yang sudah terima
        $data['laporanPadi'] = $this->laporanPadi::whereHas('verifyPadi', function ($query) {
            $query->where('status', 'terima');
        })->orderBy('date', 'desc')->get();

        return view('pertanian.pages.data.padi.index', $data);
    }



    public function show($id)
    {
        $data['show'] = $this->laporanPadi::find($id);
        return view('pertanian.pages.data.padi.show', $data);
    }


    public function filter(Request $request)
    {
        $messages = [
            "required" => "Kolom :attribute Harus Diisi",
        ];

        $this->validate($request, [
            'filterKecamatan' => 'nullable',
            'filterDesa' => 'nullable',
            'dateRange' => 'nullable',
        ], $messages);

        return DB::transaction(function () use ($request) {
            $filterKecamatan = $request->input('filterKecamatan');
            $filterDesa = $request->input('filterDesa');
            $dateRange = $request->input('dateRange');

            $query = $this->laporanPadi::query();

            if ($filterKecamatan) {
                $query->where('kecamatan_id', $filterKecamatan);
            }

            if ($filterDesa) {
                $query->where('desa_id', $filterDesa);
            }

            if ($dateRange) {
                list($startDate, $endDate) = explode(' to ', $dateRange);
                $query->whereBetween('date', [$startDate, $endDate]);
            }

            $filter = $query->get();

            return back()->with([
                'filtering' => $filter,
                'messages' => 'Data berhasil difilter',
                'status' => 'success',
                'filterKecamatanData' => $filterKecamatan,
                'filterDesaData' => $filterDesa,
                'filterDateData' => $dateRange,
            ]);
        });
    }

    public function exportPdf(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $filterKecamatanId = $request->input('filterKecamatan');
        $filterDesaId = $request->input('filterDesa');
        $filterDate = $request->input('filterDate');

        if (!empty($filterDate)) {
            [$startDate, $endDate] = explode(' to ', $filterDate);
        } else {
            $startDate = null;
            $endDate = null;
        }

        $query = $this->laporanPadi::query();

        if (!empty($filterKecamatanId)) {
            $query->where('kecamatan_id', $filterKecamatanId);
        }

        if (!empty($filterDesaId)) {
            $query->where('desa_id', $filterDesaId);
        }

        if (!empty($filterDate)) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $filterKecamatanName = !empty($filterKecamatanId) ? Kecamatan::find($filterKecamatanId)->name : null;

        $filterDesaName = !empty($filterDesaId) ? Desa::find($filterDesaId)->name : null;

        $viewData = [
            'title' => 'Laporan Luas Tanaman Padi',
            'date' => Carbon::now()->locale('id')->translatedFormat('d F Y'),
            'filterKecamatan' => $filterKecamatanName,
            'filterDesa' => $filterDesaName,
            'filterDate' => $filterDate
        ];

        $html = view('pertanian.pages.data.padi.pdf.header', $viewData)->render();

        $query->chunk(1000, function ($dataTransaksi) use (&$html) {
            $data['laporanPadiPdf'] = $dataTransaksi;
            $html .= view('pertanian.pages.data.padi.pdf.content', $data)->render();
        });

        $html .= view('pertanian.pages.data.padi.pdf.footer')->render();

        $pdf = Pdf::loadHTML($html)->setPaper("a4");
        return $pdf->stream('data_laporan_padi.pdf');
    }
}
