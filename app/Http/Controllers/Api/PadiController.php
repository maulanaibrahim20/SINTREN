<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\Pengairan;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PadiController extends Controller
{
    public function getPengairan()
    {
        $pengairan = Pengairan::all();
        $responseData = [
            'status' => 'success',
            'message' => 'Get data successful',
            'data' => $pengairan
        ];
        return response()->json($responseData);
    }

    public function showAllByUser($id)
    {
        try {
            $laporanPadi = LaporanPadi::where('user_id', $id)->with(['desa', 'pengairan'])->get();

            if ($laporanPadi->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kosong.',
                    'data' => null
                ], 201);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data',
                'data' => $laporanPadi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan ketika mendapatkan data: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function deletaDetailById($id)
    {
        $item = LaporanPadi::find($id);

        if (!$item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil menghapus data',
            'data' => null
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'kecamatan_id' => 'required|string',
            'desa_id' => 'required|string',
            'jenis_lahan' => 'required|string|max:255',
            'jenis_bantuan' => 'required|string|max:255',
            'jenis_padi' => 'required|string|max:255',
            'id_jenis_pengairan' => 'integer|nullable',
            'tipe_data' => 'required|string|max:255',
            'nilai' => 'required|numeric',
        ]);

        try {
            $padi = new LaporanPadi();
            $padi->fill($validated);
            $padi->save();

            $responseData = [
                'status' => 'success',
                'message' => 'Berhasil menyimpan data',
                'data' => $padi,
            ];
            return response()->json($responseData, 201);
        } catch (QueryException $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Gagal menyimpan data. Database error: ' . $e->getMessage(),
                'data' => null
            ];
            return response()->json($responseData, 500);
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Gagal menyimpan data. ' . $e->getMessage(),
                'data' => null
            ];
            return response()->json($responseData, 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'kecamatan_id' => 'required|string',
            'desa_id' => 'required|string',
            'jenis_lahan' => 'required|string|max:255',
            'jenis_bantuan' => 'required|string|max:255',
            'jenis_padi' => 'required|string|max:255',
            'id_jenis_pengairan' => 'integer|nullable',
            'tipe_data' => 'required|string|max:255',
            'nilai' => 'required|numeric',
        ]);

        try {
            $padi = LaporanPadi::findOrFail($id);
            $padi->fill($validated);
            $padi->save();

            $responseData = [
                'status' => 'success',
                'message' => 'Berhasil mengupdate data',
                'data' => $padi,
            ];
            return response()->json($responseData, 200);
        } catch (QueryException $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Gagal mengupdate data. Database error: ' . $e->getMessage(),
                'data' => null
            ];
            return response()->json($responseData, 500);
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Gagal mengupdate data. ' . $e->getMessage(),
                'data' => null
            ];
            return response()->json($responseData, 500);
        }
    }

    public function getDataSummaryByMonth()
    {
        // Mengambil data dari model Anda
        $data = LaporanPadi::all();

        // Menginisialisasi array untuk menyimpan hasil kesimpulan
        $conclusions = [];

        // Mengelompokkan data berdasarkan bulan
        foreach ($data as $record) {
            $month = date('m', strtotime($record->created_at));
            $year = date('Y', strtotime($record->created_at));

            $key = $year . '-' . $month;

            // Jika belum ada kunci bulan tersebut, inisialisasi array untuk bulan tersebut
            if (!isset($conclusions[$key])) {
                $conclusions[$key] = [
                    'jenis_lahan' => [],
                    'jenis_pengairan' => [],
                    'jenis_bantuan' => [],
                    'desa' => [],
                    'tipe_data' => [],
                ];
            }

            // Menyimpan informasi dari setiap rekaman ke dalam array kesimpulan
            $conclusions[$key]['jenis_lahan'][] = $record->jenis_lahan;
            $conclusions[$key]['jenis_pengairan'][] = $record->id_jenis_pengairan;
            $conclusions[$key]['jenis_bantuan'][] = $record->jenis_bantuan;
            $conclusions[$key]['desa'][] = $record->desa_id;
            $conclusions[$key]['tipe_data'][] = $record->tipe_data;
        }

        // Melakukan agregasi data untuk setiap bulan
        foreach ($conclusions as $month => &$summary) {
            // Menghitung jumlah nilai
            $summary['total_nilai'] = count($summary['jenis_lahan']);

            // Menghapus duplikat dan mengambil nilai unik
            $summary['jenis_lahan'] = array_unique($summary['jenis_lahan']);
            $summary['jenis_pengairan'] = array_unique($summary['jenis_pengairan']);
            $summary['jenis_bantuan'] = array_unique($summary['jenis_bantuan']);
            $summary['desa'] = array_unique($summary['desa']);
            $summary['tipe_data'] = array_unique($summary['tipe_data']);
        }

        return response()->json($conclusions);
    }
    // // Ambil bulan saat ini dalam format 'Y-m'
    // $month = Carbon::now()->format('Y-m');

    // // Ambil data dari database berdasarkan bulan ini
    // $data = LaporanPadi::where('created_at', 'like', "$month%")->get();

    // // Kelompokkan data berdasarkan tipe_data dan hitung jumlah serta total nilai
    // $summary = $data->groupBy('tipe_data')->map(function ($items, $key) {
    //     return [
    //         'jumlah' => $items->count(),
    //         'total_nilai' => $items->sum('nilai'),
    //     ];
    // });

    // // Kembalikan respons JSON dengan data kesimpulan
    // return response()->json([
    //     'bulan' => $month,
    //     'summary' => $summary,
    // ]);
}
