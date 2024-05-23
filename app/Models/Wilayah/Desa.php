<?php

namespace App\Models\Wilayah;

use App\Models\Penyuluh\LuasLahanWilayah;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'district_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'penugasan_penyuluh', 'desa_id', 'user_id');
    }

    public function luasLahanWilayah()
    {
        return $this->hasOne(LuasLahanWilayah::class, 'desa_id');
    }

    public function penugasanPenyuluh()
    {
        return $this->belongsToMany(User::class, 'penugasan_penyuluh', 'desa_id', 'user_id');
    }
}
