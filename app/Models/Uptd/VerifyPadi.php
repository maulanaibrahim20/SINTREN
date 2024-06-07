<?php

namespace App\Models\Uptd;

use App\Models\Penyuluh\LaporanPadi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyPadi extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'user_id',
        'status',
        'catatan'
    ];

    public function laporanPadi()
    {
        return $this->belongsTo(LaporanPadi::class, 'laporan_id');
    }
}
