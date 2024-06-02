<?php

namespace App\Models;

use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kecamatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kecamatan_id',
        'desa_id',
        'date',
        'isVerify',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }
}
