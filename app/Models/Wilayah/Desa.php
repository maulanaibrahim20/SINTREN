<?php

namespace App\Models\Wilayah;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'district_id', 'id');
    }
}
