<?php

namespace Database\Seeders;

use App\Models\Penyuluh\Penyuluh;
use App\Models\Pertanian\Pertanian;
use App\Models\Role;
use App\Models\Uptd\Uptd;
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
        $user = User::factory()->create([
            'username' => 'pertanian',
            'role_id' => Role::PERTANIAN,
        ]);
        Pertanian::create([
            'alamat' => 'indramayu',
            'user_id' => $user->id,
            'no_telp' => '081272121'
        ]);
        $users = User::factory()->create([
            'username' => 'uptd',
            'role_id' => Role::UPTD,
        ]);
        $uptd = Uptd::create([
            'alamat' => 'indramayu',
            'user_id' => $users->id,
            'no_telp' => '085123123'
        ]);
        Uptd::create([
            'alamat' => 'indramayu',
            'user_id' => $uptd->id,
            'no_telp' => '081272121'
        ]);
        $penyuluh = User::factory()->create([
            'username' => 'penyuluh',
            'role_id' => Role::PENYULUH,
        ]);
        Penyuluh::create([
            'alamat' => 'indramayu',
            'user_id' => $penyuluh->id,
            'no_telp' => '081272121'
        ]);
    }
}
