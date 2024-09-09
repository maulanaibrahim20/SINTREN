<?php

namespace App\Models\Pangan;
use App\Models\User;
use App\Models\Pasar\Pasar;
use App\Models\Pangan\JenisPangan;
use App\Models\Pangan\SubjenisPangan;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPangan extends Model
{
    use HasFactory;
    protected $table = 'laporan_pangans';
    protected $guarded = [''];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function pasar()
    {
        return $this->belongsTo(Pasar::class, 'pasar_id');
    }
    public function jenis_pangan()
    {
        return $this->belongsTo(JenisPangan::class, 'jenis_pangan_id');
    }
    public function subjenis_pangan()
    {
        return $this->belongsTo(SubjenisPangan::class, 'subjenis_pangan_id');
    }

}
