<?php

namespace App\Models\Penyuluh;

use App\Models\Operator\TanamanPalawija;
use App\Models\User;
use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kecamatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPalawija extends Model
{
    use HasFactory;
    protected $table = 'laporan_palawijas';
    protected $guarded = [''];

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function palawija()
    {
        return $this->belongsTo(TanamanPalawija::class, 'id_jenis_palawija');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
