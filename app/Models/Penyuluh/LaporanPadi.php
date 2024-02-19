<?php

namespace App\Models\Penyuluh;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPadi extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanaman_akhir_bulan_lalu',
        'panen',
        'luas_lahan',
        'tanam',
        'padi_rusak',
        'jenis_lahan',
        'jenis_padi',
        'jenis_bantuan',
        'jenis_pengairan',
        'petani',
    ];
}
