<?php

namespace App\Models\Penyuluh;

use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Desa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuasLahanWilayah extends Model
{
    use HasFactory;

    protected $table  = 'luas_lahan_wilayah';

    protected $guarded = [''];

    public $timestamps = 'false';

    public function getKecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }
}
