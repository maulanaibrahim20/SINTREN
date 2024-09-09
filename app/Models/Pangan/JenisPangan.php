<?php

namespace App\Models\Pangan;

use App\Models\Pangan\SubJenisPangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPangan extends Model
{
    use HasFactory;

    protected $table = 'jenis_pangans';


    protected $guarded = [''];

    // public function subjenis_pangan(){
    //         return $this->hasMany(Comment::class, 'jenis_pangan_id', 'id');
    // }

}
