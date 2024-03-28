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
        Schema::create('detail_laporan_palawijas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_laporan_palawija');
            $table->unsignedBigInteger('id_jenis_palawija');
            $table->string('jenis_bantuan')->nullable();
            $table->double('tanaman_akhir_bulan_lalu')->default(0);
            $table->double('panen')->default(0);
            $table->double('panen_muda')->default(0);
            $table->double('panen_pakan_ternak')->default(0);
            $table->double('tanam')->default(0);
            $table->double('puso_rusak')->default(0);
            $table->double('tanaman_akhir_bulan_laporan')->default(0);
            $table->string('total_produksi')->nullable();

            $table->foreign('id_laporan_palawija')->references('id')->on('laporan_palawijas')->onDelete('cascade');

            // $table->foreign('id_jenis_palawija')->references('id')->on('jenis_palawijas')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_laporan_palawijas');
    }
};
