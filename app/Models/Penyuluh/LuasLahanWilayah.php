<?php

namespace App\Models\Penyuluh;

use App\Models\Wilayah\Kecamatan;
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
}
