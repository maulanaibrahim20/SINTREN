<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pasar\Pasar;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Http;

class PasarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pasar::create([
            'name' => 'Bangkir',
        ]);

        Pasar::create([
            'name' => 'Jatibarang',
        ]);

        Pasar::create([
            'name' => 'Jatitujuh',
        ]);
    }
}
