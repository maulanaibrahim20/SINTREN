<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\DetailLaporanPalawija;
use App\Models\Penyuluh\JenisPalawija;
use App\Models\Penyuluh\LaporanPalawija;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PalawijaController extends Controller
{
    public function getJenisPalawija(){
        $pengairan = JenisPalawija::all();
        $responseData = [
            'status' => 'success',
            'message' => 'Get data successful',
            'data' => $pengairan
        ];
        return response()->json($responseData);
    }

    public function showAllByUser(Request $request)
    {
        $laporanPadi = LaporanPalawija::where('nama_pengumpul', $request->name)->get();

        $responseData = [
            'status' => 'success',
            'message' => 'Create successful',
            'data' => $laporanPadi
        ];
        return response()->json($responseData);
    }

    public function showDetailPalawijaByIdLaporanPalawija(Request $request){
        $detail = DetailLaporanPalawija::where('id_laporan_palawija', $request->id)->get();

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
                $palawija = LaporanPalawija::create($request->only(['desa_id', 'kecamatan_id', 'nama_pengumpul', 'jabatan', 'jenis_lahan', 'id_rehab_jaringan_irigasi_tersier']));

                foreach ($request->details as $detailData) {
                    $palawija->details()->create($detailData);
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
                'message' => 'Failed to store data. Database error : '.$e,
                'data' => null
            ];
            return response()->json($responseData, 500);
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Failed to store data.',
                'data' => null
            ];
            return response()->json($responseData, 500);
        }
    }

    public function deletePalawijaById(Request $request){
        try {
            $laporanPadi = LaporanPalawija::findOrFail($request->id);
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

    public function deleteDetailPalawijaById(Request $request){
        try {
            $detailPadi = DetailLaporanPalawija::findOrFail($request->id);
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
