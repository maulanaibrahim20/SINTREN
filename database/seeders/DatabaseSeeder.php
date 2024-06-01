<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Penyuluh\LuasLahanWilayah;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PengairanSeeder::class);
        $this->call(JenisPadiSeeder::class);
        $this->call(TanamanPalawijaSeeder::class);
        $this->call(KecamatanSeeder::class);
        $this->call(DesaSeeder::class);
        $this->call(PasarSeeder::class);
    }
}
