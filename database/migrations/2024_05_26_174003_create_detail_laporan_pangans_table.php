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
        Schema::create('detail_laporan_pangans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_laporan_pangans');
            $table->double('kebutuhan');
            $table->double('ketersediaan');
            $table->double('neraca');
            $table->integer('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_laporan_pangans');
    }
};
