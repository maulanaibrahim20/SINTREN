<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Penyuluh\Penyuluh;
use App\Models\Pertanian\Pertanian;
use App\Models\Uptd\Uptd;
use App\Models\Wilayah\Desa;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [
        'email_verified_at',
        'remember_token',
    ];
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getAkses()
    {
        return $this->belongsTo(Role::class, "role_id");
    }

    public function penyuluh()
    {
        return $this->hasOne(Penyuluh::class);
    }

    public function uptd()
    {
        return $this->hasOne(Uptd::class);
    }
    public function pertanian()
    {
        return $this->hasOne(Pertanian::class);
    }

    public function desas()
    {
        return $this->belongsToMany(Desa::class, 'penugasan_penyuluh', 'user_id', 'desa_id');
    }
}
