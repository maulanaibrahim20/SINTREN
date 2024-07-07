<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LuasLahanWilayah;
use App\Models\Uptd\PenugasanPenyuluh;
use App\Models\Uptd\VerifyPadi;
use App\Models\Uptd\VerifyPalawija;
use App\Models\User;
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
                $assignments = LuasLahanWilayah::with('desa.kecamatan')->get();
            } else {
                $assignments = LuasLahanWilayah::with('desa.kecamatan')->where('kecamatan_id', $id)->get();
            }

            if ($assignments->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data kosong',
                    'data' => null
                ], 201);
            }

            $data = [];
            foreach ($assignments as $assignment) {
                $data[] = [
                    'id' => $assignment->id,
                    'kecamatan_id' => $assignment->desa->kecamatan->id ?? '',
                    'kecamatan_name' => $assignment->desa->kecamatan->name ?? '',
                    'desa_id' => $assignment->desa_id,
                    'desa_name' => $assignment->desa->name ?? '',
                    'lahan_sawah' => $assignment->lahan_sawah,
                    'lahan_non_sawah' => $assignment->lahan_non_sawah,
                    'desa' => [
                        'id' => $assignment->desa->id ?? '',
                        'district_id' => $assignment->desa->district_id ?? '',
                        'name' => $assignment->desa->name ?? '',
                    ],
                ];
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil didapatkan',
                'data' => $data
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
            if ($request->tipe == 'palawija') {
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

    public function getPenyuluh($id)
    {
        try {
            $users = User::with(['penyuluh' => function ($query) use ($id) {
                $query->where('kecamatan_id', $id);
            }, 'penugasan.desa'])
                ->whereHas('penyuluh', function ($query) use ($id) {
                    $query->where('kecamatan_id', $id);
                })->get();

            // Menyiapkan array untuk response data
            $responseData = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'alamat' => $user->penyuluh->alamat ?? null,
                    'no_telp' => $user->penyuluh->no_telp ?? null,
                    'penugasan' => $user->penugasan->map(function ($penugasan) {
                        return [
                            'id' => $penugasan->id,
                            'desa_id' => $penugasan->desa_id,
                            'desa_name' => $penugasan->desa->name ?? null
                        ];
                    })
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil didapatkan',
                'data' => $responseData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendapatkan data: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function addPenugasan(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'desa_id' => 'required|string',
        ]);

        try {
            $penugasan = PenugasanPenyuluh::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menyimpan data',
                'data' => $penugasan,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal: ' . $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data. Database error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data. ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function deletePenugasan($id)
    {
        try {
            $item = PenugasanPenyuluh::findOrFail($id);
            $item->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menghapus data',
                'data' => null
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data. Database error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data. ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
