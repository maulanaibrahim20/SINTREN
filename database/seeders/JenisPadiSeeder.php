<?php

namespace Database\Seeders;

use App\Models\Operator\TanamanPadi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisPadiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TanamanPadi::create([
            'name' => 'Inbrida 1',
        ]);

        TanamanPadi::create([
            'name' => 'Hibrida',
        ]);
    }
}
