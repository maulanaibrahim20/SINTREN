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
        Schema::create('pangans', function (Blueprint $table) {
            $table->id();
            $table->string('pasar_id');
            $table->string('nama_petugas', 25);
            $table->enum('status', ['_terkirim', 'terkirim'])->default('_terkirim');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pangans');
    }
};
