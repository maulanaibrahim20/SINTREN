<?php

namespace App\Models\Operator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Padi extends Model
{
    use HasFactory;

    protected $table = 'padis';
    protected $fillable = ['name', 'category', 'description'];
}
