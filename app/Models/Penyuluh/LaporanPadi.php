<?php

namespace App\Models\Penyuluh;

use App\Models\Operator\Padi;
use App\Models\Operator\TanamanPadi;
use App\Models\User;
use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kecamatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pengairan()
    {
        return $this->belongsTo(Pengairan::class, 'id_jenis_pengairan');
    }

    public function padi()
    {
        return $this->belongsTo(TanamanPadi::class, 'jenis_padi');
    }
}
