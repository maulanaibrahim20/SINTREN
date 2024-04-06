<?php

namespace App\Models\Operator;

use App\Models\Penyuluh\DetailLaporanPalawija;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanamanPalawija extends Model
{
    use HasFactory;

    protected $guarded = [''];

    public function kategori()
    {
        return $this->belongsTo(KategoriTanamanPalawija::class, 'category');
    }

    public function detailPalawija()
    {
        return $this->belongsTo(DetailLaporanPalawija::class, 'id_laporan_palawija');
    }
}
