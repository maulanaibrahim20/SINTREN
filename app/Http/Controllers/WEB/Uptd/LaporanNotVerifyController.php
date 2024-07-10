<?php

namespace App\Http\Controllers\WEB\Uptd;

use App\Http\Controllers\Controller;
use App\Models\Uptd\VerifyPadi;
use App\Models\Uptd\VerifyPalawija;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanNotVerifyController extends Controller
{
    public function index()
    {
        $verifikasiPadis = VerifyPadi::with('laporanPadi')->where('status', '!=', 'terima')->get();
        $verifikasiPalawijas = VerifyPalawija::with('laporanPalawija')->where('status', '!=', 'terima')->get();

        $data['laporanBelumDiverifikasi'] = $verifikasiPadis->merge($verifikasiPalawijas);

        return view('uptd.pages.laporan.laporanNotVerify', $data);
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $verify = VerifyPadi::find($id) ?? VerifyPalawija::find($id);

            if ($verify) {
                $verify->status = $request->status;

                if ($request->status == 'tolak') {
                    $verify->catatan = $request->catatan;
                }

                $verify->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Status berhasil diubah');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Status gagal diubah: ' . $e->getMessage());
        }
    }
}
