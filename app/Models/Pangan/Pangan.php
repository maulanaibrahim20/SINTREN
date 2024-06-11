<?php

namespace App\Models\Pangan;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pangan extends Model
{
    use HasFactory;

    protected $guarded = [''];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
