<?php

namespace App\Models\Penyuluh;

use App\Models\User;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Desa;
use App\Models\Uptd\PenugasanPenyuluh;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyuluh extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function penugasan()
    {
        return $this->belongsTo(PenugasanPenyuluh::class, 'user_id',);
    }
    public function createBy()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
}
