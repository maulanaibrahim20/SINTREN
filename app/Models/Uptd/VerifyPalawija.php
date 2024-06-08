<?php

namespace App\Models\Uptd;

use App\Models\Penyuluh\LaporanPalawija;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyPalawija extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'user_id',
        'status',
        'catatan'
    ];

    public function laporanPalawija()
    {
        return $this->belongsTo(LaporanPalawija::class, 'laporan_id');
    }
}
