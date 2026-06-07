<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('penyembelihan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_peserta_id')->nullable()->constrained('order_peserta')->nullOnDelete();
            $table->string('nomor_sertifikat', 50)->unique();
            $table->string('nama_peserta', 255);
            $table->string('jenis_hewan', 100);
            $table->date('tanggal_penyembelihan');
            $table->string('file_path', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
