<?php

namespace App\Models\Pasar;
use App\Models\User;
use App\Models\Pasar\Pasar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasPasar extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function pasar()
    {
        return $this->belongsTo(Pasar::class, 'pasar_id');
    }
}
