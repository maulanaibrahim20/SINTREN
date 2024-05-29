<?php

namespace Database\Seeders;

use App\Models\Operator\TanamanPalawija;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TanamanPalawijaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Jagung Hibrida', 'Jagung Komposit', 'Jagung Lokal', 'Kedelai', 'Kacang Tanah', 'Ubi Kayu Singkong', 'Ubi Jalar/Ketela Rambat'];

        foreach ($names as $name) {
            TanamanPalawija::create([
                'name' => $name,
            ]);
        }
    }
}
