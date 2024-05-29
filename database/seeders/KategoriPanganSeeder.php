<?php

namespace Database\Seeders;

use App\Models\Pangan\KategoriPangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriPanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KategoriPangan::create([
            'name' => 'Sayuran',
        ]);
        KategoriPangan::create([
            'name' => 'Buah',
        ]);
        KategoriPangan::create([
            'name' => 'Daging',
        ]);
    }
}
