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
        Schema::create('jenis_palawijas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('bantuan')->default(false);
            $table->boolean('panen_muda')->default(false);
            $table->boolean('panen_pakan_ternak')->default(false);
            $table->boolean('total_produksi')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_palawijas');
    }
};
