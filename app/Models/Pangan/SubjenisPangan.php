<?php

namespace App\Models\Pangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pangan\JenisPangan;

class SubjenisPangan extends Model
{
    use HasFactory;
    protected $table = 'subjenis_pangans';
    protected $guarded = [];

    public function jenis_pangan()
    {
        return $this->belongsTo(JenisPangan::class, 'jenis_pangan_id');
    }
}
