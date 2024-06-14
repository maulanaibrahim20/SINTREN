<?php

namespace App\Imports;

use App\Models\Penyuluh\LaporanPadi;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportLaporanPadi implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = User::where('role_id', 4)->first();

        return new LaporanPadi([
            'user_id' => $user->id,
            'desa_id' => $row['6'],
            'kecamatan_id' => $row['5'],
            'date' => $row['1'],
            'jenis_lahan' => rand(0, 1) == 1 ? 'sawah' : 'non sawah',
            'id_jenis_padi' => rand(1, 2),
            'jenis_bantuan' => rand(0, 1) == 1 ? 'bantuan pemerintah' : 'non bantuan pemerintah',
            'id_jenis_pengairan' => rand(1, 3),
            'tipe_data' => 'panen', // Ubah ini menjadi string bukan array
            'nilai' => $row['8'],
        ]);
    }
}
