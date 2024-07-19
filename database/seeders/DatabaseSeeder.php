<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            WilayahSeeder::class,
            LuasLahanWilayahSeeder::class,
            MasterTanamanSeeder::class,
            // LaporanTanamanSeeder::class,
            PasarSeeder::class,
            // JenisPanganSeeder::class,
        ]);
    }
}
