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
        Schema::create('laporan_pangans', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('pasar_id')->nullable();
            $table->string('jenis_pangan_id')->nullable();
            $table->string('name');
            $table->integer('kebutuhan');
            $table->integer('ketersediaan');
            $table->integer('neraca');
            $table->integer('harga');
            $table->boolean('status')->default(false);
            $table->string('date', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_pangans');
    }
};
