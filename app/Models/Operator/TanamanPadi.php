<?php

namespace App\Models\Operator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanamanPadi extends Model
{
    use HasFactory;

    protected $table = 'tanaman_padis';
    protected $guarded = [''];
    // protected $fillable = ['name', 'category', 'description'];
}
