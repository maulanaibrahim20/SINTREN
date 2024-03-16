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
        Schema::create('detail_laporan_padi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_laporan_padi');
            $table->string('jenis_padi');
            $table->string('jenis_bantuan');
            $table->string('tanaman_akhir_bulan_lalu');
            $table->string('panen');
            $table->string('tanam');
            $table->string('puso_rusak');
            $table->string('tanaman_akhir_bulan_laporan');
            $table->foreign('id_laporan_padi')->references('id')->on('laporan_padis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_laporan_padi');
    }
};
