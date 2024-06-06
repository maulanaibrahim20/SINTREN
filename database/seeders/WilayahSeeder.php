<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $urls = [
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212010.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212011.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212020.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212030.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212040.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212041.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212050.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212060.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212061.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212070.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212080.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212081.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212090.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212100.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212101.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212110.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212120.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212130.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212140.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212150.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212160.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212161.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212162.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212170.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212171.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212180.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212190.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212200.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212210.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212220.json',
            'https://www.emsifa.com/api-wilayah-indonesia/api/villages/3212221.json',
        ];


        $allData = [];
        foreach ($urls as $url) {
            $response = Http::get($url);
            $data = $response->json();
            $allData = array_merge($allData, $data);
        }

        $desas = [];
        foreach ($allData as $desa) {
            $desas[] = [
                'id' => $desa['id'],
                'district_id' => $desa['district_id'],
                'name' => $desa['name'],
            ];
        }

        DB::table('desas')->insert($desas);


        $kec = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/districts/3212.json');
        $data = $kec->json();

        foreach ($data as $kecamatan) {
            DB::table('kecamatans')->insert([
                'id' => $kecamatan['id'],
                'regency_id' => $kecamatan['regency_id'],
                'name' => $kecamatan['name'],
            ]);
        }
    }
}
