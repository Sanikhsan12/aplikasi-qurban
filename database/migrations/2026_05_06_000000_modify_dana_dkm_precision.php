<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Increase precision to support larger amounts (e.g., up to 999,999,999,999.99)
        // Using raw statement to avoid requiring doctrine/dbal for column modifications.
        DB::statement('ALTER TABLE `dana_dkm` MODIFY `jumlah_dana` DECIMAL(14,2) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to decimal(10,2)
        DB::statement('ALTER TABLE `dana_dkm` MODIFY `jumlah_dana` DECIMAL(10,2) NULL');
    }
};
