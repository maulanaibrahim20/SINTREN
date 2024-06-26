<?php

namespace App\Http\Controllers\Api\Pangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pangan\JenisPangan;
use App\Models\Pangan\LaporanPangan;
use App\Models\Pasar\Pasar;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class DataPanganController extends Controller
{
    public function getPasar()
    {
        $pasar = Pasar::all();
        $responseData = [
            'status' => 'success',
            'message' => 'Get data successful',
            'data' => $pasar
        ];
        return response()->json($responseData);
    }

    public function showAllByUser($id)
    {
        try {
            $laporanPangan = LaporanPangan::where('user_id', $id)->with(['pasar'])->get();

            if ($laporanPangan->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kosong.',
                    'data' => null
                ], 201);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data',
                'data' => $laporanPangan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan ketika mendapatkan data: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function deleteDetailById($id)
    {
        DB::beginTransaction();
        try {
            $item = LaporanPangan::findOrFail($id);
            $item->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus data',
                'data' => null
            ], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data. Database error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data. ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'pasar_id' => 'required|string',
            'jenis_pangan_id' => 'required|string',
            'name' => 'required|string|max:255',
            'kebutuhan' => 'required|numeric',
            'ketersediaan' => 'required|numeric',
            'neraca' => 'required|numeric',
            'harga' => 'required|numeric',
            'date' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $laporanPangan = LaporanPangan::create($validated);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menyimpan data',
                'data' => $laporanPangan,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal: ' . $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data. Database error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data. ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'pasar_id' => 'required|string',
            'jenis_pangan_id' => 'required|string',
            'name' => 'required|string|max:255',
            'kebutuhan' => 'required|numeric',
            'ketersediaan' => 'required|numeric',
            'neraca' => 'required|numeric',
            'harga' => 'required|numeric',
            'date' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $laporanPangan = LaporanPangan::findOrFail($id);
            $laporanPangan->fill($validated);
            $laporanPangan->save();

            DB::commit();

            $responseData = [
                'status' => 'success',
                'message' => 'Berhasil mengupdate data',
                'data' => $laporanPangan,
            ];
            return response()->json($responseData, 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal: ' . $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate data. Database error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate data. ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
