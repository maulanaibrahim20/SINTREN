<?php

namespace App\Models\Pasar;

use App\Models\User;
use App\Models\Pasar\Pasar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari nama model dalam bentuk jamak
    protected $table = 'notifications';

    // Kolom yang dapat diisi secara massal
    protected $fillable = ['user_id', 'pasar_id', 'status', 'message'];

    // Default values untuk atribut
    protected $attributes = [
        'status' => '1', // Default status
        'message' => 'Belum dibuat', // Default message
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke model Pasar
    public function pasar()
    {
        return $this->belongsTo(Pasar::class, 'pasar_id');
    }
}
