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
            'category' => 'Padi',
            'description' => 'Padi Inpari 32 adalah padi yang tahan terhadap hama dan penyakit, serta memiliki hasil panen yang tinggi.',
        ]);

        TanamanPadi::create([
            'name' => 'Hibrida 33',
            'category' => 'Padi',
            'description' => 'Padi Inpari 33 adalah padi yang tahan terhadap hama dan penyakit, serta memiliki hasil panen yang tinggi.',
        ]);
    }
}
