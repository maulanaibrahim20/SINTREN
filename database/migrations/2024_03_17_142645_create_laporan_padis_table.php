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
            $table->string('user_id', 50);
            $table->string('desa_id', 50);
            $table->string('kecamatan_id', 25);
            $table->string('date',50);
            $table->string('jenis_lahan', 50);
            $table->string('jenis_padi', 50);
            $table->string('jenis_bantuan', 50);
            $table->integer('id_jenis_pengairan',)->nullable();
            $table->enum('tipe_data', ['panen', 'tanam', 'puso/rusak']);
            $table->integer('nilai')->default('0');
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
