<?php

namespace Database\Seeders;

use App\Models\Penyuluh\Penyuluh;
use App\Models\Pertanian\Pertanian;
use App\Models\Pangan\Pangan;
use App\Models\Pasar\PetugasPasar;
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
        $roles = ['OPERATOR', 'PERTANIAN', 'UPTD', 'PENYULUH', 'PANGAN', 'PETUGAS PASAR'];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
            ]);
        }

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
            'user_id' => $user['id'],
            'no_telp' => '081272121'
        ]);
        $uptd = User::factory()->create([
            'username' => 'uptd',
            'role_id' => Role::UPTD,
        ]);
        Uptd::create([
            'alamat' => 'indramayu',
            'user_id' => $uptd['id'],
            'no_telp' => '085123123',
            'kecamatan_id' => '3212010',
        ]);
        $penyuluh = User::factory()->create([
            'username' => 'penyuluh',
            'role_id' => Role::PENYULUH,
        ]);
        Penyuluh::create([
            'alamat' => 'indramayu',
            'user_id' => $penyuluh['id'],
            'no_telp' => '081272121',
            'kecamatan_id' =>  '3212010',
            'createdBy' => $penyuluh['id'],
        ]);
        $pangan = User::factory()->create([
            'username' => 'pangan',
            'role_id' => Role::PANGAN,
        ]);
        Pangan::create([
            'alamat' => 'indramayu',
            'user_id' => $pangan['id'],
            'no_telp' => '085797288',
        ]);
        // $petugaspasar = User::factory()->create([
        //     'username' => 'pasar',
        //     'role_id' => Role::PASAR,
        // ]);
        // PetugasPasar::create([
        //     'alamat' => 'indramayu',
        //     'user_id' => $petugaspasar['id'],
        //     'no_telp' => '0857982995',
        //     'pasar_id' =>  '1',
        // ]);
    }
}
