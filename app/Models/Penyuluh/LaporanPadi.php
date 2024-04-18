<?php

namespace App\Models\Penyuluh;

use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kecamatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPadi extends Model
{
    use HasFactory;

    protected $table = 'laporan_padis';

    protected $guarded = [''];


    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function details()
    {
        return $this->hasMany(DetailLaporanPadi::class, 'id_laporan_padi');
    }

    public function pengairan()
    {
        return $this->hasMany(DetailLaporanPengairan::class, 'id_laporan_padi');
    }
}
