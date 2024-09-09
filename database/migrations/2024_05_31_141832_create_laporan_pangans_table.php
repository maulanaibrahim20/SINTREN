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
            $table->string('user_id', 50);
            $table->string('pasar_id', 50);
            $table->string('jenis_pangan_id', 50);
            $table->string('subjenis_pangan_id', 50);
            $table->integer('stok')->nullable();
            $table->integer('harga');
            $table->boolean('status')->default(false);
            $table->date('date');
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
