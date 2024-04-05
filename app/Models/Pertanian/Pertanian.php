<?php

namespace App\Models\Pertanian;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanian extends Model
{
    use HasFactory;

    protected $guarded = [''];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
