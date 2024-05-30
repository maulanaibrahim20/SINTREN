<?php

namespace App\Models\Uptd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Wilayah\Desa;

class PenugasanPenyuluh extends Model
{
    use HasFactory;

    protected $table = 'penugasan_penyuluh';

    protected $guarded = [''];

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }
}
