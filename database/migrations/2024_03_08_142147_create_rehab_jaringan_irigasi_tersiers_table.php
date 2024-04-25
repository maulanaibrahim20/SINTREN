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
        Schema::create('rehab_jaringan_irigasi_tersiers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('panen');
            $table->bigInteger('tanam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehab_jaringan_irigasi_tersiers');
    }
};
