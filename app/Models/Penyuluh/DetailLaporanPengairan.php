<?php

namespace App\Models\Penyuluh;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLaporanPengairan extends Model
{
    use HasFactory;

    protected $table = 'detail_laporan_pengairan';

    protected $guarded = [''];

    // protected $guarded = [
    //     'id_laporan',
    //     'id_laporan_padi',
    //     'created_at',
    //     'updated_at',
    // ];

    // protected $fillable = [
    //     'jenis_pengairan',
    //     'tanaman_akhir_bulan_lalu',
    //     'panen',
    //     'tanam',
    //     'puso_rusak',
    //     'tanaman_akhir_bulan_laporan',

    // ];
}
