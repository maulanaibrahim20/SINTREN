<?php

namespace App\Models\Penyuluh;

use App\Models\Operator\Padi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLaporanPadi extends Model
{
    use HasFactory;

    protected $table = 'detail_laporan_padi';

    protected $guarded = [''];

    // protected $guarded = [
    //     'id_laporan_padi',
    //     'created_at',
    //     'updated_at',
    // ];
    // protected $fillable = [
    //     'jenis_padi',
    //     'jenis_bantuan',
    //     'tanaman_akhir_bulan_lalu',
    //     'panen',
    //     'tanam',
    //     'puso/rusak',
    //     'tanaman_akhir_bulan_laporan',
    // ];

    public function getPadi()
    {
        return $this->belongsTo(LaporanPadi::class, 'id_laporan_padi');
    }

    public function padi()
    {
        return $this->belongsTo(Padi::class, 'jenis_padi');
    }
}
