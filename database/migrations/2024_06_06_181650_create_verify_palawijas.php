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
        Schema::create('verify_palawijas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laporan_id');
            $table->string('user_id', 50)->nullable();
            $table->enum('status', ['terima', 'tolak', 'tunggu'])->default('tunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_verify_palawijas');
    }
};
