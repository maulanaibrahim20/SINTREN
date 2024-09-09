<?php

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Inisialisasi tanggal hari ini
$tanggalHariIni = Carbon::today();

// Ambil semua user_id dari tabel 'notifications'
$notifications = DB::table('notifications')->get();

foreach ($notifications as $notification) {
    $userId = $notification->user_id;

    // Cek apakah user_id ini memiliki entri di 'laporan_pangans' untuk hari ini
    $userHasReportedToday = DB::table('laporan_pangans')
        ->where('user_id', $userId)
        ->whereDate('created_at', $tanggalHariIni)
        ->exists();

    if ($userHasReportedToday) {
        // Update status di 'notifications' menjadi 1 (sudah input data)
        DB::table('notifications')
            ->where('user_id', $userId)
            ->update(['status' => 1]);
    } else {
        // Update status di 'notifications' menjadi 0 (belum input data)
        DB::table('notifications')
            ->where('user_id', $userId)
            ->update(['status' => 0]);
    }
}

// Menampilkan pesan untuk memastikan skrip berjalan
echo "Update status di tabel notifications telah berhasil dieksekusi.";
