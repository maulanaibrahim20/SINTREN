<?php

namespace Database\Seeders;

use App\Models\Penyuluh\JenisPalawija;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisPalawijaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $jenisPalawija = [
            [
                'name' => 'jagung Hibrida',
                'bantuan' => true,
                'panen_muda' => true,
                'panen_pakan_ternak' => true,
                'total_produksi' => false,
            ],
            [
                'name' => 'jagung komposit',
                'bantuan' => true,
                'panen_muda' => true,
                'panen_pakan_ternak' => false,
                'total_produksi' => false,
            ],
            [
                'name' => 'jagung lokal',
                'bantuan' => true,
                'panen_muda' => true,
                'panen_pakan_ternak' => false,
                'total_produksi' => false,
            ],
            [
                'name' => 'kedelai',
                'bantuan' => true,
                'panen_muda' => false,
                'panen_pakan_ternak' => true,
                'total_produksi' => false,
            ],
            [
                'name' => 'kacang tanah',
                'bantuan' => false,
                'panen_muda' => false,
                'panen_pakan_ternak' => false,
                'total_produksi' => false,
            ],
            [
                'name' => 'Ubi Kayu Singkong',
                'bantuan' => false,
                'panen_muda' => false,
                'panen_pakan_ternak' => true,
                'total_produksi' => false,
            ],
            [
                'name' => 'ubi Jalar/Ketela Rambat',
                'bantuan' => false,
                'panen_muda' => false,
                'panen_pakan_ternak' => false,
                'total_produksi' => false,
            ],
        ];

        JenisPalawija::insert($jenisPalawija);
    }
}
