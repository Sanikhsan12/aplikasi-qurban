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
        Schema::create('ketersediaan_hewan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_hewan');
            $table->decimal('bobot', 8, 2);       // kg
            $table->decimal('harga', 15, 2);      // rupiah
            $table->integer('jumlah');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ketersediaan_hewan');
    }
};
