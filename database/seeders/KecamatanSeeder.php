<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/districts/3212.json');
        $data = $response->json();

        foreach ($data as $kecamatan) {
            DB::table('kecamatans')->insert([
                'id' => $kecamatan['id'],
                'regency_id' => $kecamatan['regency_id'],
                'name' => $kecamatan['name'],
            ]);
        }
    }
}
