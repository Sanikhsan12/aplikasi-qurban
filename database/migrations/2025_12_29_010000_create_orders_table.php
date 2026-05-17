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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->references('id')->on('users')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('ketersediaan_hewan_id')
                ->nullable()
                ->references('id')->on('ketersediaan_hewan')
                ->constrained('ketersediaan_hewan')
                ->nullOnDelete();

            $table->foreignId('pelaksanaan_id')
                ->nullable()
                ->references('id')->on('pelaksanaans')
                ->constrained('pelaksanaans')
                ->nullOnDelete();

            $table->foreignId('bank_id')
                ->nullable()
                ->references('id')->on('bank_penerima')
                ->constrained('bank_penerima')
                ->nullOnDelete();

            $table->string('jenis_hewan')->nullable();
            
            $table->enum('tipe_pendaftaran', ['transfer', 'kirim langsung']);

            $table->integer('total_hewan');

            $table->decimal('total_harga', 15, 2);

            // snapshot bobot (WAJIB ADA)
            $table->decimal('berat_hewan', 8, 2);

            // hasil hitung otomatis
            $table->decimal('perkiraan_daging', 8, 2);

            $table->enum('status', [
                'menunggu verifikasi',
                'disetujui',
                'ditolak'
            ])->default('menunggu verifikasi');

            $table->string('alasan_penolakan')->nullable();
            $table->string('bukti_pembayaran')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
