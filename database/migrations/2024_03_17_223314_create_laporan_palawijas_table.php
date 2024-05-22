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
        Schema::create('laporan_palawijas', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 50);
            $table->string('desa_id', 50);
            $table->string('kecamatan_id', 25);
            $table->string('jenis_lahan', 50);
            $table->integer('id_jenis_palawija');
            $table->string('jenis_bantuan', 50);
            $table->enum('tipe_data', ['panen', 'tanam', 'puso/rusak','panen_muda','panen_hijauan_pakan_ternak']);
            $table->integer('nilai')->default('0');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('desa_id')->references('id')->on('desas')->onDelete('cascade');
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->onDelete('cascade');
            $table->foreign('id_jenis_palawija')->references('id')->on('jenis_palawijas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_palawijas');
    }
};
