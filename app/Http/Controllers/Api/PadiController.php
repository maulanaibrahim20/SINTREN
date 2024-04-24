<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\DetailLaporanPadi;
use App\Models\Penyuluh\DetailLaporanPengairan;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\Pengairan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PadiController extends Controller
{

    protected $pengairan;

    public function __construct(Pengairan $pengairan)
    {
        $this->pengairan = $pengairan;
    }
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

    public function showAllByUser(Request $request)
    {
        $laporanPadi = LaporanPadi::where('nama_pengumpul', $request->name)->get();

        $responseData = [
            'status' => 'success',
            'message' => 'Create successful',
            'data' => $laporanPadi
        ];
        return response()->json($responseData);
    }

    public function showDetailPadiByIdLaporanPadi(Request $request)
    {
        $detail = DetailLaporanPadi::where('id_laporan_padi', $request->id)->get();

        $responseData = [
            'status' => 'success',
            'message' => 'Create successful',
            'data' => $detail
        ];
        return response()->json($responseData);
    }

    public function showDetailPengairanByIdLaporanPadi(Request $request)
    {
        $detail = DetailLaporanPengairan::where('id_laporan_padi', $request->id)->get();

        $responseData = [
            'status' => 'success',
            'message' => 'Create successful',
            'data' => $detail
        ];
        return response()->json($responseData);
    }

    public function store(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $padi = LaporanPadi::create([
                    'desa_id' => $request->desa_id,
                    'kecamatan_id' => "123",
                    'nama_pengumpul' => "sama",
                    'jabatan' => 'penyuluh',
                    'jenis_lahan' => $request->jenis_lahan,
                    // 'id_rehab_jaringan_irigasi_tersier' => $irigasitersier->id,
                ]);

                foreach ($request->detailPadi as $detailData) {
                    $padi->details()->create($detailData);
                }

                foreach ($request->detailPengairan as $pengairan) {
                    $padi->pengairan()->create($pengairan);
                }
            });

            $responseData = [
                'status' => 'success',
                'message' => 'Create successful',
                'data' => null
            ];
            return response()->json($responseData, 201);
        } catch (QueryException $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Failed to store data. Database error : ' . $e,
                'data' => null
            ];
            return response()->json($responseData, 500);
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Failed to store data.' . $e->getMessage(),
                'data' => null
            ];
            return response()->json($responseData, 500);
        }
    }

    public function deletePadiById(Request $request)
    {
        try {
            $laporanPadi = LaporanPadi::findOrFail($request->id);
            $laporanPadi->delete();
            $responseData = [
                'status' => 'success',
                'message' => 'Delete successful.',
                'data' => null
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Failed to delete.',
                'data' => null
            ];
            return response()->json($responseData, 500);
        }
    }

    public function deleteDetailPadiById(Request $request)
    {
        try {
            $detailPadi = DetailLaporanPadi::findOrFail($request->id);
            $detailPadi->delete();
            $responseData = [
                'status' => 'success',
                'message' => 'Delete successful.',
                'data' => null
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Failed to delete.',
                'data' => null
            ];
            return response()->json($responseData, 500);
        }
    }

    public function deleteDetailPengairanById(Request $request)
    {
        try {
            $detailPadi = DetailLaporanPengairan::findOrFail($request->id);
            $detailPadi->delete();
            $responseData = [
                'status' => 'success',
                'message' => 'Delete successful.',
                'data' => null
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Failed to delete.',
                'data' => null
            ];
            return response()->json($responseData, 500);
        }
    }
}
