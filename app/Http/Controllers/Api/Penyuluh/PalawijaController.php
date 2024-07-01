<?php

namespace App\Http\Controllers\Api\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\Operator\TanamanPalawija;
use App\Models\Penyuluh\LaporanPalawija;
use App\Models\Uptd\VerifyPalawija;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
            $laporanPalawija = LaporanPalawija::where('user_id', $id)->with(['desa', 'palawija', 'verify', 'kecamatan'])->get();

            if ($laporanPalawija->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kosong.',
                    'data' => null
                ], 201);
            }

            $result = $laporanPalawija->map(function ($item) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'desa_id' => $item->desa_id,
                    'desa_name' => $item->desa->name,
                    'kecamatan_id' => $item->kecamatan_id,
                    'kecamatan_name' => $item->kecamatan ? $item->kecamatan->name : "",
                    'jenis_lahan' => $item->jenis_lahan,
                    'id_jenis_palawija' => $item->id_jenis_palawija,
                    'palawija_name' => $item->palawija->name,
                    'jenis_bantuan' => $item->jenis_bantuan,
                    'tipe_data' => $item->tipe_data,
                    'nilai' => $item->nilai,
                    'date' => $item->date,
                    'status' => $item->verify->status,
                    'catatan' => $item->verify->catatan,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data',
                'data' => $result
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
            $item = LaporanPalawija::findOrFail($id);
            $verify = VerifyPalawija::where('laporan_id', $id)->first();

            if ($verify) {
                $verify->delete();
            }

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
            'kecamatan_id' => 'required|string',
            'desa_id' => 'required|string',
            'date' => 'required|string|max:255',
            'jenis_lahan' => 'required|string|max:255',
            'jenis_bantuan' => 'required|string|max:255',
            'id_jenis_palawija' => 'required|string|max:255',
            'tipe_data' => 'required|string|max:255',
            'nilai' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $palawija = LaporanPalawija::create($validated);

            $verify = VerifyPalawija::create([
                'laporan_id' => $palawija->id,
                'status' => 'tunggu'
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menyimpan data',
                'data' => $palawija,
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
            'kecamatan_id' => 'required|string',
            'desa_id' => 'required|string',
            'date' => 'required|string|max:255',
            'jenis_lahan' => 'required|string|max:255',
            'jenis_bantuan' => 'required|string|max:255',
            'id_jenis_palawija' => 'required|string|max:255',
            'tipe_data' => 'required|string|max:255',
            'nilai' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $palawija = LaporanPalawija::findOrFail($id);
            $palawija->fill($validated);
            $palawija->save();

            $verify = VerifyPalawija::where('laporan_id', $palawija->id)->firstOrFail();
            $verify->status = 'tunggu';
            $verify->save();

            DB::commit();

            $responseData = [
                'status' => 'success',
                'message' => 'Berhasil mengupdate data',
                'data' => $palawija,
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
