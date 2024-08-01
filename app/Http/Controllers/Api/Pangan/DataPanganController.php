<?php

namespace App\Http\Controllers\Api\Pangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pangan\JenisPangan;
use App\Models\Pangan\SubjenisPangan;
use App\Models\Pangan\LaporanPangan;
use App\Models\Pasar\Pasar;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

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
            $laporanPangan = LaporanPangan::where('user_id', $id)->with(['pasar', 'jenis_pangan', 'subjenis_pangan', 'user']);

            if(request('status')){

            	if(request('status') == 'terkirim'){
            		$laporanPangan->where('status', true);
            	}else{
            		$laporanPangan->where('status', false);
            	}
            }else{
            		$laporanPangan->where('status', false);
            	}



            if (!$laporanPangan) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data kosong.',
                    'data' => null
                ], 201);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan data',
                'data' => $laporanPangan->get()
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
            'subjenis_pangan_id' => 'required|string',
            //'name' => 'required|string|max:255',
            'stok' => 'required|numeric',
            'status' => 'required',
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

            'subjenis_pangan_id' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric',

        ]);

        DB::beginTransaction();
        try {
            $laporanPangan = LaporanPangan::findOrFail($id);
             $laporanPangan->update([
                    'subjenis_pangan_id' => $request->subjenis_pangan_id,
                    'stok' => $request->stok,
                    'harga' => $request->harga,
                    'status' => $request->status,
                ]);
            //$laporanPangan->save();

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

    public function grafikData($id){
       // Ambil parameter tanggal dari request (jika ada)
        $date = request()->input('date');

        // Jika ada parameter tanggal, parse dan set tanggal filter
        $filterDate = $date
            ? Carbon::parse($date)->startOfDay()
            : Carbon::today()->startOfDay();

        // Query Eloquent
        $data = SubjenisPangan::with(['laporanPangans' => function ($query) use ($id, $filterDate) {
            $query->where('user_id', $id)
                  ->whereDate('date', $filterDate) // Filter berdasarkan tanggal
                  ->selectRaw('subjenis_pangan_id, AVG(harga) as rata_rata_harga')
                  ->groupBy('subjenis_pangan_id');
        }])->get()->map(function ($subjenisPangan) {
            $rataRataHarga = $subjenisPangan->laporanPangans->isEmpty()
                ? 0
                : (int) $subjenisPangan->laporanPangans->first()->rata_rata_harga;

            return [
                'x' => $subjenisPangan->name,
                'y' => number_format($rataRataHarga, 0, '', '')
            ];
        });

        $responseData = [
            'status' => 'success',
            'message' => 'Berhasil mengupdate data',
            'data' => $data,
        ];

        return response()->json($responseData, 200);

    }
}
