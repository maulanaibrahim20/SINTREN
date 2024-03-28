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
            $table->string('jenis_lahan', 25);
            $table->string('nama_pengumpul', 25);
            $table->string('desa_id', 30);
            $table->string('kecamatan_id', 30);
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
