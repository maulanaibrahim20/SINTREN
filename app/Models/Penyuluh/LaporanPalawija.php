<?php

namespace App\Models\Penyuluh;

use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kecamatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPalawija extends Model
{
    use HasFactory;

    // protected $table = 'laporan_palawijas';

    // protected $fillable = [
    //     'jenis_lahan',
    //     'desa_id',
    //     'kecamatan_id',
    //     'nama_pengumpul',
    // ];

    protected $guarded = [''];

    public function detailLaporanPalawija()
    {
        return $this->belongsTo(DetailLaporanPalawija::class, 'id_laporan_palawija');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }
}
