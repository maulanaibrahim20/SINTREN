<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Operator\TanamanPalawija;
use App\Models\Penyuluh\JenisPalawija;
use App\Models\Penyuluh\LaporanPalawija;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PalawijaController extends Controller
{
    public function getJenisPalawija()
    {
        $pengairan = TanamanPalawija::all();
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
            $laporanPalawija = LaporanPalawija::where('user_id', $id)->with(['desa', 'palawija'])->get();

            if ($laporanPalawija->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kosong.',
                    'data' => null
                ], 201);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data',
                'data' => $laporanPalawija
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
        $item = LaporanPalawija::find($id);

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
            'date' => 'required|string|max:255',
            'jenis_lahan' => 'required|string|max:255',
            'jenis_bantuan' => 'required|string|max:255',
            'id_jenis_palawija' => 'required|string|max:255',
            'tipe_data' => 'required|string|max:255',
            'nilai' => 'required|numeric',
        ]);

        try {
            $palawija = new LaporanPalawija();
            $palawija->fill($validated);
            $palawija->save();

            $responseData = [
                'status' => 'success',
                'message' => 'Berhasil menyimpan data',
                'data' => $palawija,
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
            'date' => 'required|string|max:255',
            'jenis_lahan' => 'required|string|max:255',
            'jenis_bantuan' => 'required|string|max:255',
            'id_jenis_palawija' => 'required|string|max:255',
            'tipe_data' => 'required|string|max:255',
            'nilai' => 'required|numeric',
        ]);

        try {
            $palawija = LaporanPalawija::findOrFail($id);
            $palawija->fill($validated);
            $palawija->save();

            $responseData = [
                'status' => 'success',
                'message' => 'Berhasil mengupdate data',
                'data' => $palawija,
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
}
