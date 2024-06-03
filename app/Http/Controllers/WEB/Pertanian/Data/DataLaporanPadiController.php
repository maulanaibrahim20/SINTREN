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
        // Menambah batas waktu eksekusi menjadi 60 detik
        // set_time_limit(300);

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
                'filterKecamatanData' => $filterKecamatan,
                'filterDesaData' => $filterDesa,
                'filterDateData' => $dateRange,
            ]);
        });
    }

    public function exportPdf(Request $request)
    {
        $filterKecamatanId = $request->input('filterKecamatan');
        $filterDesaId = $request->input('filterDesa');
        $filterDate = $request->input('filterDate');

        // Menguraikan rentang tanggal dari filterDate
        if (!empty($filterDate)) {
            [$startDate, $endDate] = explode(' to ', $filterDate);
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

        $pdf = Pdf::loadView('pertanian.pages.data.padi.pdf.index', ['laporanPadiPdf' => []], $viewData)->setPaper("a4");

        $query->chunk(1000, function ($dataTransaksi) use ($pdf) {
            $data['laporanPadiPdf'] = $dataTransaksi;
            $pdf->loadView('pertanian.pages.data.padi.pdf.index', $data)->appendPDF();
        });

        return $pdf->stream('data_laporan_padi.pdf');
    }
}
