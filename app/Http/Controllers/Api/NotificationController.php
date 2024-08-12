<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\LaporanPalawija;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Factory;

class NotificationController extends Controller
{
    public function sendVerifyNotification($id, $new)
    {
        try {
            $firebase = app('firebase.messaging');
            $laporanPadi = LaporanPadi::where('kecamatan_id', $id)
                ->whereHas('verify', function ($query) {
                    $query->where('status', 'tunggu');
                })
                ->with(['verify'])
                ->count();
            $laporanPalawija = LaporanPalawija::where('kecamatan_id', $id)
                ->whereHas('verify', function ($query) {
                    $query->where('status', 'tunggu');
                })
                ->with(['verify'])
                ->count();
            $total = $laporanPadi + $laporanPalawija;

            if ($new == "true") {
                $message = CloudMessage::withTarget('topic', 'verify-' . $id)
                    ->withNotification(Notification::create("Verifikasi Data Penyuluhan", "1 Data Baru menunggu diverifikasi"));
            } else {
                $message = CloudMessage::withTarget('topic', 'verify-' . $id)
                    ->withNotification(Notification::create("Verifikasi Data Penyuluhan", "$total Data menunggu diverifikasi"));
            }

            $firebase->send($message);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengirim notifikasi',
                'data' => $total
            ], 200);
        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim notifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sendRejectedNotification($id, $new)
    {
        try {
            $firebase = app('firebase.messaging');
            $laporanPadi = LaporanPadi::where('user_id', $id)
                ->whereHas('verify', function ($query) {
                    $query->where('status', 'tolak');
                })
                ->with(['verify'])
                ->count();
            $laporanPalawija = LaporanPalawija::where('user_id', $id)
                ->whereHas('verify', function ($query) {
                    $query->where('status', 'tolak');
                })
                ->with(['verify'])
                ->count();
            $total = $laporanPadi + $laporanPalawija;

            if ($new == "true") {
                $message = CloudMessage::withTarget('topic', 'verify-' . $id)
                    ->withNotification(Notification::create("Verifikasi Data Penyuluhan", "1 Data Penyuluhan Ditolak"));
            } else {
                $message = CloudMessage::withTarget('topic', 'verify-' . $id)
                    ->withNotification(Notification::create("Verifikasi Data Penyuluhan", "$total Data Penyuluhan Ditolak"));
            }

            $firebase->send($message);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengirim notifikasi',
                'data' => $total
            ], 200);
        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim notifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
