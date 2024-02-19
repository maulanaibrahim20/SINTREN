<?php

namespace App\Models\Penyuluh\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPadi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_padi',
        'created_by',
        'user_id'
    ];
}
