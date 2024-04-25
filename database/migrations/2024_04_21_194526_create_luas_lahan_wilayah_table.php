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
        Schema::create('luas_lahan_wilayah', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kecamatan_id');
            $table->enum('jenis_lahan', ['sawah', 'non_sawah']);
            $table->double('luas_lahan_wilayah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luas_lahan_wilayah');
    }
};
