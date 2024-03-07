<?php

namespace App\Models\Penyuluh;

use App\Models\User;
use App\Models\Wilayah\Kecamatan;
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
}
