<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'username' => 'operator',
            'role_id' => Role::OPERATOR,
        ]);
        User::factory()->create([
            'username' => 'pertanian',
            'role_id' => Role::PERTANIAN,
        ]);
        User::factory()->create([
            'username' => 'uptd',
            'role_id' => Role::UPTD,
        ]);
        User::factory()->create([
            'username' => 'penyuluh',
            'role_id' => Role::PENYULUH,
        ]);
    }
}
