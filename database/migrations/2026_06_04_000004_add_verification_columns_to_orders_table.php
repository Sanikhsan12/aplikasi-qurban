<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('verified_at')->nullable()->after('alasan_penolakan');
            $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
            $table->timestamp('rejected_at')->nullable()->after('verified_by');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');

            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn(['verified_at', 'verified_by', 'rejected_at', 'rejected_by']);
        });
    }
};
