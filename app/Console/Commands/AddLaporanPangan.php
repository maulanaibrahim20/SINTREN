<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pangan\LaporanPangan; // Pastikan kamu mengimport model LaporanPangan
use Carbon\Carbon;

class AddLaporanPangan extends Command
{
    protected $signature = 'laporan:add-pangan';

    protected $description = 'Menambahkan data laporan pangan secara otomatis';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Logika untuk menambahkan data ke tabel laporan_pangans
        $data = [
            'user_id' => "9cb4c54b-54cd-4f4d-b450-fd99163e64c4", // Sesuaikan dengan user_id yang ada
            'pasar_id' => 1, // Sesuaikan dengan pasar_id yang ada
            'jenis_pangan_id' => 1, // Sesuaikan dengan jenis_pangan_id yang ada
            'subjenis_pangan_id' => 1, // Sesuaikan dengan subjenis_pangan_id yang ada
            'stok' => 100, // Sesuaikan dengan stok yang diinginkan
            'harga' => 12500, // Sesuaikan dengan harga yang diinginkan
            'status' => '1', // Sesuaikan dengan status yang diinginkan
            'date' => Carbon::today(), // Menggunakan tanggal hari ini
            'created_at' => now(),
            'updated_at' => now(),
        ];

        LaporanPangan::create($data);

        $this->info('Data laporan pangan berhasil ditambahkan secara otomatis.');
    }
}

