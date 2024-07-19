<?php

namespace Database\Seeders;

// use App\Models\Pangan\JenisPangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisPanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisPangan::create([
            'name' => 'Sayuran',
        ]);
        JenisPangan::create([
            'name' => 'Buah',
        ]);
        JenisPangan::create([
            'name' => 'Daging',
        ]);
    }
}
