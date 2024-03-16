<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_padis', function (Blueprint $table) {
            $table->id();
            $table->string('desa_id', '25');
            $table->string('kecamatan_id', '25');
            $table->string('tanaman_akhir_bulan_lalu', '25');
            $table->string('nama_pengumpul', '25');
            $table->string('jabatan', '25');
            $table->string('jenis_lahan', '25');
            $table->bigInteger('id_rehab_jaringan_irigasi_tersier')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_padis');
    }
};
