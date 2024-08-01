<?php

namespace Database\Seeders;

use App\Imports\ImportLaporanPadi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPadiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table("laporan_padis")->truncate();
        // DB::table("verify_padis")->truncate();

        Excel::import(new ImportLaporanPadi, public_path('/data_padi/data_padi.xlsx'));
    }
}
