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
        Schema::create('pelaksanaans', function (Blueprint $table) {
            $table->id();

            $table->date('Tanggal_Pendaftaran')->nullable();

            $table->date('Tanggal_Penutupan')->nullable();

            $table->string('Ketuplak')->nullable();

            $table->string('Lokasi')->nullable();

            $table->date('Penyembelihan')->nullable();

            $table->enum('Status', ['Closed', 'Active'])->default('Active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaksanaans');
    }
};
