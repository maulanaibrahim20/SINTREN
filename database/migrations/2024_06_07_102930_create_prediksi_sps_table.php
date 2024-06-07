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
        Schema::create('prediksi_sps', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->double('nilai_prediksi', 15, 2);
            $table->double('perubahan_dari_tahun_sebelumnya', 15, 2)->nullable();
            $table->double('nilai_aktual', 15, 2)->nullable();
            $table->double('error', 15, 2)->nullable();
            $table->double('mape', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prediksi_sps');
    }
};
