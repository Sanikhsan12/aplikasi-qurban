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
        Schema::create('penyembelihans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->references('id')->on('orders')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('pelaksanaan_id')
                ->references('id')->on('pelaksanaans')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('status', [
                'menunggu penyembelihan',
                'tersembelih'
            ])->default('menunggu penyembelihan');

            $table->string('dokumentasi_penyembelihan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyembelihan');
    }
};
