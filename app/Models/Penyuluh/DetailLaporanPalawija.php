<?php

namespace App\Models\Penyuluh;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLaporanPalawija extends Model
{
    use HasFactory;

    protected $table = 'detail_laporan_palawijas';

    protected $fillable = [
        'id_laporan_palawija',
        'id_jenis_palawija',
        'jenis_palawija',
        'sub_jenis_palawija',
        'jenis_bantuan',
        'jenis_lahan',
        'tanaman_akhir_bulan_lalu',
        'tanam',
        'panen',
        'puso_rusak',
        'panen_muda',
        'panen_pakan_ternak',
        'tanaman_akhir_bulan_laporan',
        'total_produksi',
    ];

    public function laporanPalawija()
    {
        return $this->belongsTo(LaporanPalawija::class, 'id_laporan_palawija');
    }
}
