<?php

namespace App\Models\Penyuluh;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPadi extends Model
{
    use HasFactory;

    protected $table = 'laporan_padis';

    protected $guarded = [''];

    // protected $fillable = [
    //     'tanaman_akhir_bulan_lalu',
    //     'nama_pengumpul',
    //     'jabatan',
    //     'jenis_lahan',
    // ];
    // protected $guarded = [
    //     'desa_id',
    //     'kecamatan_id',
    //     'id_rehab_jaringan_irigasi_tersier',
    // ];
}
