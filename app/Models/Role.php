<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    const OPERATOR = 1;
    const PERTANIAN = 2;
    const UPTD = 3;
    const PENYULUH = 4;
    const PANGAN = 5;
    const PASAR = 6;


    protected $fillable = ['name'];

    public $timestamp = false;
}
