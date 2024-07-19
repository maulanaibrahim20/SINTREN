<?php

namespace App\Models\Pasar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasar extends Model
{
    use HasFactory;

    protected $table = 'pasars';
    protected $guarded = [''];

    public function laporanPangans()
    {
        return $this->hasMany(LaporanPangan::class, 'pasar_id');
    }

    public function laporanPangan()
    {
        return $this->hasMany(LaporanPangan::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_pasar', 'pasar_id', 'user_id');
    }
}
