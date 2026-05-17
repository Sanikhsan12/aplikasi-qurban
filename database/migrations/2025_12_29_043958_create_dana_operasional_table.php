<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dana_operasional', function (Blueprint $table) {
            $table->id();

            $table->string('keperluan')->nullable();

            $table->decimal('jumlah_pengeluaran', 10, 2)->nullable();

            $table->text('keterangan')->nullable();
            
            $table->unsignedBigInteger('id_dkm')->nullable();

            $table->unsignedBigInteger('id_user')->nullable();

            $table->timestamps();

            $table->foreign('id_dkm')
                ->references('id')
                ->on('dana_dkm')
                ->onDelete('set null');
            
            // PENAMBAHAN: Foreign Key ke tabel users
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('set null'); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dana_operasional');
    }
};