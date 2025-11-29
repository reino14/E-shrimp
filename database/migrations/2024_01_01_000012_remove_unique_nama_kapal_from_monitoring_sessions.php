<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_sessions', function (Blueprint $table) {
            // Drop unique constraint on nama_kapal if it exists
            $table->dropUnique(['nama_kapal']);
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_sessions', function (Blueprint $table) {
            // Re-add unique constraint if needed (for rollback)
            $table->unique('nama_kapal');
        });
    }
};










