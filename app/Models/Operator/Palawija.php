<?php

namespace App\Models\Operator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Palawija extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function kategori()
    {
        return $this->belongsTo(KategoriTanamanPalawija::class, 'category');
    }
}
