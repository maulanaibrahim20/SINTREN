<?php

namespace Database\Seeders;

use App\Models\Operator\TanamanPadi;
use App\Models\Operator\TanamanPalawija;
use App\Models\Penyuluh\Pengairan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterTanamanSeeder extends Seeder
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

        $padiNames = ['Inbrida', 'Hibrida'];

        foreach ($padiNames as $padi)
            TanamanPadi::create([
                'name' => $padi,
            ]);

        $pengairanNames = ['Sawah Irigasi', 'Sawah Tadah Huja', 'Sawah Rawa Pasang Surut', 'Sawah Rawa Lebak'];

        foreach ($pengairanNames as $pengairan)
            Pengairan::create([
                'name' => $pengairan,
            ]);
    }
}
