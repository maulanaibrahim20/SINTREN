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
            $table->string('tanaman_akhir_bulan_lalu');
            $table->string('panen')->nullable();
            $table->string('luas_lahan')->nullable();
            $table->string('tanam');
            $table->string('padi_rusak');
            $table->string('jenis_lahan');
            $table->string('petani');
            $table->string('jenis_padi');
            $table->string('jenis_bantuan');
            $table->string('jenis_pengairan');
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
