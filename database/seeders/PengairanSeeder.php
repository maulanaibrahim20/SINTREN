<?php

namespace Database\Seeders;

use App\Models\Penyuluh\Pengairan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengairanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengairan::create([
            'name' => 'sawah irigasi',
        ]);
        Pengairan::create([
            'name' => 'sawah tadah hujan',
        ]);
        Pengairan::create([
            'name' => 'sawah rawa pasang surut',
        ]);
        Pengairan::create([
            'name' => 'sawah rawa lebak',
        ]);
    }
}
