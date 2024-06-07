<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LuasLahanWilayah;
use App\Models\Uptd\VerifyPadi;
use App\Models\Uptd\VerifyPalawija;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function getDesa($id)
    {
        try {
            if ($id == "dinas") {
                $assignments = LuasLahanWilayah::with('desa')->get();
            } else {
                $assignments = LuasLahanWilayah::with('desa')->where('kecamatan_id', $id)->get();
            }

            if (is_null($assignments)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data kosong',
                    'data' => null
                ], 201);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil didapatkan',
                'data' => $assignments
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan.',
                'data' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendapatkan data: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function verify(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'status' => 'required|string',
            'catatan' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $verification = VerifyPadi::where('laporan_id', $id)->firstOrFail();
            if($request->tipe == 'palawija'){
                $verification = VerifyPalawija::where('laporan_id', $id)->firstOrFail();
            }
            $verification->fill($validated);
            $verification->save();

            DB::commit();

            $responseData = [
                'status' => 'success',
                'message' => 'Berhasil mengupdate data',
                'data' => $verification,
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
