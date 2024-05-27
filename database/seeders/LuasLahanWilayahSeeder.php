<?php

namespace Database\Seeders;

use App\Models\Penyuluh\LuasLahanWilayah;
use App\Models\Penyuluh\Pengairan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LuasLahanWilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LuasLahanWilayah::create([
            'kecamatan_id' => 3212170,
            'desa_id' => '3212010007',
            'lahan_sawah' => 500,
            'lahan_non_sawah' => 500
        ]);
        LuasLahanWilayah::create([
            'kecamatan_id' => 3212170,
            'desa_id' => '3212010008',
            'lahan_sawah' => 500,
            'lahan_non_sawah' => 500
        ]);
        LuasLahanWilayah::create([
            'kecamatan_id' => 3212170,
            'desa_id' => '3212010009',
            'lahan_sawah' => 500,
            'lahan_non_sawah' => 500
        ]);
        LuasLahanWilayah::create([
            'kecamatan_id' => 3212170,
            'desa_id' => '3212010010',
            'lahan_sawah' => 500,
            'lahan_non_sawah' => 500
        ]);
        LuasLahanWilayah::create([
            'kecamatan_id' => 3212170,
            'desa_id' => '3212010011',
            'lahan_sawah' => 500,
            'lahan_non_sawah' => 500
        ]);
    }
}
